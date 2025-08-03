<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            // Makanan Utama
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sayuran segar',
                'price' => 25000,
                'is_featured' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Mie Goreng',
                'description' => 'Mie goreng dengan bumbu special dan topping lengkap',
                'price' => 22000,
                'is_featured' => false,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Ayam Goreng',
                'description' => 'Ayam goreng crispy dengan sambal terasi',
                'price' => 30000,
                'is_featured' => true,
            ],
            
            // Minuman
            [
                'category_name' => 'Minuman',
                'name' => 'Es Teh Manis',
                'description' => 'Teh manis dingin yang menyegarkan',
                'price' => 5000,
                'is_featured' => false,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Es Jeruk',
                'description' => 'Jeruk segar dengan es batu',
                'price' => 8000,
                'is_featured' => false,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Kopi Hitam',
                'description' => 'Kopi hitam pahit yang nikmat',
                'price' => 12000,
                'is_featured' => true,
            ],
            
            // Dessert
            [
                'category_name' => 'Dessert',
                'name' => 'Es Krim Vanilla',
                'description' => 'Es krim vanilla lembut',
                'price' => 15000,
                'is_featured' => false,
            ],
            [
                'category_name' => 'Dessert',
                'name' => 'Pudding Coklat',
                'description' => 'Pudding coklat dengan saus coklat',
                'price' => 18000,
                'is_featured' => true,
            ],
            
            // Snack
            [
                'category_name' => 'Snack',
                'name' => 'Kentang Goreng',
                'description' => 'Kentang goreng crispy dengan saus',
                'price' => 15000,
                'is_featured' => false,
            ],
            [
                'category_name' => 'Snack',
                'name' => 'Nugget Ayam',
                'description' => 'Nugget ayam crispy 6 pcs',
                'price' => 20000,
                'is_featured' => false,
            ],
        ];

        foreach ($menus as $menu) {
            $category = Category::where('name', $menu['category_name'])->first();
            
            if ($category) {
                Menu::create([
                    'category_id' => $category->id,
                    'name' => $menu['name'],
                    'slug' => Str::slug($menu['name']),
                    'description' => $menu['description'],
                    'price' => $menu['price'],
                    'is_featured' => $menu['is_featured'],
                    'is_available' => true,
                    'sort_order' => 0,
                ]);
            }
        }
    }
}
