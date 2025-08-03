<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan Utama',
                'description' => 'Menu makanan utama restoran',
                'sort_order' => 1,
            ],
            [
                'name' => 'Minuman',
                'description' => 'Menu minuman segar',
                'sort_order' => 2,
            ],
            [
                'name' => 'Dessert',
                'description' => 'Menu pencuci mulut',
                'sort_order' => 3,
            ],
            [
                'name' => 'Snack',
                'description' => 'Menu makanan ringan',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
