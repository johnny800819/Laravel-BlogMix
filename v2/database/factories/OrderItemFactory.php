<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'article_id' => \App\Models\Article::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'price_at_purchase' => fake()->randomFloat(2, 10, 100),
        ];
    }
}
