<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartFormUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quantity' => [
                'required',
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
