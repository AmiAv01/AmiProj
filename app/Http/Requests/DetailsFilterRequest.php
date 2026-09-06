<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DetailsFilterRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => $this->route('type'),
            'category' => $this->route('category'),
        ]);
    }

    public function rules(): array
    {
        $type = (string) $this->route('type');
        $filters = config("parts.filters.{$type}", []);
        $hasCategories = is_array($filters) && $filters !== [] && ! array_is_list($filters);

        return [
            'type' => ['required', 'string', Rule::in(array_keys(config('parts.filters', [])))],
            'category' => [
                Rule::requiredIf($hasCategories),
                Rule::prohibitedIf(! $hasCategories),
                'nullable',
                'string',
                Rule::in($hasCategories ? array_keys($filters) : []),
            ],
            'filter' => ['sometimes', 'array'],
            'filter.id' => ['sometimes', 'nullable', 'string', 'regex:/^\d+(?:,\d+)*$/'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
