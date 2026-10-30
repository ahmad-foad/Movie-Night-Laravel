<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Movie;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Movie $movie)
    {
        $reviews = $movie->reviews()
            ->with('user')
            ->latest()
            ->get();

        return ReviewResource::collection($reviews);
    }

    public function store(StoreReviewRequest $request, Movie $movie)
    {
        $review = $movie->reviews()->create($request->validated());
        $review->load('user');

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        $review->load('user');

        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->noContent();
    }
}
