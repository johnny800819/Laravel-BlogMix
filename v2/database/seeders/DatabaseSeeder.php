<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Categories & Articles
        $this->call([
            CategorySeeder::class,
            ArticleSeeder::class,
        ]);

        // Orders
        \App\Models\Order::factory(10)->create()->each(function ($order) {
            \App\Models\OrderItem::factory(rand(1, 3))->create([
                'order_id' => $order->id,
                'article_id' => \App\Models\Article::inRandomOrder()->first()->id,
            ]);
        });

        // Service Tickets
        \App\Models\ServiceTicket::factory(5)->create();
    }
}
