<?php

namespace App\Http\Controllers;

use App\Services\DetailService;
use App\Services\SearchService;
use App\Services\PriceService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(protected DetailService $detailService, protected SearchService $searchService, protected
        PriceService $priceService)
    {

    }

    public function index(string $id): Response
    {
        $detail = $this->detailService->getByInvoice($id);
        $analogs = $this->detailService->getAnalogs($id);
        if (!$detail->isEmpty()){
            $sameDetails = $this->detailService->getSameDetails($id);
            return Inertia::render('Card/Index', ['detail' => $detail[0], 'sameDetails' => $sameDetails,
                'analogs' => $analogs, 'isEmpty' => false, 'price' => $this->priceService->getPrice($detail[0]->dt_id, (auth()->check() && auth()->user()->id))]);
        }
        $detailFromOems = $this->detailService->getByCodeFromOems($id);
        $dataFromOems = $this->searchService->getInfoAboutDetailFromOems($detailFromOems, $id);
        return Inertia::render('Card/Index', ['detail' => $dataFromOems,
            'sameDetails' => [], 'analogs' => $analogs, 'cargoIds' => $this->detailService->getCargoFromAnalogs($analogs), 'isEmpty' => true]);
    }
}
