<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Electronics', 'Clothing', 'Food & Beverages', 'Office Supplies'];

        foreach ($categories as $name) {
            Category::factory()->create(['name' => $name]);
        }
    }
}
