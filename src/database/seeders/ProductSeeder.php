<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create(['name' => 'iPhone 15', 'description' => 'Флагманский смартфон Apple', 'price' => 79999, 'category_id' => 1]);
        Product::create(['name' => 'MacBook Pro', 'description' => 'Ноутбук для профессионалов', 'price' => 199999, 'category_id' => 1]);
        Product::create(['name' => 'Джинсы', 'description' => 'Классические синие джинсы', 'price' => 3999, 'category_id' => 2]);
    }
}
