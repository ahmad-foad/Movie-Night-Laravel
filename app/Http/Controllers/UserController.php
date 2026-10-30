<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * List users.
     */
    public function index()
    {
        return UserResource::collection(User::orderBy('id')->get());
    }

    /**
     * Show a user.
     *
     * Includes the user's watch history and how many reviews they wrote.
     */
    public function show(User $user)
    {
        $user->load('watches.movie')->loadCount('reviews');

        return new UserResource($user);
    }

    /**
     * Create a user.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        $user->refresh();

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }
}
