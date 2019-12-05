<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;

$router->get(
    '/',
    function () use ($router) {
        return $router->app->version();
    }
);

$router->get(
    'gifs',
    [
        'uses' => 'GifController@index'
    ]
);

$router->get(
    'g/{key}',
    [
        'as' => 'minified',
        'uses' => 'GifController@get'
    ]
);

$router->group(
    ['prefix' => 'gif'],
    function () use ($router) {
        $router->group(
            ['middleware' => 'auth'],
            function () use ($router) {
                $router->get(
                    'search',
                    [
                        'uses' => 'GifController@search'
                    ]
                );

                $router->get(
                    'share/{gifId}',
                    [
                        'uses' => 'GifController@share'
                    ]
                );
            }
        );
    }
);

$router->group(
    ['prefix' => 'user', 'middleware' => 'auth'],
    function () use ($router) {
        $router->get(
            'history',
            [
                'uses' => 'HistoryController@index'
            ]
        );
        $router->get(
            'favorite/{gifId}',
            [
                'uses' => 'FavoriteController@store'
            ]
        );
    }
);

$router->group(
    ['prefix' => 'auth'],
    function () use ($router) {
        $router->post(
            'login',
            [
                'uses' => 'AuthController@authenticate'
            ]
        );

        $router->get(
            'logout',
            [
                'middleware' => 'auth',
                'uses' => 'AuthController@logout'
            ]
        );

        $router->post(
            'register',
            [
                'uses' => 'RegisterController@register'
            ]
        );
    }
);
