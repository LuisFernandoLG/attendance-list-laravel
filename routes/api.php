<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\RegisterUserController;
use App\Http\Controllers\api\VerifyEmailController;
use App\Http\Controllers\attendanceController;
use App\Http\Controllers\ControlledListRecordController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberController;
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

Route::get('/attendance/{eventId}/{shortId}', [ControlledListRecordController::class, 'store']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::get('/resend-email', [VerifyEmailController::class, 'show']);
    Route::post('/verify-email', [VerifyEmailController::class, 'store']);
    Route::post('/login', [AuthController::class, 'store']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'destroy']);


    Route::middleware(['verified'])->group(function () {
        Route::get('/events', [EventController::class, 'index']);
        Route::get('/events/{id}', [EventController::class, 'show']);
        Route::get('/events/{id}/members', [EventController::class, 'showWithMembers']);
        Route::get('/events/{id}/attendance', [EventController::class, 'showWithAttendance']);
        Route::post('/events', [EventController::class, 'store']);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);
        Route::post('/members', [MemberController::class, 'store']);
    });
    
});
