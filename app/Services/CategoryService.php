<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function list(): LengthAwarePaginator
    {
        return Category::query()
            ->latest()
            ->paginate(20);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function findOrFail(string $id): Category
    {
        return Category::findOrFail($id);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
