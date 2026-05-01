<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Category name must be a valid string.',
        ];
    }
}
