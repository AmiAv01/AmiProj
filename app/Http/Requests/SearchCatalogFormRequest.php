<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchCatalogFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => 'array',
            'searchQ' => 'required|string|min:1|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
