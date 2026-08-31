<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Ensure that access has not been revoked since the session was created.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->approved === true) {
            return $next($request);
        }

        if ($request->hasSession() && Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Auth::forgetGuards();
        }

        if ($request->expectsJson()) {
            return new JsonResponse(['message' => __('Your account is not approved.')], 403);
        }

        abort(403, __('Your account is not approved.'));
    }
}
