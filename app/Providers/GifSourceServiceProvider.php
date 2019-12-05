<?php

namespace App\Providers;


use App\Services\GifSource\GifSourceInterface;
use App\Services\GifSource\GiphyService;
use Illuminate\Support\ServiceProvider;

class GifSourceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            GifSourceInterface::class,
            function ($app) {
                return new GiphyService();
            }
        );
    }
}
