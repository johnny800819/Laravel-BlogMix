<?php
use App\Models\Order;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$o = Order::latest()->first();
if ($o) {
    $testVal = 'TEST_' . time();
    $o->update(['trade_no' => $testVal, 'paid_at' => now()]);
    $o->refresh();
    
    if ($o->trade_no === $testVal && $o->paid_at !== null) {
        echo "VERIFICATION_PASSED: TradeNo updated to {$o->trade_no}\n";
    } else {
        echo "VERIFICATION_FAILED: TradeNo is {$o->trade_no}\n";
    }
} else {
    echo "NO_ORDER_TO_TEST\n";
}
