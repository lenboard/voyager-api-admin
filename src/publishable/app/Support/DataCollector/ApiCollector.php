<?php

namespace App\Support\DataCollector;

use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as Guzzle;
use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;

class ApiCollector extends DataCollector implements DataCollectorInterface, Renderable
{
    /**
     * History container.
     *
     * @var array
     */
    protected $container = [];

    /**
     * Construct data collector.
     *
     * @return void
     */
    public function __construct()
    {
        $history = Middleware::history($this->container);

        $config = app(Guzzle::class)->getConfig();

        $stack = isset($config['handler']) ?
            $config['handler'] : HandlerStack::create();
        $stack->push($history);

        // change handler stack to debug with history
        $config['handler'] = $stack;

        // change client
        app()->singleton(Guzzle::class, function ($app) use ($config) {
            return new Guzzle($config);
        });
    }

    /**
     * Collect data.
     *
     * @return array
     */
    public function collect()
    {
        $requests = [];

        foreach ($this->container as $index => $transaction) {
            $request = $transaction['request'];
            $response = $transaction['response'];
            $endpoint = $request->getUri()->getPath();

            $data = [
                'code'     => $response->getStatusCode(),
                'method'   => $request->getMethod(),
                'uri'      => $request->getUri()->__toString(),
                'request'  => [
                    'body'    => $request->getBody()->__toString() ?: 'empty',
                    'headers' => collect($request->getHeaders())
                        ->map(function ($item) {
                            return implode(', ', $item);
                        })->toArray(),
                ],
                'response' => [
                    'body'    => str_limit($response->getBody()->__toString(), 255) ?: 'empty',
                    'headers' => collect($response->getHeaders())
                        ->map(function ($item) {
                            return implode(', ', $item);
                        })->toArray(),
                ],
            ];

            $requests["$index $endpoint"] = $this->formatVar($data);
        }

        return [
            'nb_requests' => count($requests),
            'requests'    => $requests,
        ];
    }

    /**
     * Get collector name.
     *
     * @return string
     */
    public function getName()
    {
        return 'api';
    }

    /**
     * Get debugbar widget config.
     *
     * @return array
     */
    public function getWidgets()
    {
        return [
            'api' => [
                'icon'    => 'server',
                'widget'  => 'PhpDebugBar.Widgets.VariableListWidget',
                'map'     => 'api.requests',
                'default' => "{}"
            ],
            'api:badge' => [
                'map'     => 'api.nb_requests',
                'default' => 0
            ]
        ];
    }
}
