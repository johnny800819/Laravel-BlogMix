<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_item_to_cart()
    {
        $article = Article::factory()->create(['price' => 100]);
        $sessionId = 'test-session-id';

        $response = $this->withHeaders(['X-Session-ID' => $sessionId])
            ->postJson('/api/cart/items', [
                'article_id' => $article->id,
                'quantity' => 2,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('carts', ['session_id' => $sessionId]);
        $this->assertDatabaseHas('cart_items', [
            'article_id' => $article->id,
            'quantity' => 2,
        ]);
    }

    public function test_authenticated_user_can_add_item_to_cart()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['price' => 50]);

        $response = $this->actingAs($user)
            ->postJson('/api/cart/items', [
                'article_id' => $article->id,
                'quantity' => 1,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
    }

    public function test_cart_calculates_total_correctly()
    {
        $article1 = Article::factory()->create(['price' => 100]);
        $article2 = Article::factory()->create(['price' => 50]);
        $sessionId = 'total-calc-session';

        $cart = Cart::create(['session_id' => $sessionId]);
        $cart->items()->create(['article_id' => $article1->id, 'quantity' => 2]); // 200
        $cart->items()->create(['article_id' => $article2->id, 'quantity' => 1]); // 50

        $response = $this->withHeaders(['X-Session-ID' => $sessionId])
             ->getJson('/api/cart');

        $response->assertStatus(200)
             ->assertJson(['total' => 250]);
    }
    public function test_cart_item_quantity_can_be_updated()
    {
        $article = Article::factory()->create(['price' => 100]);
        $sessionId = 'update-session';
        $cart = Cart::create(['session_id' => $sessionId]);
        $item = $cart->items()->create(['article_id' => $article->id, 'quantity' => 1]);

        $response = $this->withHeaders(['X-Session-ID' => $sessionId])
            ->putJson("/api/cart/items/{$item->id}", [
                'quantity' => 5
            ]);

        $response->assertStatus(200)
            ->assertJson(['quantity' => 5]);
            
        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 5
        ]);
    }

    public function test_cart_item_can_be_removed()
    {
        $article = Article::factory()->create(['price' => 100]);
        $sessionId = 'remove-session';
        $cart = Cart::create(['session_id' => $sessionId]);
        $item = $cart->items()->create(['article_id' => $article->id, 'quantity' => 1]);

        $response = $this->withHeaders(['X-Session-ID' => $sessionId])
             ->deleteJson("/api/cart/items/{$item->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }
}
