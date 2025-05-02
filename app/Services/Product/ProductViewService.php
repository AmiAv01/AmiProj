<?php

namespace App\Services\Product;

use App\Services\DetailService;
use App\Services\Interface\ProductViewServiceInterface;
use App\Services\PriceService;
use Illuminate\Contracts\Auth\Factory as AuthManager;

class ProductViewService implements ProductViewServiceInterface
{
    public function __construct(
        private ProductService $productService,
        private DetailService $detailService,
        private PriceService $priceService,
        private AuthManager $auth,
    ) {}

    public function getViewDataForProduct(string $id): array
    {
        $detail = $this->detailService->getByInvoice($id);

        return $detail
            ? $this->buildViewDataForFoundDetail($detail, $id)
            : $this->buildViewDataForNotFoundDetail($id);
    }

    protected function buildViewDataForFoundDetail(object $detail, string $id): array
    {
        $data = [
            'detail' => $detail,
            'isEmpty' => false,
            'analogs' => $this->productService->getAnalogs($id),
        ];

        if ($this->auth->guard()->check()) {
            $data = array_merge($data, [
                'sameDetails' => $this->productService->getSameDetails($detail->dt_typec, $id),
                'imageUrl' => $this->productService->getImageUrl($detail->dt_foto),
                'price' => $this->priceService->getPrice($detail->dt_code, $this->auth->guard()->id()),
            ]);
        }

        return $data;
    }

    protected function buildViewDataForNotFoundDetail(string $id): array
    {
        $detailFromOems = $this->productService->getProductInfoFromOems($id)->toArray();

        $data = [
            'detail' => $detailFromOems,
            'isEmpty' => true,
            'analogs' => $this->productService->getAnalogs($id),
        ];

        if ($this->auth->guard()->check()) {
            $data = array_merge($data, [
                'sameDetails' => $this->productService->getSameDetails($detailFromOems['dt_typec'], $id),
                'imageUrl' => $this->productService->getImageUrl(null),
                'cargoIds' => $this->productService->getCargoFromAnalogs($data['analogs']),
            ]);
        }

        return $data;
    }

    public function resolveViewName(): string
    {
        return $this->auth->guard()->check() ? 'Card/Index' : 'Card/GuestCard';
    }
}
