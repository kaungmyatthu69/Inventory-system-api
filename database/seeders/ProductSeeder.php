<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private array $products = [
        'Electronics' => [
            ['name' => 'MSI Laptop', 'price' => 1299.99, 'stock' => 25],
            ['name' => 'Dell Monitor 27"', 'price' => 349.99, 'stock' => 40],
            ['name' => 'Logitech MX Master 3S', 'price' => 99.99, 'stock' => 60],
            ['name' => 'Mechanical Keyboard RGB', 'price' => 149.99, 'stock' => 35],
            ['name' => 'Sony WH-1000XM5', 'price' => 349.99, 'stock' => 20],
        ],
        'Clothing' => [
            ['name' => 'Nike Air Max 270', 'price' => 159.99, 'stock' => 50],
            ['name' => "Levi's 501 Jeans", 'price' => 69.99, 'stock' => 80],
            ['name' => 'North Face Jacket', 'price' => 199.99, 'stock' => 30],
            ['name' => 'Adidas Ultraboost', 'price' => 189.99, 'stock' => 45],
        ],
        'Food & Beverages' => [
            ['name' => 'Nespresso Coffee Machine', 'price' => 249.99, 'stock' => 15],
            ['name' => 'Green Tea Pack (50 bags)', 'price' => 12.99, 'stock' => 200],
            ['name' => 'Belgian Chocolate Box', 'price' => 29.99, 'stock' => 100],
        ],
        'Office Supplies' => [
            ['name' => 'A4 Copy Paper (500 sheets)', 'price' => 8.99, 'stock' => 300],
            ['name' => 'Bic Ballpoint Pen (12 pack)', 'price' => 5.99, 'stock' => 500],
            ['name' => 'Swingline Stapler', 'price' => 14.99, 'stock' => 75],
        ],
    ];

    public function run(): void
    {
        foreach ($this->products as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->firstOrFail();

            foreach ($items as $productData) {
                $product = Product::factory()->create($productData);
                $product->categories()->attach($category->id);
            }
        }
    }
}
