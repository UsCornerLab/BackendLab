<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BookSupportRequestController;
use App\Http\Controllers\SupportedBooksController;
use App\Http\Controllers\FileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('access');

Route::middleware(['access'])->group(function () {

    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/updateProfile/{id}', [AuthController::class, 'updateProfile']);

    Route::middleware(['role:librarian,admin'])->group(function () {
        Route::get('/users', [AuthController::class, 'getUsers']);
        Route::put('/verifyUser/{id}', [AuthController::class, 'verifyUser']);
    });

    Route::get('/books', [BookController::class, 'getAll']);
    Route::get('/books/{id}', [BookController::class, 'getOne']);

    Route::middleware(['role:librarian,admin'])->group(function () {
        Route::post('/books', [BookController::class, 'create']);
        Route::delete('/books/{id}', [BookController::class, 'delete']);
        Route::put('/books/{id}', [BookController::class, 'update']);
        Route::put('/books/activate/{id}', [BookController::class, 'activate']);
        Route::put('/books/deactivate/{id}', [BookController::class, 'deactivate']);
        Route::get('/files/{fileName}', [FileController::class, 'serveFile']);
    });

    Route::get('/books/search', [BookController::class, 'search']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('/book-support-requests', [BookSupportRequestController::class, 'store']);
    Route::put('/book-support-requests/{id}/review', [BookSupportRequestController::class, 'review']);
    Route::post('/supported-books', [SupportedBooksController::class, 'store']);
    Route::get('/supported-books', [SupportedBooksController::class, 'index']);
    Route::get('/supported-books/{id}', [SupportedBooksController::class, 'show']);
    Route::put('/supported-books/{id}', [SupportedBooksController::class, 'update']);
    Route::delete('/supported-books/{id}', [SupportedBooksController::class, 'destroy']);
});
