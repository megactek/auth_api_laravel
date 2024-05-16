<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;


// login
Route::post('register', [ApiController::class, 'register'])->name('register');
Route::post('login', [ApiController::class, 'login'])->name('login');

Route::group([
    'middleware' => ['auth:sanctum'],
], function ($route) {
    //  profile
    Route::get('profile', [ApiController::class, 'profile'])->name('profile');
    // logout
    Route::get('logout', [ApiController::class, 'logout'])->name('logout');
});

