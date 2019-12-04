<?php

namespace App\Providers;

use App\Services\Token\JWTService;
use App\Services\Token\TokenInterface;
use Illuminate\Support\ServiceProvider;

class TokenServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            TokenInterface::class,
            function ($app) {
                return new JWTService();
            }
        );
    }
}
