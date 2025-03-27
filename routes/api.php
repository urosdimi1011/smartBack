<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
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

    Route::post('register', [UserController::class, 'store']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refreshToken']);
    Route::get('categories', [CategoryController::class, 'getAll']);
    Route::get('timerChange', [TimerController::class, 'processTimers']);
    Route::get('device/{id}', [DeviceController::class, 'getStatusOfDevice']);
    Route::get('device/status/{id}', [DeviceController::class, 'changeStatusOfDevice']);
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
            ->name('verification.verify');

    Route::post('/email/resend', [VerificationController::class, 'resend']);
    // /api/device/status/53?status=1
    Route::middleware(['auth:api','verified'])->group(function(){
        Route::patch('changePassword/{idUser}', [UserController::class, 'changePassword']);
        Route::post('changePassword', [UserController::class, 'confirmPassword']);
        Route::post('device', [DeviceController::class, 'store']);
        Route::get('device', [DeviceController::class, 'getAll']);
        Route::get('groups', [GroupController::class, 'getAll']);
        Route::post('groups/{id}', [GroupController::class, 'addDeviceInGroup']);
        Route::delete('groups/{id}', [GroupController::class, 'removeGroup']);
        Route::post('groups', [GroupController::class, 'store']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'me']);
        Route::post('timer', [TimerController::class, 'setTimer']);
        Route::get('timer', [TimerController::class, 'getAll']);
        Route::patch('device/group/{id}', [DeviceController::class, 'changeStatusOfDeviceInGroup']);
        Route::patch('device/name/{id}', [DeviceController::class, 'changeNameOfDevice']);
        Route::delete('timer/{id}', [TimerController::class, 'deleteTimer']);
        Route::put('timer/{id}', [TimerController::class, 'changeTimer']);
        Route::delete('device/{id}', [DeviceController::class, 'removeDevice']);
    });
