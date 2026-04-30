<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResourceItem;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    public function index(): ProductCollection
    {
        return new ProductCollection(
            $this->productService->list()
        );
    }

    public function store(StoreProductRequest $request): MessageResource
    {
        $product = $this->productService->create($request->validated());

        return new MessageResource([
            'status' => 201,
            'message' => 'Product created successfully.',
            'data' => new ProductResourceItem($product),
        ]);
    }

    public function show(string $id): MessageResource
    {
        $product = $this->findProductOrRespond($id);

        if ($product instanceof MessageResource) {
            return $product;
        }

        return new MessageResource([
            'status' => 200,
            'message' => 'Product fetched successfully.',
            'data' => new ProductResourceItem($product),
        ]);
    }

    public function update(UpdateProductRequest $request, string $id): MessageResource
    {
        $product = $this->findProductOrRespond($id);

        if ($product instanceof MessageResource) {
            return $product;
        }

        $product = $this->productService->update($product, $request->validated());

        return new MessageResource([
            'status' => 200,
            'message' => 'Product updated successfully.',
            'data' => new ProductResourceItem($product),
        ]);
    }

    public function destroy(string $id): MessageResource
    {
        $product = $this->findProductOrRespond($id);

        if ($product instanceof MessageResource) {
            return $product;
        }

        $this->productService->delete($product);

        return new MessageResource([
            'status' => 200,
            'message' => 'Product deleted successfully.',
            'data' => null,
        ]);
    }

    private function findProductOrRespond(string $id): Product|MessageResource
    {
        $product = $this->productService->find($id);

        if (! $product) {
            return new MessageResource([
                'status' => 404,
                'message' => 'Product not found.',
                'data' => null,
            ]);
        }

        return $product;
    }
}
