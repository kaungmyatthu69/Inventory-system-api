<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;

class DashboardService
{
    public function getStats(): array
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $revenue = Order::where('status', OrderStatus::COMPLETED)->sum('total_price');
        $lowStock = Product::where('stock', '<=', 5)->count();

        $recentOrders = Order::query()
            ->with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Order $order) => [
                'id' => $order->id,
                'user' => $order->user->name,
                'total_price' => $order->total_price,
                'status' => $order->status->value,
                'created_at' => $order->created_at?->toDateTimeString(),
            ]);

        $lowStockAlerts = Product::query()
            ->where('stock', '<=', 5)
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
            ]);

        return [
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
            'revenue' => (float) $revenue,
            'low_stock' => $lowStock,
            'recent_orders' => $recentOrders,
            'low_stock_alerts' => $lowStockAlerts,
        ];
    }
}
