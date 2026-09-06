<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\AuthUserResource;
use App\Services\UserService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function __construct(private readonly UserService $users) {}

    public function profile(Request $request): JsonResponse
    {
        return response()->json(['data' => [
            'user' => AuthUserResource::make($request->user())->resolve($request),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
        ]]);
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        return response()->json([
            'data' => AuthUserResource::make($user->fresh())->resolve($request),
            'message' => __('Profile updated.'),
        ]);
    }

    public function password(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return response()->json(['message' => __('Password updated.')]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);
        $user = $request->user();
        $this->users->destroy($user->id);
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::forgetGuards();

        return response()->json(null, 204);
    }
}
