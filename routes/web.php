<?php

use App\User;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    //return $router->app->version();

    //return $validation = JWTAuth::attempt(['email' => 'josejavierjesus2@gmail.com', 'password' => 'secret']);
    //return $user = JWTAuth::parseToken()->authenticate(); //get the authenticate user
    //return $user = JWTAuth::parseToken()->refresh(); //refresh a token
    //return $user = JWTAuth::parseToken()->getPayload(); //return the decode payload
    //return $user = JWTAuth::parseToken()->getClaim('jti'); //return the especified claims
    //$token = JWTAuth::fromUser($user); //authenticate post register

});

$router->group(['middleware' => ['auth']], function () use ($router){
    $router->get('/users', ['uses' => 'UserController@index']);
});

$router->post('/users', ['uses' => 'UserController@store']);
$router->post('/login', ['uses' => 'AuthController@login']);


$router->post('/upload', ['uses' => 'ImagesController@make']);
