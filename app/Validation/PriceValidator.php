<?php

namespace App\Services\Cart;

use App\Exceptions\ZeroPriceException;

final class PriceValidator
{

    public function validate(float|int $price, int $productId): void
    {
        if ($price <= 0) {
            throw new ZeroPriceException("Product price must be greater than zero for product ID: {$productId}.");
        }
    }
}
