<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return Product::query()
            ->with('categories')
            ->when($filters['search'] ?? null, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($filters['category'] ?? null, fn ($q, $category) => $q->whereHas('categories', fn ($q) => $q->where('name', $category)))
            ->latest()
            ->paginate(5);
    }

    public function create(array $data): Product
    {
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $product = Product::create($data);
        $product->categories()->sync($categories);

        return $product->load('categories');
    }

    public function findOrFail(string $id): Product
    {
        return Product::with('categories')->findOrFail($id);
    }

    public function update(Product $product, array $data): Product
    {
        if (array_key_exists('categories', $data)) {
            $product->categories()->sync($data['categories']);
            unset($data['categories']);
        }

        $product->update($data);

        return $product->load('categories');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
