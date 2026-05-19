<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Электроника', 'description' => 'Смартфоны, ноутбуки, планшеты']);
        Category::create(['name' => 'Одежда', 'description' => 'Мужская, женская, детская одежда']);
        Category::create(['name' => 'Книги', 'description' => 'Художественная литература, учебники']);
    }
}
