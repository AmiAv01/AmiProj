<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|string|max:10'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
