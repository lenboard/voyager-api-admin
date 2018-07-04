<?php

namespace App\Providers;

use GuzzleHttp\Middleware;
use App\Support\Facades\Api;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\CurlHandler;
use App\Support\Classes\Api\Captcha;
use Illuminate\Encryption\Encrypter;
use App\Support\Classes\Api\Response;
use Illuminate\Support\Facades\Blade;
use App\Support\Classes\Api\Auth\Auth;
use App\Support\Classes\Api\Auth\Guard;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Validator;
use App\Support\Classes\Api\Api as ApiClient;
use Illuminate\Support\Facades\Auth as AuthProvider;
use App\Support\Classes\Api\Auth\Providers\UserProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Authentication routes
        Api::auth()->routes();

        // money format
        Blade::directive('money', function ($number) {
            return "<?php echo number_format($number, 2, '.', ' ') ?>";
        });
        // crypto format
        Blade::directive('crypto', function ($number) {
            return "<?php echo number_format($number, 8, '.', ' ') ?>";
        });

        // time format
        Blade::directive('time', function ($date) {
            return "<?php echo date('H:i', strtotime($date)) ?>";
        });
        // Captcha routes
        Api::captcha()->routes();

        // Scope check
        Blade::directive('scope', function ($scope) {
           return "<?php if (app('api.auth')->hasScope($scope)): ?>";
        });

        // End scope check
        Blade::directive('endscope', function () {
           return "<?php endif; ?>";
        });

        // Group check
        Blade::directive('group', function ($group) {
           return "<?php if (app('api.auth')->hasGroup($group)): ?>";
        });

        // End group check
        Blade::directive('endgroup', function () {
           return "<?php endif; ?>";
        });

        // replace spaces with undersore
        Blade::directive('url_clean_string', function ($string) {
           return "<?php echo str_replace(' ', '_', strtolower($string)) ?>";
        });

        // Validator extensions
        Validator::extend('captcha', function ($attribute, $value, $parameters) {
            return api()->captcha()->check($value);
        });

        Validator::replacer('captcha', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.captcha', 'Invalid captcha code.', $message);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAliases();

        $this->registerGuzzle();

        $this->registerApi();

        $this->registerAuth();

        $this->registerUserProvider();

        $this->registerGuard();

        $this->registerEncrypter();

        $this->registerCaptcha();

        $this->aliasMiddleware('scope', \App\Support\Middleware\Scope::class);
        $this->aliasMiddleware('group', \App\Support\Middleware\Group::class);
        $this->aliasMiddleware('api.debugbar', \App\Support\Middleware\ApiDebugbar::class);
    }

    /**
     * Register class aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $aliases = [
            'api.guzzle'    => 'GuzzleHttp\Client',
            'api.client'    => 'App\Support\Classes\Api\Api',
            'api.auth'      => 'App\Support\Classes\Api\Auth\Auth',
            'api.encrypter' => 'Illuminate\Contracts\Encryption\Encrypter',
            'api.captcha'   => 'App\Support\Classes\Api\Captcha',
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register a short-hand name for a middleware.
     *
     * @param  string  $name
     * @param  string  $class
     * @return void
     */
    protected function aliasMiddleware($name, $class)
    {
        $router = $this->app['router'];

        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }

        return $router->middleware($name, $class);
    }

    /**
     * Register guzzle client container.
     *
     * @return void
     */
    protected function registerGuzzle()
    {
        $this->app->singleton('api.guzzle', function ($app) {
            $handlerStack = new HandlerStack();
            $handlerStack->setHandler(new CurlHandler());

            // map response interface
            $handlerStack->push(
                Middleware::mapResponse(
                    function (ResponseInterface $response) use ($app) {
                        return new Response(
                            $response,
                            $app['Illuminate\Http\Request']
                        );
                    }
                )
            );

            return new Guzzle([
                'handler'     => $handlerStack,
                'base_uri'    => $app['config']['api.uri'],
                'verify'      => !$app['config']->get('api.allow_selfsigned', false),
                'http_errors' => false,
            ]);
        });
    }

    /**
     * Register API client container.
     *
     * @return void
     */
    protected function registerApi()
    {
        $this->app->singleton('api.client', function ($app) {
            return new ApiClient(
                $app['GuzzleHttp\Client'],
                $app['Illuminate\Http\Request'],
                $app['api.encrypter'],
                $app['api.captcha'],
                $app['api.auth'],
                $app['Illuminate\Contracts\Config\Repository'],
                $app['Illuminate\Support\Str']
            );
        });
    }

    /**
     * Register API auth container.
     *
     * @return void
     */
    protected function registerAuth()
    {
        $this->app->singleton('api.auth', function ($app) {
            return new Auth();
        });
    }

    /**
     * Register API user provider.
     *
     * @return void
     */
    protected function registerUserProvider()
    {
        AuthProvider::provider('remote', function ($app, array $config) {
            return new UserProvider(
                $app['hash'],
                $app['api.client'],
                $config['model']
            );
        });
    }

    /**
     * Register API auth guard.
     *
     * @return void
     */
    protected function registerGuard()
    {
        AuthProvider::extend('remote', function ($app, $name, array $config) {
            $guard = new Guard(
                $name,
                AuthProvider::createUserProvider($config['provider']),
                $app['session.store'],
                $app['api.client']
            );

            // When using the remember me functionality of the authentication services we
            // will need to be set the encryption instance of the guard, which allows
            // secure, encrypted cookie values to get generated for those cookies.
            $guard->setCookieJar($app['cookie']);

            $guard->setDispatcher($app['events']);

            $guard->setRequest($app->refresh('request', $guard, 'setRequest'));

            return $guard;
        });
    }

    /**
     * Register encrypter container.
     *
     * @return void
     */
    protected function registerEncrypter()
    {
        $this->app->singleton('api.encrypter', function ($app) {
            $config = $app->make('config')->get('api');

            if (starts_with($key = $config['key'], 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            return new Encrypter($key, $config['cipher']);
        });
    }

    /**
     * Register encrypter container.
     *
     * @return void
     */
    protected function registerCaptcha()
    {
        $this->app->bind('api.captcha', function ($app) {
            return new Captcha(
                $app['Illuminate\Contracts\Config\Repository'],
                $app['Illuminate\Contracts\Session\Session'],
                $app['Illuminate\Contracts\Hashing\Hasher'],
                $app['Illuminate\Contracts\Routing\ResponseFactory'],
                $app['Illuminate\Contracts\Logging\Log'],
                $app['Illuminate\Contracts\Routing\UrlGenerator'],
                $app['Illuminate\Support\Str']
            );
        });
    }
}
