<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // GET /api/movies?genre=Animation&clean=true
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

    // Shows one movie with its average rating and all of its reviews.
    public function show(Movie $movie)
    {
        $movie->loadAvg('reviews', 'rating')
            ->loadCount('reviews')
            ->load(['reviews' => function ($query) {
                $query->with('user')->latest();
            }]);

        return new MovieResource($movie);
    }

    public function store(StoreMovieRequest $request)
    {
        $movie = Movie::create($request->validated());
        $movie->refresh();

        return (new MovieResource($movie))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $movie->update($request->validated());
        $movie->loadAvg('reviews', 'rating')->loadCount('reviews');

        return new MovieResource($movie);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->noContent();
    }
}
