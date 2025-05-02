<?php

namespace App\Services\Product;

use App\DTO\OemInfoDTO;
use App\Services\OemService;


class ProductService
{
    public function __construct(
        private AnalogService $analogService,
        private CompatiblePartsService $compatiblePartsService,
        private OemService $oemService,
        private ProductImageService $imageService
    ) {}

    public function getSameDetails(string $detailType, string $detailInvoice): array
    {
        return $this->compatiblePartsService->getCompatibleParts($detailType, $detailInvoice);
    }

    public function getAnalogs(string $id):array
    {
        return $this->analogService->getAnalogs($id);
    }

    public function getCargoFromAnalogs(array $details):array
    {
        return $this->analogService->getCargoFromAnalogs($details);
    }

    public function getProductInfoFromOems(string $code): OemInfoDTO{
        return $this->oemService->getProductInfoFromOems($code);
    }

    public function getImageUrl(?string $imageName = null):string {
        return $this->imageService->getImageUrl($imageName);
    }

}
