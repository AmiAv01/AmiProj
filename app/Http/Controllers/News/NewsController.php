<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function __construct(protected NewsService $newsService)
    {}

    public function index()
    {
        return Inertia::render('News/News', ['posts' => $this->newsService->getAll(12)]);
    }

}
