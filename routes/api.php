<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatchController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return response()->json([
        'message' => 'Hello World',
    ]);
});

// Users
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show'])
    ->whereNumber('user');
Route::post('/users', [UserController::class, 'store']);

// Movies
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show'])
    ->whereNumber('movie');
Route::post('/movies', [MovieController::class, 'store']);
Route::patch('/movies/{movie}', [MovieController::class, 'update'])
    ->whereNumber('movie');
Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])
    ->whereNumber('movie');

// Watching
Route::post('/movies/{movie}/watch', [WatchController::class, 'store'])
    ->whereNumber('movie');

// Reviews
Route::get('/movies/{movie}/reviews', [ReviewController::class, 'index'])
    ->whereNumber('movie');
Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])
    ->whereNumber('movie');
Route::patch('/reviews/{review}', [ReviewController::class, 'update'])
    ->whereNumber('review');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
    ->whereNumber('review');
