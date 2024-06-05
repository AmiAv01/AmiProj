<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        return Inertia::render('Blog/Index');
    }
}
