<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatus;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    public function index(): ProductCollection
    {
        return new ProductCollection(
            $this->productService->list(request()->only('search', 'category'))
        );
    }

    public function store(StoreProductRequest $request): MessageResource
    {
        $product = $this->productService->create($request->validated());

        return new MessageResource([
            'status' => HttpStatus::CREATED->value,
            'message' => 'Product created successfully.',
            'data' => new ProductResource($product),
        ]);
    }

    public function show(string $id): MessageResource
    {
        $product = $this->productService->findOrFail($id);

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Product fetched successfully.',
            'data' => new ProductResource($product),
        ]);
    }

    public function update(UpdateProductRequest $request, string $id): MessageResource
    {
        $product = $this->productService->findOrFail($id);
        $product = $this->productService->update($product, $request->validated());

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($product),
        ]);
    }

    public function destroy(string $id): MessageResource
    {
        $product = $this->productService->findOrFail($id);
        $this->productService->delete($product);

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Product deleted successfully.',
            'data' => null,
        ]);
    }
}
