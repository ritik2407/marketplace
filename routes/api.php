<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListingApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes with Sanctum Authentication
|--------------------------------------------------------------------------
*/

// Public API Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [ListingApiController::class, 'categories']);
Route::get('/locations', [ListingApiController::class, 'locations']);
Route::get('/listings', [ListingApiController::class, 'index']);
Route::get('/listings/{id}', [ListingApiController::class, 'show']);

// Sanctum Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'profile']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Manage Listings via API
    Route::post('/listings', [ListingApiController::class, 'store']);
});
