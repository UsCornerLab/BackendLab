<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BookSupportRequestController;
use App\Http\Controllers\SupportedBooksController;
use App\Http\Controllers\FileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


Route::middleware(['auth'])->group(function () {

    
    Route::get('/books', [BookController::class,'getAll']);
    Route::get('/books/{id}', [BookController::class,'getOne']);
    Route::post('/books', [BookController::class,'create']);
    Route::delete('/books/{id}', [BookController::class,'delete']);
    Route::put('/books/{id}', [BookController::class,'update']);

    Route::get('/files/{fileName}', [FileController::class, 'serveFile']);
});


Route::get('/book/search', [BookController::class, 'search']);

Route::get('/files/{fileName}', function ($fileName) {
    if (Storage::disk('public')->exists('ID_photos/' . $fileName)) {
        return response()->download(storage_path('app/public/ID_photos/' . $fileName));
    }
    return abort(404, 'File not found');
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
