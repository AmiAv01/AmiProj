<?php

namespace App\Http\Controllers;

use App\Services\DetailService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(int $id)
    {
        $detail = $this->detailService->getById($id);
        $sameDetails = $this->detailService->getSameDetails($id);
        Log::info($sameDetails);

        return Inertia::render('Card/Index', ['detail' => $detail, 'sameDetails' => $sameDetails]);
    }
}
