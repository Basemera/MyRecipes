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

$app->get('/', function () use ($app) {
    return $app->welcome();
});

//$app->post('/user', UserController@createUser());

$app->group(['prefix'=>'/myrecipes', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
    $app->post('/user', 'UserController@createUser');
    $app->get('/user', 'UserController@getAllUsers');
    $app->get('/user/{id}', 'UserController@getSingleUser');
    $app->delete('/user/{id}', 'UserController@deleteUser');
});
