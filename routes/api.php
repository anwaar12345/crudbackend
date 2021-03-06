<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/signup','API\AuthController@CrudRegister');
Route::post('/login','API\AuthController@CrudLogin');
Route::post('/logout', 'API\AuthController@CrudLogout');

Route::middleware('ApiToken')->group(function () {

    Route::get('/users', 'API\UserController@index');
    Route::post('/create-user', 'API\UserController@CreateUser');
    Route::get('/edit/{id}', 'API\UserController@GetUserById');
    Route::put('update/{id}','API\UserController@UpdateUser');
    Route::delete('delete/{id}','API\UserController@UserDelete');
});

