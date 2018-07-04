<?php

namespace App\Support\Classes\Api;

use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Routing\UrlGenerator as Url;
use Illuminate\Contracts\Routing\ResponseFactory as Response;

class Captcha
{
    /**
     * Config repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Session store.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * bcrypt hasher.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Response factory instance.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * Log writer instance.
     *
     * @var \Illuminate\Contracts\Logging\Log
     */
    protected $log;

    /**
     * Url generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * String manipulator instance.
     *
     * @var \Illuminate\Support\Str
     */
    protected $str;

    /**
     * GD resource.
     *
     * @var resource
     */
    protected $image;

    /**
     * Construct captcha.
     *
     * @param  Repository  $config
     * @param  Session  $session
     * @param  Hasher  $hasher
     * @param  Response  $response
     * @param  Log  $log
     * @param  Url  $url
     * @param  Str  $str
     * @return void
     */
    public function __construct(
            Repository $config,
            Session $session,
            Hasher $hasher,
            Response $response,
            Log $log,
            Url $url,
            Str $str)
    {
        $this->config = $config;
        $this->session = $session;
        $this->hasher = $hasher;
        $this->response = $response;
        $this->log = $log;
        $this->url = $url;
        $this->str = $str;
    }

    public function routes(array $attributes = null)
    {
        if (app()->routesAreCached()) {
            return;
        }

        $attributes = $attributes ?: ['middleware' => ['web']];

        app('router')->group($attributes, function ($router) {
            $router->get('/captcha', CaptchaController::class.'@getCaptcha');
        });
    }

    /**
     * Creates captcha.
     *
     * @return void
     */
    public function create()
    {
        $this->image = imagecreatetruecolor(
            $this->captchaWidth(),
            $this->captchaHeight()
        );

        $backgroundColor = imagecolorallocate($this->image, 255, 255, 255);

        imagefilledrectangle(
            $this->image,
            0,
            0,
            $this->captchaWidth(),
            $this->captchaHeight(),
            $backgroundColor
        );

        $config = $this->config->get('captcha');

        if ($config['background']) {
            $this->addBackground();
        }

        if ($config['noise']) {
            $this->addNoise();
        }

        if ($config['ellipses']) {
            $this->addEllipses();
        }

        $this->addText();

        if ($config['grid']) {
            $this->addGrid();
        }

        return $this->output();
    }

    /**
     * Check captcha.
     *
     * @param  string  $value
     * @return bool
     */
    public function check($value)
    {
        if (!$this->session->has('captcha')) {
            return false;
        }

        $hash = $this->session->get('captcha.hash');

        if (!$this->session->get('captcha.case_sensitive')) {
            $value = $this->str->lower($value);
        }

        $this->session->remove('captcha');

        return $this->hasher->check($value, $hash);
    }

    /**
     * Add grid.
     *
     * @return void
     */
    protected function addGrid()
    {
        $color = $this->config->get('captcha.grid_color', [150, 150, 150]);
        $width = $this->captchaWidth();
        $height = $this->captchaHeight();
        $size = $this->config->get('captcha.grid_size', 10);
        $gridColor = imagecolorallocate($this->image, ...$color);

        for ($iw = 1; $iw < $width / $size; $iw++) {
            imageline($this->image, $iw*$size, 0, $iw*$size, $width, $gridColor);
        }

        for ($ih = 1; $ih < $height / $size; $ih++) {
            imageline($this->image, 0, $ih*$size, $width, $ih*$size, $gridColor);
        }
    }

    /**
     * Add random dots.
     *
     * @return void
     */
    protected function addNoise()
    {
        $colors = $this->config->get('captcha.noise_colors');

        $randomColor = $colors ?
            collect($colors)->random() : $this->randomColor([80, 150], [80, 150], [80, 150]);

        $pixelColor = imagecolorallocate($this->image, ...$randomColor);

        $dotsNumber = $this->config->get('captcha.noise_amount', 1000);

        for ($pixel = 0; $pixel < $dotsNumber; $pixel++) {
            imagesetpixel(
                $this->image,
                mt_rand()%$this->captchaWidth(),
                mt_rand()%$this->captchaHeight(),
                $pixelColor
            );
        }
    }

