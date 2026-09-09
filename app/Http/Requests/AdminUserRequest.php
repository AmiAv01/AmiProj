<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'formula' => 'sometimes|required|string|max:10',
            'notification_email' => 'sometimes|required|string|lowercase|email|max:255',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('notification_email')) {
            $this->merge([
                'notification_email' => mb_strtolower(trim((string) $this->input('notification_email'))),
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
