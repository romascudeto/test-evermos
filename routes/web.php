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

$router->post('user', [
    'uses' => 'UserController@create'
]);
$router->post('cart', [
    'uses' => 'CartController@create'
]);
$router->post('order', [
    'uses' => 'OrderController@create'
]);
$router->post('orderThreshold', [
    'uses' => 'OrderController@createThreshold'
]);
$router->get('product', [
    'uses' => 'ProductController@getAll'
]);
$router->get('product/generate', [
    'uses' => 'ProductController@generateProductRedis'
]);