<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\MessageError;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return Order::query()
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20);
    }

    public function create(array $items): Order
    {
        return DB::transaction(function () use ($items) {
            $products = $this->resolveProducts($items);
            $totalPrice = '0.00';

            $orderItems = collect($items)->map(function (array $item) use ($products, &$totalPrice) {
                $product = $products->get($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new MessageError(
                        "Insufficient stock for {$product->name}. Available: {$product->stock}.",
                        422
                    );
                }

                $subtotal = bcmul((string) $product->price, (string) $item['quantity'], 2);
                $totalPrice = bcadd($totalPrice, $subtotal, 2);

                return [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => OrderStatus::PENDING,
            ]);

            $order->items()->createMany($orderItems->toArray());

            $this->deductStock($items, $products);

            return $order->load('items.product');
        });
    }

    public function findOrFail(string $id, string $userId): Order
    {
        return Order::query()
            ->where('user_id', $userId)
            ->with('items.product')
            ->findOrFail($id);
    }

    private function resolveProducts(array $items): Collection
    {
        $productIds = collect($items)->pluck('product_id')->unique();

        $products = Product::whereIn('id', $productIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            throw new MessageError('One or more selected products do not exist.', 422);
        }

        return $products;
    }

    private function deductStock(array $items, Collection $products): void
    {
        foreach ($items as $item) {
            $products->get($item['product_id'])
                ->decrement('stock', $item['quantity']);
        }
    }
}
