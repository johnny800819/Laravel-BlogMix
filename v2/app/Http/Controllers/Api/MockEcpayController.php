<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MockEcpayController extends Controller
{
    /**
     * 顯示模擬綠界付款頁面 (Show Mock ECPay Payment Page)
     * 這是一個僅供開發測試用的模擬頁面，用來代替真實的綠界轉導頁面。
     * 它會接收 OrderController 傳來的參數，並生成一個包含 Auto-Submit 表單的 HTML。
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        // 模擬接收來自商店端的表單參數
        $merchantTradeNo = $request->input('MerchantTradeNo');
        $totalAmount = $request->input('TotalAmount');
        $itemName = $request->input('ItemName');
        $returnUrl = $request->input('ReturnURL'); // 付款完成後的 Server Callback URL
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock ECPay Payment</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .payment-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .header { text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #00cca1; padding-bottom: 1rem; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #00cca1; }
        .amount { font-size: 2rem; font-weight: bold; text-align: center; margin: 1rem 0; color: #333; }
        .item-info { background: #f9f9f9; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.9rem; color: #666; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #333; }
        input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 1rem; background-color: #00cca1; color: white; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #00b386; }
        .secure-badge { text-align: center; margin-top: 1rem; font-size: 0.8rem; color: #888; }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="header">
            <div class="logo">ECPay Mock</div>
            <div>Secure Payment Gateway (Simulation)</div>
        </div>
        
        <div class="item-info">
            <p><strong>Merchant Trade No:</strong> {$merchantTradeNo}</p>
            <p><strong>Item:</strong> {$itemName}</p>
        </div>

        <div class="amount">NT$ {$totalAmount}</div>

        <!-- 模擬付款表單：直接 Post 回 Mock Callback -->
        <form action="/api/mock/ecpay/callback" method="POST">
            <input type="hidden" name="MerchantTradeNo" value="{$merchantTradeNo}">
            <input type="hidden" name="RtnCode" value="1"> <!-- 1 = 成功 -->
            <input type="hidden" name="RtnMsg" value="Succeeded">
            <input type="hidden" name="TradeAmt" value="{$totalAmount}">
            <input type="hidden" name="PaymentDate" value="2025/12/24 12:00:00">
            <input type="hidden" name="SimulatePaid" value="1">
            
            <div class="form-group">
                <label>Credit Card Number</label>
                <input type="text" value="4311-9522-2222-2222" disabled style="background: #eee;">
            </div>
            
            <button type="submit">Simulate Payment Success / 模擬付款成功</button>
        </form>
        
        <div class="secure-badge">🔒 This is a simulation environment</div>
    </div>
</body>
</html>
HTML;
        return response($html);
    }

    /**
     * 處理模擬回調 (Handle Mock Callback)
     * 這是一個複合方法，同時扮演了 "綠界伺服器通知後端" 與 "引導使用者回商店" 的角色。
     * 
     * 正常流程：
     * 1. CheckMacValue 驗證 (在此省略)
     * 2. 更新訂單狀態
     * 3. 顯示成功頁面或導回商店
     */
    public function callback(Request $request)
    {
        // 1. 模擬綠界通知後端 (Server-to-Server)
        // 在真實情況下，綠界會發送 POST 到 /api/payment/callback
        // 這裡我們直接操作資料庫來模擬這個結果
        
        $merchantTradeNo = $request->input('MerchantTradeNo');
        $rtnCode = $request->input('RtnCode'); // 1 = 成功
        
        // 根據 TradeNo 查找訂單
        $order = Order::where('trade_no', $merchantTradeNo)->first();
            
        if ($order) {
            if ($rtnCode == '1') {
                $order->status = 'paid';
                $order->paid_at = Carbon::now();
                $order->save();
                Log::info("Mock ECPay: Order {$order->id} marked as PAID.");
            }
        } else {
             Log::error("Mock ECPay: Order not found for TradeNo: {$merchantTradeNo}");
        }

        // 2. 將使用者導回客戶端商店 (Client Redirect)
        // 假設前端運行在 8081 port
        return redirect('http://10.13.1.20:8081/'); 
    }
}
