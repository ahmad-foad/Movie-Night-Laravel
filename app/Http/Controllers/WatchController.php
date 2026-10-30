<?php

namespace App\Http\Controllers;

use App\Http\Requests\WatchMovieRequest;
use App\Http\Resources\WatchResource;
use App\Models\Movie;
use App\Models\Watch;

class WatchController extends Controller
{
    /**
     * Mark a movie as watched.
     *
     * Returns 201 the first time and 200 if the user already watched the movie.
     */
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
