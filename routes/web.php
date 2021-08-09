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

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('/{userId}', 'UserController@get');
    $router->post('/', 'UserController@create');
    $router->put('/{userId}', 'UserController@update');
    $router->get('/balance/{userId}', 'UserController@getBalance');
});

$router->group(['prefix' => 'transaction'], function () use ($router) {
    $router->post('/', 'TransactionController@make');
    $router->post('/funds', 'TransactionController@addFunds');
    $router->get('/history/{userId}', 'TransactionController@getHistoryByUser');
});
