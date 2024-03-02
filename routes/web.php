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

use App\Http\Controllers\InfoController;

Route::get('/info/server', [InfoController::class, 'phpInfo']);
Route::get('/info/client', [InfoController::class, 'clientInfo']);
Route::get('/info/database', [InfoController::class, 'databaseInfo']);



