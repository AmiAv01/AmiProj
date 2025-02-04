<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "formula"=>'required|string|max:10',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
