<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\AutoSubmitFormService;
use Ecpay\Sdk\Services\UrlService; // Might be needed for URL checking?

class RealEcpayNewSdkTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_form_with_new_sdk()
    {
        // 1. Setup Data
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
        
        // 2. Setup SDK Factory
        // Using env keys directly
        $factory = new Factory([
            'hashKey' => env('ECPAY_HASH_KEY'),
            'hashIv' => env('ECPAY_HASH_IV'),
            'hashMethod' => 'sha256',
        ]);
        
        // 3. Create Service
        /** @var AutoSubmitFormService $service */
        $service = $factory->create('AutoSubmitFormWithCmvService');
        
        // 4. Prepare Input
        // New SDK input array might be similar to old one
        $input = [
            'MerchantID' => env('ECPAY_MERCHANT_ID'),
            'MerchantTradeNo' => 'BM' . time() . 'POC',
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => (int) $order->total_amount,
            'TradeDesc' => 'Test Order POC',
            'ItemName' => 'Test Article 100 TWD x 1', // New SDK might favor single string for ItemName
            'ReturnURL' => url('/api/payment/callback'),
            'ChoosePayment' => 'Credit',
            'ClientBackURL' => url('/orders/' . $order->id),
            'EncryptType' => 1,
        ];
        
        $action = env('ECPAY_PAYMENT_URL'); // https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5
        
        // 5. Generate
        try {
            $html = $service->generate($input, $action);
            
            // 6. Assert
            $this->assertStringContainsString('<form', $html);
            $this->assertStringContainsString($input['MerchantTradeNo'], $html);
            
            // Dump for visual check
            // dump($html);
            
        } catch (\Exception $e) {
            $this->fail('New SDK Failed: ' . $e->getMessage());
        }
    }
}
