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

    public function index(string $id)
    {
        $detail = $this->detailService->getByInvoice($id);
        Log::info($detail);
        if (!$detail->isEmpty()){
            $sameDetails = $this->detailService->getSameDetails($id);
            Log::info($detail);

            return Inertia::render('Card/Index', ['detail' => $detail[0], 'sameDetails' => $sameDetails]);
        }
        return Inertia::render('Card/Index', ['detail' => $this->detailService->getByInvoiceFromOems($id),
            'sameDetails' => []]);
    }
}
