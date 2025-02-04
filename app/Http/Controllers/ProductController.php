<?php

namespace App\Http\Controllers;

use App\Services\DetailService;
use App\Services\ProductService;
use App\Services\SearchService;
use App\Services\PriceService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(protected DetailService $detailService, protected SearchService $searchService, protected
        PriceService $priceService, protected ProductService $productService)
    {}

    public function index(string $id): Response
    {
        $detail = $this->detailService->getByInvoice($id);
        $analogs = $this->productService->getAnalogs($id);
        if (!$detail->isEmpty()){
            if (!auth()->check()){
                return Inertia::render('Card/GuestCard', ['detail' => $detail[0], 'isEmpty' => false]);
            }
            return Inertia::render('Card/Index', ['detail' => $detail[0], 'sameDetails' => $this->productService->getSameDetails($detail[0]->dt_typec, $id),
                'analogs' => $analogs, 'isEmpty' => false, 'imageUrl' => $this->productService->getImageUrl($detail[0]->dt_foto),
                'price' => $this->priceService->getPrice($detail[0]->dt_code, (auth()->check() && auth()->user()->id))]);
        }
        $detailFromOems = $this->productService->getProductInfoFromOems($this->searchService, $id);
        return Inertia::render('Card/Index', ['detail' => $detailFromOems,
            'sameDetails' => $this->productService->getSameDetails($detailFromOems['dt_typec'], $id), 'analogs' => $analogs, 'cargoIds' => $this->productService->getCargoFromAnalogs($analogs), 'isEmpty' => true,
            'imageUrl' => $this->productService->getImageUrl()]);
    }
}
