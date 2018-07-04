<?php

namespace Lenboard\VoyagerApiAdmin;

class VoyagerApiAdminServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
        }
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath = __DIR__ . '/publishable';

        $publishable = [
            'voyager_assets' => [
                "{$publishablePath}/assets/" => public_path(config('voyager.assets_path')),
            ],
            'seeds' => [
                "{$publishablePath}/database/seeds/" => database_path('seeds'),
            ],
            'migrations' => [
                "{$publishablePath}/database/migrations/" => database_path('migrations'),
            ],
            'config' => [
                "{$publishablePath}/config/voyager.php" => config_path('voyager.php'),
            ],
            'storage' => [
                "{$publishablePath}/storage/" => base_path('storage'),
            ],
            'public' => [
                "{$publishablePath}/public/" => base_path('public'),
            ],
            'app' => [
                "{$publishablePath}/app/" => base_path('app'),
            ],
            'resources' => [
                "{$publishablePath}/resources/" => base_path('resources'),
            ],
            'routes' => [
                "{$publishablePath}/routes/" => base_path('routes'),
            ],

        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }
}
