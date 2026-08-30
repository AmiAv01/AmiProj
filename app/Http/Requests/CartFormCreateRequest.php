<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartFormCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:detail,dt_id',
            'quantity' => [
                'sometimes',
                'integer',
                'min:'.config('cart.quantity.min'),
                'max:'.config('cart.quantity.max'),
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
