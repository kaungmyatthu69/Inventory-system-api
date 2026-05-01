<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatus;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MessageResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService) {}

    public function index(): CategoryCollection
    {
        return new CategoryCollection(
            $this->categoryService->list()
        );
    }

    public function store(StoreCategoryRequest $request): MessageResource
    {
        $category = $this->categoryService->create($request->validated());

        return new MessageResource([
            'status' => HttpStatus::CREATED->value,
            'message' => 'Category created successfully.',
            'data' => new CategoryResource($category),
        ]);
    }

    public function show(string $id): MessageResource
    {
        $category = $this->categoryService->findOrFail($id);

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Category fetched successfully.',
            'data' => new CategoryResource($category),
        ]);
    }

    public function update(UpdateCategoryRequest $request, string $id): MessageResource
    {
        $category = $this->categoryService->findOrFail($id);
        $category = $this->categoryService->update($category, $request->validated());

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Category updated successfully.',
            'data' => new CategoryResource($category),
        ]);
    }

    public function destroy(string $id): MessageResource
    {
        $category = $this->categoryService->findOrFail($id);
        $this->categoryService->delete($category);

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Category deleted successfully.',
            'data' => null,
        ]);
    }
}
