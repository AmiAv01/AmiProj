<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'totalPrice' => 'required|numeric|min:0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
