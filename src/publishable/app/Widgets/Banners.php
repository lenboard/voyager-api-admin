<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\HtmlBlock;

class Banners extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'uri' => '',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $uris = $this->config[ 'uri' ];

        if (!is_array($uris)) {
            $uris = explode(',', $uri);
        }

        $htmlBlocks = HtmlBlock::whereIn('uri', $uris)
            ->where('active', 1)
            ->orderBy('order', 'asc')
            ->get();

        return view('widgets.banners', [
            'config' => $this->config,
            'htmlBlocks' => $htmlBlocks,
        ]);
    }
}
