<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_payment_form()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 100,
            'receiver_name' => 'Tester',
            'receiver_phone' => '0912345678',
            'shipping_address' => 'Test Addr',
            'status' => 'pending'
        ]);

        // Mock PaymentService
        $this->mock(PaymentService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generatePaymentForm')
                ->once()
                ->andReturn('<form action="ecpay..."></form>');
        });

        $response = $this->actingAs($user)
            ->post("/api/orders/{$order->id}/pay");

        $response->assertStatus(200)
            ->assertSee('<form action="ecpay..."></form>', false);
    }

    public function test_payment_callback_updates_order_status()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 100,
            'receiver_name' => 'Tester',
            'receiver_phone' => '0912345678',
            'shipping_address' => 'Test Addr',
            'status' => 'pending',
            'trade_no' => 'BM12345678'
        ]);

        // Mock PaymentService for validation
        $this->mock(PaymentService::class, function (MockInterface $mock) {
            $mock->shouldReceive('validateCallback')
                ->once()
                ->andReturn([
                    'RtnCode' => '1',
                    'MerchantTradeNo' => 'BM12345678',
                    'PaymentType' => 'Credit_CreditCard'
                ]);
        });

        $response = $this->post('/api/payment/callback', [
            'MerchantTradeNo' => 'BM12345678',
            'RtnCode' => '1'
        ]);

        $response->assertStatus(200)
            ->assertSee('1|OK');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'payment_method' => 'ECPay_Credit' // Default in controller based on logic
        ]);
    }
    
    public function test_payment_callback_fails_with_invalid_checksum()
    {
         // Mock PaymentService for validation failure
        $this->mock(PaymentService::class, function (MockInterface $mock) {
            $mock->shouldReceive('validateCallback')
                ->once()
                ->andReturn([]); // Empty return means validation failed
        });

        $response = $this->post('/api/payment/callback', [
            'MerchantTradeNo' => 'BM_FAKE',
        ]);

        $response->assertStatus(200)
             ->assertSee('0|Error');
    }
}
