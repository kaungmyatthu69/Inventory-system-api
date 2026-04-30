<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function list(): LengthAwarePaginator
    {
        return Product::query()
            ->latest()
            ->paginate(20);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function find(string $id): ?Product
    {
        return Product::find($id);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
