<?php

namespace App\Http\Controllers;

use App\Http\Requests\WatchMovieRequest;
use App\Http\Resources\WatchResource;
use App\Models\Movie;
use App\Models\Watch;

class WatchController extends Controller
{
    // POST /api/movies/{movie}/watch with {"user_id": 1}
    public function store(WatchMovieRequest $request, Movie $movie)
    {
        // If this user already watched this movie, return the existing record instead of a duplicate.
        $watch = Watch::firstOrCreate(
            ['user_id' => $request->validated('user_id'), 'movie_id' => $movie->id],
            ['watched_at' => now()],
        );
        $watch->load('movie');

        return (new WatchResource($watch))
            ->response()
            ->setStatusCode($watch->wasRecentlyCreated ? 201 : 200);
    }
}
