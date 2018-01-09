<?php

namespace Submtd\SocialLogin\Providers;

use Illuminate\Support\ServiceProvider;

class SocialLoginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        require __DIR__ . '/../Routes.php';
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__ . '/../Config/social-login.php', 'social-login');
    }
}
