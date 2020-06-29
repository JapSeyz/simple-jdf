<?php

declare(strict_types=1);

namespace JapSeyz\SimpleJDF\Providers;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/simple-jdf.php',
            'simple-jdf'
        );
    }

    /**
     * Boot the service provider after registration.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/simple-jdf.php' => config_path('simple-jdf.php'),
        ]);
    }
}
