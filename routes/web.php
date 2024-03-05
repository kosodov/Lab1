<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InfoController;

Route::get('/info/server', [InfoController::class, 'phpInfo']);
Route::get('/info/client', [InfoController::class, 'clientInfo']);
Route::get('/info/database', [InfoController::class, 'databaseInfo']);
