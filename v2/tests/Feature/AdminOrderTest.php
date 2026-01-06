<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_order_status()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('admin-test')->plainTextToken;
        
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 100,
            'receiver_name' => 'Test',
            'receiver_phone' => '123',
            'shipping_address' => 'addr',
            'status' => 'pending'
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->putJson("/api/admin/orders/{$order->id}", [
                'status' => 'paid'
             ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'paid']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid'
        ]);
    }

    public function test_admin_can_view_orders()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('admin-test')->plainTextToken;
        Order::factory()->count(3)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->getJson('/api/admin/orders');

        $response->assertStatus(200)
             ->assertJsonCount(3, 'data');
    }

    public function test_non_admin_cannot_manage_orders()
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = $user->createToken('user-test')->plainTextToken;
        
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->getJson('/api/admin/orders');
        
        $response->assertStatus(403); 
    }
}
