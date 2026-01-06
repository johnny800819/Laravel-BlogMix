<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'image_path' => fake()->imageUrl(),
            'price' => fake()->randomFloat(2, 10, 500),
            'view_count' => fake()->numberBetween(0, 1000),
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}
