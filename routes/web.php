<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('orders/v1', ['as' => 'orders/v1', 'uses' => 'v1\ApiController@create']);
$router->get('orders/v1', ['as' => 'orders/v1', 'uses' => 'v1\ApiController@index']);
$router->patch('orders/v1', ['as' => 'orders/v1', 'uses' => 'v1\ApiController@update']);
$router->get('orders/v1/delayed', ['as' => 'orders/v1/delayed', 'uses' => 'v1\ApiController@delayed']);