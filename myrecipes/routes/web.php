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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix'=>'/myrecipes'], function () use ($router) {
    $router->post('/user', 'UsersController@createUser');
    $router->get('/user', 'UsersController@getAllUsers');
    $router->get('/user/{id}', 'UsersController@getSingleUser');
    $router->put('/user/{id}', 'UsersController@update');
    $router->delete('/user/{id}', 'UsersController@deleteUser');
    $router->post('/login', 'UsersController@logIn');
});

$router->group(['prefix'=>'/myrecipes', 'middleware' => 'admin.auth'], function () use ($router) {
    $router->get('/user', 'UsersController@getAllUsers');
    $router->get('/user/{id}', 'UsersController@getSingleUser');
    $router->put('/user/{id}', 'UsersController@update');
    $router->delete('/user/{id}', 'UsersController@deleteUser');
});

$router->group(['prefix'=>'/myrecipes/user/{id}', 'middleware' => 'auth'], function () use ($router) {
    $router->post('/category', 'AddCategoryController@addCategory');
//    $router->get('/user/{id}', 'UsersController@getSingleUser');
//    $router->put('/user/{id}', 'UsersController@update');
//    $router->delete('/user/{id}', 'UsersController@deleteUser');
});

