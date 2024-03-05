<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/tokens', [AuthController::class, 'getTokens']);
    Route::post('/revoke-tokens', [AuthController::class, 'revokeTokens']);
});

Route::get('/check-database', function () {
    try {
        $pdo = DB::connection()->getPdo();
        $databaseName = $pdo->query('SELECT DATABASE()')->fetchColumn();
        return 'Connected to database: ' . $databaseName;
    } catch (\Exception $e) {
        return 'Database connection error: ' . $e->getMessage();
    }
});


use App\Http\Controllers\InfoController;
Route::get('/info/server', [InfoController::class, 'phpInfo']);
Route::get('/info/client', [InfoController::class, 'clientInfo']);
Route::get('/info/database', [InfoController::class, 'databaseInfo']);

