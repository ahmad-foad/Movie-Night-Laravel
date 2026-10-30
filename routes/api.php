<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return response()->json([
        'message' => 'Hello World',
    ]);
});

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show'])
    ->whereNumber('movie');
Route::post('/movies', [MovieController::class, 'store']);
Route::patch('/movies/{movie}', [MovieController::class, 'update'])
    ->whereNumber('movie');
Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])
    ->whereNumber('movie');
