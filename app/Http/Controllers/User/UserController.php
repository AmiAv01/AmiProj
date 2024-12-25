<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DetailService;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(): Response
    {
        return Inertia::render('User/Index', [
            'details' => $this->detailService->getAll(4),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
