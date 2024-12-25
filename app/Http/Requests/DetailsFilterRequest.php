<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailsFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => 'array'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
