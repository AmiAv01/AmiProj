<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'searchQ' => 'string|nullable|max:255',
            'category' => 'string|nullable|max:20',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