    /**
     * Add random ellipses.
     *
     * @return void
     */
    protected function addEllipses()
    {
        $colors = $this->config->get('captcha.ellipses_colors');
        $ellipsesNumber = $this->config->get('captcha.ellipses_number', 5);
        $ellipsesRadius = $this->config->get('captcha.ellipses_radius', [
            10,
            $this->captchaWidth()
        ]);

        for ($ellipse = 0; $ellipse < $ellipsesNumber; $ellipse++) {
            $randomColor = $colors ?
                collect($colors)->random() : $this->randomColor([0, 255], [0, 255], [0, 255]);
            $ellipseColor = imagecolorallocate($this->image, ...$randomColor);

            $x = mt_rand()%$this->captchaWidth();
            $y = mt_rand()%$this->captchaHeight();

            $radius = mt_rand(...$ellipsesRadius);
            imageellipse($this->image, $x, $y, $radius, $radius, $ellipseColor);
        }
    }

    /**
     * Add random background.
     *
     * @return void
     */
    protected function addBackground()
    {
        $backgrounds = $this->config->get('captcha.background_images') ?: [
            'wave1.png',
            'wave2.png',
        ];

        $background = storage_path('app/bg/'.collect($backgrounds)->random());

        if (!is_file($background)) {
            return $this->log->error(
                sprintf('Couldn\'t find captcha background file: %s', $background)
            );
        }

        list($class, $type) = explode('/', mime_content_type($background));

        $makeImage = 'imagecreatefrom'.$type;

        if (!function_exists($makeImage)) {
            return $this->log->error(
                sprintf('Unsupported captcha background type: %s', $type),
                ['background' => $background]
            );
        }

        $backgroundOpacity = $this->config
            ->get('captcha.background_opacity', 0.6);

        $background = $this->setOpacity(
            $makeImage($background),
            $backgroundOpacity
        );

        imagecopyresized(
            $this->image,
            $background,
            0,
            0,
            0,
            0,
            imagesx($this->image),
            imagesy($this->image),
            imagesx($background),
            imagesy($background)
        );
    }

    /**
     * Set image opacity.
     *
     * @param  resource  $image
     * @param  float  $opacity
     * @return resource
     */
    protected function setOpacity($image, $opacity)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        $alpha = imagecreatetruecolor($width, $height);

        $alphaColor = imagecolorallocate($alpha, 255, 255, 255);

        imagefilledrectangle($alpha, 0, 0, $width, $height, $alphaColor);

        imagecopymerge(
            $alpha,
            $image,
            0,
            0,
            0,
            0,
            $width,
            $height,
            round($opacity * 100)
        );

