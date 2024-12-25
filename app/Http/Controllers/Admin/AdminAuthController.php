<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdminAuthController extends Controller
{
    public function showLoginForm(): Response
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->validated('email'), 'password' => $request->validated('password'), 'isAdmin' => true])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->with('error', 'Неверные данные');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
