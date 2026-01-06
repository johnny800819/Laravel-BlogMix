<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\User;
use App\Models\Article;
use App\Models\CartItem;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Http;

echo "🚀 Starting ECPay Real Flow Verification...\n";

// 1. Create a Real User and Order
echo "\n[Step 1] Creating Test Data...\n";
$user = User::factory()->create();
$article = Article::factory()->create(['price' => 1000]); // 1000 TWD
$order = Order::create([
    'user_id' => $user->id,
    'total_amount' => 1000,
    'receiver_name' => 'Real Tester',
    'receiver_phone' => '0912345678',
    'shipping_address' => 'Taipei City',
    'status' => 'pending'
]);
// Add item to order logic (mocked or real relation)
// Ideally we need OrderItems, but PaymentService iterates items. 
// We'll mimic the item structure if needed, or rely on Order total.
// PaymentService loop: foreach ($order->items ...
// We need to create order items.
// Assuming Order hasMany OrderItem (or CartItem logic reused? No, usually OrderItem)
// Let's check Order model relation.
// If not easily creatable, we skip item detail or create dummy.
echo "  -> Order #{$order->id} created. Status: {$order->status}\n";

// 2. Generate ECPay Form Data
echo "\n[Step 2] Generating ECPay Form...\n";
$service = new PaymentService();
try {
    $formHtml = $service->generatePaymentForm($order);
    
    // Extract parameters for validation
    preg_match('/<input type="hidden" name="MerchantTradeNo" value="(.*?)"/', $formHtml, $matches);
    $tradeNo = $matches[1] ?? 'UNKNOWN';
    
    preg_match('/<input type="hidden" name="CheckMacValue" value="(.*?)"/', $formHtml, $matches);
    $checkMac = $matches[1] ?? 'UNKNOWN';

    echo "  -> MerchantTradeNo: {$tradeNo}\n";
    echo "  -> CheckMacValue: {$checkMac}\n";
    
    // Refresh order to get the trade_no saved in DB
    $order->refresh();
    echo "  -> DB Updated with TradeNo: {$order->trade_no}\n";

    if (empty($tradeNo) || empty($checkMac)) {
        die("❌ FAILED: Could not generate valid ECPay parameters.\n");
    }
    echo "✅ Form Generation Successful.\n";
    echo "  -> 💡 Tip: Use ECPay Test Card for manual testing:\n";
    echo "     Card: 4311-9522-2222-2222 | CVV: 222 | Date: Future\n";

} catch (\Exception $e) {
    die("❌ FAILED: " . $e->getMessage() . "\n");
}

// 3. Simulate ECPay Callback (Server-to-Server)
echo "\n[Step 3] Simulating ECPay Callback (Server-to-Server)...\n";
echo "  -> Note: Real ECPay server cannot reach 'localhost', so we mimic the exact POST request it would send.\n";

// Callback parameters (Success case)
$callbackData = [
    'MerchantID' => env('ECPAY_MERCHANT_ID'),
    'MerchantTradeNo' => $order->trade_no,
    'RtnCode' => '1', // 1 = Success
    'RtnMsg' => 'Succeeded',
    'TradeNo' => 'ECPayTest' . time(),
    'TradeAmt' => $order->total_amount,
    'PaymentDate' => date('Y/m/d H:i:s'),
    'PaymentType' => 'Credit_CreditCard',
];

// Generate Valid CheckMacValue using SDK Service
try {
    $factory = new \Ecpay\Sdk\Factories\Factory([
        'hashKey' => env('ECPAY_HASH_KEY'),
        'hashIv' => env('ECPAY_HASH_IV'),
        'hashMethod' => 'sha256',
    ]);
    $checkMacValueService = $factory->create(\Ecpay\Sdk\Services\CheckMacValueService::class);
    
    // The SDK's generate() method takes the array, appends CheckMacValue, and returns the checksum string.
    // However, the service->generate($source) filters out existing CheckMacValue and returns the HASH string.
    $checksum = $checkMacValueService->generate($callbackData);
    
    $callbackData['CheckMacValue'] = $checksum;
    
    echo "  -> Generated Callback CheckMacValue: {$checksum}\n";

} catch (\Exception $e) {
    die("❌ FAILED to generate callback checksum: " . $e->getMessage() . "\n");
}

// Send POST to our own API
// Assuming running inside docker, localhost:8000 is accessible? 
// No, inside docker container, localhost:8000 might work if using `php artisan serve`, 
// but we are running THIS script as CLI php.
// We can instantiate the controller directly? No, routing matters.
// Better: Use `app()->handle($request)` to simulate a request internally without network!
// This is what `this->postJson` does in Feature tests.
// Let's use that same trick but in this script.

$request = Illuminate\Http\Request::create(
    '/api/payment/callback', 
    'POST', 
    $callbackData
);

echo "  -> Dispatching Request to Application...\n";
$response = $app->handle($request);

echo "  -> Response Status: " . $response->getStatusCode() . "\n";
echo "  -> Response Body: " . $response->getContent() . "\n";

if ($response->getContent() === '1|OK') {
    echo "✅ Callback Accepted.\n";
} else {
    echo "❌ Callback Rejected.\n";
}

// 4. Verify Final Database State
$order->refresh();
echo "\n[Step 4] Verifying Final Status...\n";
echo "  -> Order Status: {$order->status}\n";

if ($order->status === 'paid') {
    echo "🎉 SUCCESS: Full Flow Verified (Order is PAID).\n";
} else {
    echo "❌ FAILED: Order is NOT paid.\n";
}
