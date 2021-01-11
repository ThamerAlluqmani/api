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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register' , 'App\Http\Controllers\API\AuthController@register');
Route::post('login' , 'App\Http\Controllers\API\AuthController@login');


Route::middleware('auth:api')->prefix('user')->group(function (){

    Route::post('update/password' ,'App\Http\Controllers\API\UserController@updatePassword' );
    Route::post('update/account' ,'App\Http\Controllers\API\UserController@updateAccount' );
});

Route::resource('categories' ,'App\Http\Controllers\API\CategoryController');
Route::put('categories/{categoryId}/restore' ,'App\Http\Controllers\API\CategoryController@restore' );
Route::delete('categories/{categoryId}/force-delete' ,'App\Http\Controllers\API\CategoryController@forceDelete' );


Route::resource('tasks' ,'App\Http\Controllers\API\TaskController');
Route::put('tasks/{taskId}/restore' ,'App\Http\Controllers\API\TaskController@restore' );
Route::delete('tasks/{taskId}/force-delete' ,'App\Http\Controllers\API\TaskController@forceDelete' );
