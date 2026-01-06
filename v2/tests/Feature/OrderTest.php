<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_checkout()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['price' => 100]);
        
        // Add item to cart
        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create(['article_id' => $article->id, 'quantity' => 2]);

        $data = [
            'receiver_name' => 'John Doe',
            'receiver_phone' => '1234567890',
            'shipping_address' => '123 St, City',
            'payment_method' => 'credit_card',
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $data);

        $response->assertStatus(201)
            ->assertJson([
                'total_amount' => 200,
                'status' => 'pending',
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => 200,
        ]);
        
        $this->assertDatabaseHas('order_items', [
            'article_id' => $article->id,
            'quantity' => 2,
            'price' => 100,
        ]);

        // Assert Cart is empty/deleted
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }
    
    public function test_guest_cannot_checkout()
    {
        $response = $this->postJson('/api/orders', []);
        $response->assertStatus(401);
    }
}
