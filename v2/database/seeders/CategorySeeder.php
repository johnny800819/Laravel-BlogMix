<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * 執行資料庫種子 (Run database seeds)
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => '技術文章',
                'slug' => 'tech',
                'type' => 'article',
                'status' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => '生活日記',
                'slug' => 'life',
                'type' => 'article',
                'status' => 1,
                'sort_order' => 2,
            ],
            [
                'name' => '產品分類',
                'slug' => 'products',
                'type' => 'product',
                'status' => 1,
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
