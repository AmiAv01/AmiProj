<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RecoveryController extends Controller
{
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $status = Password::sendResetLink($request->only('email'));
        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => [trans($status)]]);
        }

        return response()->json(['message' => trans($status)]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password): void {
                $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
                event(new PasswordReset($user));
            },
        );
        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => [trans($status)]]);
        }

        return response()->json(['message' => trans($status)]);
    }

    public function confirmPassword(Request $request): JsonResponse
    {
        $request->validate(['password' => ['required']]);
        if (! Auth::guard('web')->validate(['email' => $request->user()->email, 'password' => $request->input('password')])) {
            throw ValidationException::withMessages(['password' => [__('auth.password')]]);
        }
        $request->session()->put('auth.password_confirmed_at', time());

        return response()->json(['message' => __('Password confirmed.')]);
    }

    public function verificationNotification(Request $request): JsonResponse
    {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();
        }

        return response()->json(['message' => __('Verification link sent.')]);
    }
}
