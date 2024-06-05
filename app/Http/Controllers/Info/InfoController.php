<?php

namespace App\Http\Controllers\Info;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class InfoController extends Controller
{
    public function index()
    {
        return Inertia::render('Info/Index');
    }
}
