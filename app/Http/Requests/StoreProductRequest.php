<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['string', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a valid string.',
            'categories.array' => 'Categories must be a valid list.',
            'categories.*.exists' => 'One or more categories do not exist.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'stock.required' => 'Stock is required.',
            'stock.integer' => 'Stock must be a valid number.',
            'stock.min' => 'Stock must be at least 0.',
        ];
    }
}
