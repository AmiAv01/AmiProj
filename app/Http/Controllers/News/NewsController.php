<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function __construct(protected NewsService $newsService)
    {

    }

    public function index()
    {
        return Inertia::render('News/NewsList', ['posts' => $this->newsService->getAll()]);
    }

    public function store()
    {

    }

    public function show()
    {

    }

    public function update()
    {

    }
}
