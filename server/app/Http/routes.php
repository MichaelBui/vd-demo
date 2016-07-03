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
/** @var \Laravel\Lumen\Application $app */
$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/object', 'ObjectsController@index');
$app->post('/object', 'ObjectsController@save');
$app->get('/object/{key}', 'ObjectsController@show');
