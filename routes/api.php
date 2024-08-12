<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Storage;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->get('/profile', function (Request $request) { // to check the authentication
    try{
        return $request->user();
    } catch (Exception $e) {
        return response(['error' => $e->getMessage()], 500);
    }
});

Route::get('/files/{fileName}', function ($fileName) {
    // You can add additional checks here if necessary

    if (Storage::disk('public')->exists('ID_photos/' . $fileName)) {
        return response()->download(storage_path('app/public/ID_photos/' . $fileName));
    }

    return abort(404, 'File not found');
});

