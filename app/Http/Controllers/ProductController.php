<?php

namespace App\Http\Controllers;

use App\Services\Interface\ProductViewServiceInterface;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductViewServiceInterface $viewService,
    ) {}

    public function index(string $id): Response
    {
        return Inertia::render(
            $this->viewService->resolveViewName(),
            $this->viewService->getViewDataForProduct($id)
        );
    }
}
