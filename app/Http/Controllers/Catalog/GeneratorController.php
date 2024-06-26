<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\Detail\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeneratorController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(Request $request)
    {
        $details = $this->detailService->getByCategory(['ГЕНЕРАТОР']);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Генераторы',
            'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
        ]);
    }
}
