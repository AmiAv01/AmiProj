<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Inertia\Inertia;

class NewsAdminController extends Controller
{
    public function __construct(protected NewsService $newsService)
    {

    }

    public function index()
    {
        return Inertia::render('Admin/News/NewsList', ['news' => $this->newsService->getAll()]);
    }
}
