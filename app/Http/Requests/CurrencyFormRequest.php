<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currency' => 'required|numeric|between:0.00,99.99',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
