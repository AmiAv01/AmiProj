<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:'.implode(',', OrderStatus::values()),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
