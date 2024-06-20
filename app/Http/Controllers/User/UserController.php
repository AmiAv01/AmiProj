<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Detail\DetailService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index()
    {
        $details = $this->detailService->getAll();

        return Inertia::render('User/Index', [
            'details' => $details,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
