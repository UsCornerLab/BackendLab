<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BookRequestController;
use App\Http\Controllers\FileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('access');

Route::middleware(['access'])->group(function () {

    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/updateProfile/{id}', [AuthController::class, 'updateProfile']);

    
    Route::get('/books', [BookController::class,'getAll']);
    Route::get('/books/{id}', [BookController::class,'getOne']);
    Route::middleware(['role:librarian,admin'])->group(function () {
        Route::post('/books', [BookController::class,'create']);
        Route::delete('/books/{id}', [BookController::class,'delete']);
        Route::put('/books/{id}', [BookController::class,'update']);
    });
    Route::get('/books/search', [BookController::class, 'search']);

    Route::post('/book-requests', [BookRequestController::class, 'store']); 
    Route::get('/book-requests/{id}', [BookRequestController::class, 'show']);
    Route::put('/book-requests/{id}', [BookRequestController::class, 'update']);
    Route::delete('/book-requests/{id}', [BookRequestController::class, 'destroy']);

    Route::middleware(['role:librarian,admin'])->get('/files/{fileName}', [FileController::class, 'serveFile']);
});


