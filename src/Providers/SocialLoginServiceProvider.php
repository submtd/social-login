<?php

namespace Submtd\SocialLogin\Providers;

use Illuminate\Support\ServiceProvider;

class SocialLoginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // require our routes file
        require __DIR__ . '/../Routes.php';
        // views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'social-login');
        $this->publishes([__DIR__ . '/../Views' => resource_path('views/vendor/social-login')], 'views');
        // migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->publishes([__DIR__ . '/../Database/Migrations' => database_path('migrations')], 'migrations');
        // config
        $this->mergeConfigFrom(__DIR__ . '/../Config/social-login.php', 'social-login');
        $this->publishes([__DIR__ . '/../Config' => config_path()], 'config');
    }
}
