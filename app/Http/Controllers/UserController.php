<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::orderBy('id')->get());
    }

    // Shows the user's profile with their watch history.
    public function show(User $user)
    {
        $user->load('watches.movie')->loadCount('reviews');

        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        $user->refresh();

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }
}
