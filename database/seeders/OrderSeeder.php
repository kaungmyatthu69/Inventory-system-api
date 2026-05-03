<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $products = Product::all();

        foreach (OrderStatus::cases() as $status) {
            $this->createOrder($user, $products, $status);
        }
    }

    private function createOrder(User $user, $products, OrderStatus $status): void
    {
        $items = $products->random(rand(1, 4));
        $orderItems = [];
        $totalPrice = 0;

        foreach ($items as $product) {
            $quantity = fake()->numberBetween(1, 5);
            $price = $product->price;
            $totalPrice += $price * $quantity;

            $orderItems[] = OrderItem::factory()->make([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => $status,
            'total_price' => $totalPrice,
        ]);

        $order->items()->saveMany($orderItems);
    }
}
