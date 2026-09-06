<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
