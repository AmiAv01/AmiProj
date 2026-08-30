<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisteredUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function user(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();
        if (! $user || ! $user->approved || ! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => [__('auth.failed')]]);
        }

        $request->session()->regenerate();

        return response()->json(['data' => $request->user()]);
    }

    public function adminLogin(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt([
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'isAdmin' => true,
            'approved' => true,
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => [__('auth.failed')]]);
        }
        $request->session()->regenerate();

        return response()->json(['data' => $request->user()]);
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

        return response()->json([
            'data' => $user,
            'message' => __('Registration submitted for approval.'),
        ], 201);
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
