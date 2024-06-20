<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\Detail\DetailService;
use Inertia\Inertia;

class StarterController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index()
    {
        $details = $this->detailService->getByCategory(['СТАРТЕР']);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Стартеры',
        ]);
    }
}
