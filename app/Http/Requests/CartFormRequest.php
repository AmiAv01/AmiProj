<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
