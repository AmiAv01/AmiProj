<?php

namespace App\Http\Controllers\Desktop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DesktopController extends Controller
{
    public function index(): Response{
        return Inertia::render('Desktop/Index');
    }
}
