<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartFormCreateRequest extends FormRequest
{
    private const int MAX_QUANTITY = 999;

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:detail,dt_id',
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:'.self::MAX_QUANTITY],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
