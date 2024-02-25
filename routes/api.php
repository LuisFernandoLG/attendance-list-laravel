<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\RegisterUserController;
use App\Http\Controllers\api\VerifyEmailController;
use App\Http\Controllers\ControlledListRecordController;
use App\Http\Controllers\EventAttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventMemberController;
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

Route::get('/attendance/{event}/{shortId}', [ControlledListRecordController::class, 'store'])->name('attendance.store');

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
        Route::post('/events', [EventController::class, 'store']);
        Route::get('/events/{id}', [EventController::class, 'show']);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);
        Route::get('/events/{id}/attendance', [EventAttendanceController::class, 'show']);
        
        // TODO: return url to register attendance
        Route::post('/events/{id}/members', [EventMemberController::class, 'store']);
        Route::get('/events/{id}/members', [EventMemberController::class, 'index']);
        Route::delete('/events/{event}/members/{member}', [EventMemberController::class, 'destroy']);
        
    });
    
});