        return $alpha;
    }

    /**
     * Add debug plaque.
     *
     * @param  string  $text
     * @param  integer  $font
     * @return void
     */
    protected function debugPlaque($text, $font = 2)
    {
        $x1 = 0;
        $y1 = $this->captchaHeight() - imagefontheight($font);
        $x2 = imagefontwidth($font) * strlen($text);
        $y2 = $this->captchaHeight();

        imagefilledrectangle(
            $this->image,
            $x1,
            $y1,
            $x2,
            $y2,
            imagecolorallocate($this->image, 255, 255, 255)
        );

        imagestring(
            $this->image,
            $font,
            $x1,
            $y1,
            $text,
            imagecolorallocate($this->image, 0, 0, 0)
        );
    }

    /**
     * Add captch text.
     *
     * @return void
     */
    protected function addText()
    {
        $text = $this->generate();

        for ($letter = 0; $letter < strlen($text); $letter++) {
            $font = $this->font();
            $fontSize = $this->fontSize();
            $angle = $this->angle();

            list(
                $bottomLeftX,
                $bottomLeftY,
                $bottomRightX,
                $bottomRightY,
                $topLeftX,
                $topLeftY,
                $topRightX,
                $topRightY
            ) = imagettfbbox($fontSize, $angle, $font, $text[$letter]);

            $charWidth = $bottomRightX - $bottomLeftX;
            $charHeight = $topLeftY - $bottomLeftY;

            $x = $letter * $this->captchaWidth() / strlen($text) + ($charWidth / 2);
            $y = ($this->captchaHeight() / 2) - ($charHeight / 2);

            imagettftext(
                $this->image,
                $fontSize,
                $angle,
                $x,
                $y,
                $this->fontColor(),
                $font,
                $text[$letter]
            );
        }

        if ($this->config->get('app.debug')) {
            $this->debugPlaque($text);
        }
    }

    /**
     * Random font.
     *
     * @return integer
     */
    protected function font()
    {
        $fonts = $this->config->get('captcha.fonts', [
            'OpenSans-Regular',
            'From Cartoon Blocks',
            'Zebra',
        ]);

        $font = storage_path('app/fonts/'.collect($fonts)->random().'.ttf');

        if (!is_file($font)) {
            throw new RuntimeException(
                sprintf('Couldn\'t find captcha font file: %s', $font)
            );
        }

        return $font;
    }

    /**
     * Is captcha case-sensitive.
     *
     * @return bool
     */
    protected function caseSensitive()
    {
        return $this->config->get('captcha.case_sensitive', false);
    }

    /**
     * Random font size.
     *
     * @return integer
     */
    protected function fontSize()
    {
        $sizes = $this->config->get('captcha.font_sizes');

        return $sizes ?
            collect($sizes)->random() : mt_rand($this->captchaHeight() - 30, $this->captchaHeight() - 20);
    }

    /**
     * Random font color.
     *
     * @return integer
     */
    protected function fontColor()
    {
        $colors = $this->config->get('captcha.font_colors');

        $fontColor = $colors ?
            collect($colors)->random() : $this->randomColor([0, 150], [0, 150], [0, 150]);

        return imagecolorallocate($this->image, ...$fontColor);
    }

    /**
     * Random character angle.
     *
     * @return float
     */
    protected function angle()
    {
        $angles = $this->config->get('captcha.angle', [0]);

        $angle = collect($angles)->random();

        return mt_rand((-1 * $angle), $angle);
    }

    /**
     * Get random rgb color.
     *
     * @param  array  $red
     * @param  array  $green
     * @param  array  $blue
     * @return array
     */
    protected function randomColor(array $red, array $green, array $blue) {
        return [mt_rand(...$red), mt_rand(...$green), mt_rand(...$blue)];
    }

    /**
     * Get captcha width.
     *
     * @return integer
     */
    public function captchaWidth()
    {
        return $this->config->get('captcha.width', 200);
    }

    /**
     * Get captcha height.
     *
     * @return integer
     */
    public function captchaHeight()
    {
        return $this->config->get('captcha.height', 50);
    }

    /**
     * Output captcha.
     *
     * @return \Illuminate\Http\Response
     */
    protected function output()
    {
        ob_start();
        imagepng($this->image);
        $buffer = ob_get_contents();
        ob_end_clean();

        return $this->response->make($buffer, 200, [
            'Content-Type' => 'image/png'
        ]);
    }

    /**
     * Convert captcha to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->html();
    }

    /**
     * Get captcha url.
     *
     * @return string
     */
    public function url()
    {
        return $this->url->to('captcha/') .
            '?r=' . (float)rand()/(float)getrandmax();
    }

    /**
     * Get captcha url without cache buster.
     *
     * @return string
     */
    public function cleanUrl()
    {
        return $this->url->to('captcha/');
    }

    /**
     * Get captcha html tag.
     *
     * @return string
     */
    public function html()
    {
        return sprintf('<img src="%s" alt="captcha">', $this->url());
    }

    /**
     * Get captcha input.
     *
     * @param  array  $classes
     * @return string
     */
    public function input(array $classes = [])
    {
        return sprintf(
            '<input type="text" class="%s" name="captcha">',
            implode(' ', $classes)
        );
    }

    /**
     * Generate text.
     *
     * @return string
     */
    protected function generate()
    {
        $characters = $this->config->get(
            'captcha.characters',
            'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789'
        );

        $characters = collect(str_split($characters));

        $word = $characters->random($this->config->get('captcha.length', 6))
            ->implode('');

        $this->session->put('captcha', [
            'case_sensitive' => $this->caseSensitive(),
            'hash'           => $this->hasher
                ->make($this->caseSensitive() ?
                $word : $this->str->lower($word)),
        ]);

        return $word;
    }
}
