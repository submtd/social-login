<?php

namespace Submtd\SocialLogin\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * The SocialLoginServiceProvider class is the main
 * entry point for the package.
 */
class SocialLoginServiceProvider extends ServiceProvider
{
    /**
     * The service provider boot method
     *
     * @return void
     */
    public function boot()
    {
        // require our routes file
        require __DIR__ . '/../Routes.php';
        // migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->publishes([__DIR__ . '/../Database/Migrations' => database_path('migrations')], 'migrations');
        // config
        $this->mergeConfigFrom(__DIR__ . '/../Config/social-login.php', 'social-login');
        $this->publishes([__DIR__ . '/../Config' => config_path()], 'config');
    }
}
