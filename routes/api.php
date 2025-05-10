<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::get('/test', function () {
    return '<h1> Hello, World! </h1>';
});

// Ruta de depuración
Route::get('/debug', function () {
    return response()->json(['message' => 'Debug route is working']);
});

Route::middleware('jwt.auth')->group(function () {
    //users endpoints
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);

    // get my profile
    Route::get('/profile', [UserController::class, 'profile']);

    // Logout endpoint
    Route::post('/logout', [AuthController::class, 'logout']);

});