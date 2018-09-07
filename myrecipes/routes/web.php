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


$router->group(['prefix'=>'/myrecipes',], function () use ($router) {
    $router->post('/user', 'UsersController@createUser');
    $router->post('/login', 'UsersController@logIn');
});
$router->group(['prefix'=>'/myrecipes', 'middleware' => 'allUsers'], function () use ($router) {
    $router->get('/user/{id}/categories', 'UsersController@getUserCategories');
    $router->get('/user/{id}/category', 'CategoryController@getAllUserCategories');
    $router->get('/user/{id}/category/{category_id}', 'CategoryController@getSingleCategory');
    $router->get('/user/{id}/category/{category_id}/recipes', 'CategoryController@getAllCategoryRecipes');
    $router->get('/user/{id}/category/{category_id}/recipes/{recipe_id}', 'RecipesController@getSingleRecipe');
    $router->post('/user/{id}/category/{category_id}/recipes/{recipe_id}/comments', 'CommentsController@add');
    $router->get('/user/{id}/category/{category_id}/recipes/{recipe_id}/comments', 'CommentsController@view');
    $router->get('/user/{id}/category/{category_id}/recipes/{recipe_id}/comments/{comment_id}', 'CommentsController@viewSingleComment');

});

$router->group(['prefix'=>'/myrecipes', 'middleware' => 'admin.auth'], function () use ($router) {
    $router->get('/user', 'UsersController@getAllUsers');
    $router->get('/user/{id}', 'UsersController@getSingleUser');
    $router->put('/user/{id}', 'UsersController@update');
    $router->delete('/user/{id}', 'UsersController@deleteUser');
});

$router->group(['prefix'=>'/myrecipes/user/{id}', 'middleware' => 'auth'], function () use ($router) {
    $router->post('/category', 'CategoryController@addCategory');
    $router->get('/category/user/{user_id}', 'CategoryController@getUser');
    $router->put('/category/{category_id}', 'CategoryController@editCategory');
    $router->delete('/category/{category_id}', 'CategoryController@deleteCategory');
});

$router->group(['prefix'=>'/myrecipes/user/{id}/category/{category_id}/recipes', 'middleware' => 'auth'], function () use ($router) {
    $router->post('/', 'RecipesController@addRecipe');
    $router->put('/{recipe_id}', 'RecipesController@edit');
    $router->delete('/{recipe_id}', 'RecipesController@delete');
});

$router->group(['prefix'=>'/myrecipes/user/{id}/category/{category_id}/recipes/{recipe_id}/comments', 'middleware' => 'auth'], function () use ($router) {
    $router->put('/{comment_id}', 'CommentsController@edit');
    $router->delete('/{comment_id}', 'CommentsController@delete');
});
