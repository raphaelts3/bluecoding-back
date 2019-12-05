<?php

namespace App\Providers;


use App\Services\Minify\Base62Service;
use App\Services\Minify\MinifyInterface;
use Illuminate\Support\ServiceProvider;

class MinifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            MinifyInterface::class,
            function ($app) {
                return new Base62Service();
            }
        );
    }
}
