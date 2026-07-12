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
        $sameDetails = $this->productService->getSameDetails($detail->dt_typec, $id);

        // Если пользователь не авторизован (гость), маскируем артикулы звёздочками
        if (! $this->auth->guard()->check()) {
            $sameDetails = array_map(function ($item) {
                $item['dt_invoice'] = $this->maskString($item['dt_invoice']);
                $item['dt_cargo'] = $this->maskString($item['dt_cargo']);

                return $item;
            }, $sameDetails);
        }

        $data = [
            'detail' => $detail,
            'isEmpty' => false,
            'analogs' => $this->productService->getAnalogs($id),
            'sameDetails' => $sameDetails, // Теперь передается всегда
        ];

        if ($this->auth->guard()->check()) {
            $data = array_merge($data, [
                'imageUrl' => $this->productService->getImageUrl($detail->dt_foto),
                'price' => $this->priceService->getPrice($detail->dt_code, $this->auth->guard()->id()),
            ]);
        }

        return $data;
    }

    protected function buildViewDataForNotFoundDetail(string $id): array
    {
        $detailFromOems = $this->productService->getProductInfoFromOems($id)->toArray();
        $sameDetails = $this->productService->getSameDetails($detailFromOems['dt_typec'], $id);

        // Если пользователь не авторизован (гость), маскируем артикулы звёздочками
        if (! $this->auth->guard()->check()) {
            $sameDetails = array_map(function ($item) {
                $item['dt_invoice'] = $this->maskString($item['dt_invoice']);
                $item['dt_cargo'] = $this->maskString($item['dt_cargo']);

                return $item;
            }, $sameDetails);
        }

        $data = [
            'detail' => $detailFromOems,
            'isEmpty' => true,
            'analogs' => $this->productService->getAnalogs($id),
            'sameDetails' => $sameDetails, // Теперь передается всегда
        ];

        if ($this->auth->guard()->check()) {
            $data = array_merge($data, [
                'imageUrl' => $this->productService->getImageUrl(null),
                'cargoIds' => $this->productService->getCargoFromAnalogs($data['analogs']),
            ]);
        }

        return $data;
    }

    private function maskString(?string $value): string
    {
        if (empty($value)) {
            return '';
        }

        $length = mb_strlen($value);
        if ($length <= 2) {
            return $value;
        }

        $first = mb_substr($value, 0, 1);
        $last = mb_substr($value, -1);
        $stars = str_repeat('*', $length - 2);

        return $first.$stars.$last;
    }

    public function resolveViewName(): string
    {
        return $this->auth->guard()->check() ? 'Card/Index' : 'Card/GuestCard';
    }
}
