<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class InfoController extends Controller
{
    public function index()
    {
        return Inertia::render('Info/Index');
    }
}
