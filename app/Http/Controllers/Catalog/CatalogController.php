<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(array $name)
    {
        $details = Detail::where('dt_typec', 'ГЕНЕРАТОР')->paginate(12);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Генераторы',
        ]);
    }
}
