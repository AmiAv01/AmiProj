<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Firm;
use App\Services\Detail\DetailService;
use Inertia\Inertia;

class BearingController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index()
    {
        $details = $this->detailService->getByCategory(['ПОДШИПНИК', 'ПОДШИПНИК КОМПРЕССОРА КОНДИЦИОНЕРА']);
        $categories = Firm::all();

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'categories' => $categories,
            'title' => 'Подшипники',
        ]);
    }
}
