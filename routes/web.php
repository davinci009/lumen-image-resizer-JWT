<?php

use App\User;
use Illuminate\Support\Str;

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
    //factory(User::class)->create();

    return $router->app->version();

});

$router->group(['middleware' => ['auth']], function () use ($router){
    $router->get('/users', ['uses' => 'UserController@index']);
});

$router->post('/users', ['uses' => 'UserController@store']);

$router->post('/login', ['uses' => 'AuthController@login']);

$router->post('/upload', ['uses' => 'ImagesController@make']);
