<?php

namespace App\Providers;

use App\Services\Token\TokenException;
use App\Services\Token\TokenInterface;
use App\User;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $tokenService = app(TokenInterface::class);

        $this->app['auth']->viaRequest(
            'api',
            function ($request) use ($tokenService) {
                try {
                    $userId = $tokenService->Validate($request->bearerToken());
                    return User::find($userId);
                } catch (TokenException $exception) {
                    return null;
                }
            }
        );
    }
}
