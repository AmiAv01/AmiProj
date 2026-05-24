<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartFormCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:detail,dt_id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
