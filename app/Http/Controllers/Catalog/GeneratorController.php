<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Firm;
use App\Services\Detail\DetailService;
use Inertia\Inertia;

class GeneratorController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index()
    {
        $details = $this->detailService->getByCategory(['ГЕНЕРАТОР']);
        $categories = Firm::all();

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'categories' => $categories,
            'title' => 'Генераторы',
        ]);
    }
}
