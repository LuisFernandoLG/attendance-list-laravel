<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\RegisterUserController;
use App\Http\Controllers\api\VerifyEmailController;
use App\Models\User;
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

// prefix auth 

// list laravel conventions for actions
// index - GET /users
// create - GET /users/create
// store - POST /users
// show - GET /users/{id}
// edit - GET /users/{id}/edit
// update - PUT/PATCH /users/{id}
// destroy - DELETE /users/{id}


Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::post('/verify-email', [VerifyEmailController::class, 'store']);
    Route::post('/login', [AuthController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
