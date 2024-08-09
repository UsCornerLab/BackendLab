<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

// Sanctum::routes();

Route::prefix('api')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    
    Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
        try{
            return $request->user();
        } catch (Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    });

    Route::get('/csrf-token', function () {
        return response()->json(['csrfToken' => csrf_token()]);
    });
});

