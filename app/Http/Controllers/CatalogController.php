<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index()
    {
        $details = Detail::where('dt_typec', 'ГЕНЕРАТОР')->paginate(12);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Генераторы',
        ]);
    }
}
