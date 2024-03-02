<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('http://localhost/info/server', 'InfoController@serverInfo');
Route::get('http://localhost/info/client', 'InfoController@clientInfo');
Route::get('http://localhost/info/database', 'InfoController@databaseInfo');

 
Route::get('/user', [UserController::class, 'index']);

 
Route::get(‘/hello', function () {
    return 'Hello World';
});

