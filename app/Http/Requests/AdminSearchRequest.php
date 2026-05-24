<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'searchQ' => 'required|string|min:1|max:255',
            'category' => 'required|string|max:20',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
