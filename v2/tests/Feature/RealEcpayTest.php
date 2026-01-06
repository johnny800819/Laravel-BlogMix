<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\PaymentService;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class RealEcpayTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_real_ecpay_form_string()
    {
        // 1. Create Data
        $user = User::factory()->create();
        $article = Article::factory()->create(['price' => 100, 'title' => 'Test Article']);
        
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 100,
            'receiver_name' => 'Real Tester',
            'receiver_phone' => '0912345678',
            'shipping_address' => 'Real Address Data',
            'status' => 'pending'
        ]);
        
        // Add item to order so ECPay SDK has items to process
        $order->items()->create([
            'article_id' => $article->id,
            'price' => 100,
            'quantity' => 1
        ]);

        // 2. Instantiate Real Service (No Mocking)
        $paymentService = new PaymentService();

        // 3. Execute
        try {
            $formHtml = $paymentService->generatePaymentForm($order);
            
            // 4. Assert
            // The SDK returns a full HTML page with a form that auto-submits.
            // We check for key strings.
            $this->assertStringContainsString('<form id="ecpay-form"', $formHtml);
            $this->assertStringContainsString('action="https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5"', $formHtml); // Staging URL by default usually
            $this->assertStringContainsString('MerchantTradeNo', $formHtml);
            
            // Log output for manual inspection if needed
            // Log::info('Generated ECPay Form:', ['html' => $formHtml]);

        } catch (\Exception $e) {
            $this->fail('ECPay SDK threw an exception: ' . $e->getMessage());
        }
    }
}
