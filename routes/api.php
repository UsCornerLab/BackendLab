<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->get('/profile', function (Request $request) {
    try{
        return $request->user();
    } catch (Exception $e) {
        return response(['error' => $e->getMessage()], 500);
    }
});

// Route::get('/csrf', function () {
//     return response()->json(['csrfToken' => csrf_token()]);
// });


