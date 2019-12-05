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
        'uses' => 'GifSearchController@index'
    ]
);

$router->get(
    'gif/s/',
    [
        'middleware' => 'auth',
        'uses' => 'GifSearchController@search'
    ]
);

$router->get(
    'history',
    [
        'middleware' => 'auth',
        'uses' => 'HistoryController@index'
    ]
);

$router->post(
    'auth/login',
    [
        'uses' => 'AuthController@authenticate'
    ]
);

$router->get(
    'auth/logout',
    [
        'middleware' => 'auth',
        'uses' => 'AuthController@logout'
    ]
);

$router->post(
    'auth/register',
    [
        'uses' => 'RegisterController@register'
    ]
);
