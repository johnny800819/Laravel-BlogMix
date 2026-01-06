<?php
use App\Models\Order;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = Order::latest()->first();
if ($order) {
    echo "ID: " . $order->id . "\n";
    echo "TRADENO: " . $order->trade_no . "\n";
    echo "STATUS: " . $order->status . "\n";
    echo "ITEMS_COUNT: " . $order->items->count() . "\n";
    if ($order->items->count() > 0) {
        $item = $order->items->first();
        echo "ITEM_PRICE: " . $item->price_at_purchase . "\n";
    }
} else {
    echo "NO_Order_FOUND\n";
}
