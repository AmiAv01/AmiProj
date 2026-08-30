<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisteredUserRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function user(Request $request): JsonResponse
    {
        return AuthUserResource::make($request->user())->response();
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate(['approved' => true]);
        $request->session()->regenerate();

        return AuthUserResource::make($request->user())->response();
    }

    public function adminLogin(LoginRequest $request): JsonResponse
    {
        $request->authenticate([
            'isAdmin' => true,
            'approved' => true,
        ]);
        $request->session()->regenerate();

        return AuthUserResource::make($request->user())->response();
    }

    public function register(RegisteredUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone_number' => $request->validated('phoneNumber'),
            'password' => Hash::make($request->validated('password')),
        ]);
        event(new Registered($user));

        return AuthUserResource::make($user)
            ->additional(['message' => __('Registration submitted for approval.')])
            ->response()
            ->setStatusCode(201);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::forgetGuards();

        return response()->json(['message' => __('Logged out.')]);
    }
}
