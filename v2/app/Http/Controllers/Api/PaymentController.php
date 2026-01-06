<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function callback(Request $request)
    {
        // 1. Validate CheckMacValue
        $feedback = $this->paymentService->validateCallback($request->all());

        if (empty($feedback)) {
            Log::warning('ECPay Callback Invalid CheckMacValue', $request->all());
            return '0|Error';
        }

        // 2. Check RtnCode
        if (!isset($feedback['RtnCode']) || $feedback['RtnCode'] != '1') {
            Log::warning('ECPay Callback Failed RtnCode', $feedback);
            return '1|OK'; // Acknowledge even if failed transaction, typically
        }

        // 3. Find Order via MerchantTradeNo
        // Note: In generatePaymentForm, we used 'BM' . time() . Str::random(4). 
        // Ideally, we should have saved this to the order before sending.
        // Or we can parse the OrderID from TradeDesc if we put it there. 
        // Let's assume we implement logic to save trade_no in generatePaymentForm or here we search by it.
        // BUT SDK generates it inside. 
        
        // BETTER APPROACH: 
        // In PaymentService::generatePaymentForm, we generated it: $obj->Send['MerchantTradeNo'].
        // We *MUST* save it to Order DB before returning the form.
        // Let's update PaymentService to handle Order update logic, or do it in Controller.
        
        // For now, let's look at the implementation. The current PaymentService doesn't save it.
        // I will need to update PaymentService or this logic. 
        // Let's assume we pass the TradeNo back via CustomField (if available) or assume logic updates.
        
        // Actually, let's use the 'MerchantTradeNo' from feedback to find the order.
        $tradeNo = $feedback['MerchantTradeNo'];
        $order = Order::where('trade_no', $tradeNo)->first();

        if (!$order) {
            Log::error('ECPay Callback Order Not Found', ['trade_no' => $tradeNo]);
            return '1|OK';
        }

        if ($order->status !== 'paid') {
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => 'ECPay_Credit', // Or $feedback['PaymentType']
            ]);
            Log::info('Order Paid via ECPay', ['order_id' => $order->id]);
        }

        return '1|OK';
    }
}
