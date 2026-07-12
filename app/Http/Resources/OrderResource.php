<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Order $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'total_price' => $resource->total_price,
            'status' => $resource->status,
            'created_by' => $resource->created_by,
        ];
    }
}
