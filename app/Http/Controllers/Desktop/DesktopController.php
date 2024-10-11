<?php

namespace App\Http\Controllers\Desktop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DesktopController extends Controller
{
    public function index(){
        return Inertia::render('Desktop/Index');
    }
}
