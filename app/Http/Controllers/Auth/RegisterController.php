<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        UserRegistered::dispatch($user);

        return response()->json([
            'message' => 'User created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 201);
    }
}
