<?php

namespace App\Http\Requests;

use App\Enums\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'searchQ' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::enum(Category::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
