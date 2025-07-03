<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use App\Services\Cart\CartService;

use App\Services\NewsService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        protected DetailService $detailService,
        protected NewsService $newsService,
        protected CartService $cartService
    ) {}

    public function index(): Response
    {
        return Inertia::render('User/Index', [
            'details' => $this->detailService->getAll(4),
            'posts' => $this->newsService->getAll(3),
            'countOfCartItems' => $this->cartService->getCountOfCartItems(auth()->id()),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
