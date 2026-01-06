<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * 執行資料庫種子 (Run database seeds)
     */
    public function run(): void
    {
        // IDs are hardcoded because we seeded them sequentially in CategorySeeder
        // Tech=1, Life=2, Products=3
        
        $articles = [
            [
                'category_id' => 1, // Tech
                'title' => 'Laravel 11 新功能介紹',
                'content' => 'Laravel 11 帶來了許多令人興奮的新功能，包括改進的路由系統、更好的性能優化...',
                'price' => 0,
                'is_published' => true,
                'published_at' => now(),
                'view_count' => 0,
            ],
            [
                'category_id' => 1, // Tech
                'title' => 'Vue 3 Composition API 實戰',
                'content' => '學習如何使用 Vue 3 的 Composition API 來建立更靈活、可維護的應用程式...',
                'price' => 0,
                'is_published' => true,
                'published_at' => now(),
                'view_count' => 0,
            ],
            [
                'category_id' => 2, // Life
                'title' => '咖啡品味之旅',
                'content' => '探索世界各地的精品咖啡，從產地到沖煮方法的完整指南...',
                'price' => 0,
                'is_published' => true,
                'published_at' => now(),
                'view_count' => 0,
            ],
            [
                'category_id' => 3, // Products
                'title' => '精選手工咖啡豆',
                'content' => '來自衣索比亞的單一產區咖啡豆，帶有花香與果香的完美平衡...',
                'price' => 450.00,
                'is_published' => true,
                'published_at' => now(),
                'view_count' => 0,
            ],
            [
                'category_id' => 3, // Products
                'title' => '專業手沖壺套組',
                'content' => '包含溫控手沖壺、不鏽鋼濾杯、溫度計，讓您在家也能享受專業級手沖咖啡...',
                'price' => 1280.00,
                'is_published' => true,
                'published_at' => now(),
                'view_count' => 0,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
