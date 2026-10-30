<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * List movies.
     *
     * Filter with `?genre=Animation` and `?clean=true` to get only family-clean movies.
     */
    public function index(Request $request)
    {
        $movies = Movie::query()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($request->filled('genre'), function ($query) use ($request) {
                // ILIKE is PostgreSQL's case-insensitive match, so "animation" finds "Animation".
                $query->where('genre', 'ilike', $request->input('genre'));
            })
            ->when($request->boolean('clean'), function ($query) {
                $query->familyClean();
            })
            ->orderBy('id')
            ->get();

        return MovieResource::collection($movies);
    }

    /**
     * Show a movie.
     *
     * Includes the average rating and all of the movie's reviews.
     */
    public function show(Movie $movie)
    {
        $movie->loadAvg('reviews', 'rating')
            ->loadCount('reviews')
            ->load(['reviews' => function ($query) {
                $query->with('user')->latest();
            }]);

        return new MovieResource($movie);
    }

    /**
     * Create a movie.
     */
    public function store(StoreMovieRequest $request)
    {
        $movie = Movie::create($request->validated());
        $movie->refresh();

        return (new MovieResource($movie))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update a movie.
     *
     * Send only the fields you want to change, including the clean-edit fields.
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $movie->update($request->validated());
        $movie->loadAvg('reviews', 'rating')->loadCount('reviews');

        return new MovieResource($movie);
    }

    /**
     * Delete a movie.
     *
     * Its watches and reviews are deleted too.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->noContent();
    }
}
