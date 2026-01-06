<?php

namespace App\Services;

use App\Models\Order;

use Illuminate\Support\Str;

class PaymentService
{
    /**
     * 產生綠界金流 (ECPay) 付款表單 HTML
     * Generate ECPay Payment Form HTML
     *
     * 此函式會：
     * 1. 準備金流所需參數 (MerchantID, TradeNo, Amount...)
     * 2. 計算檢查碼 (CheckMacValue) 以確保資料完整性
     * 3. 產生並回傳一個包含自動提交 JavaScript 的 HTML 表單
     *
     * @param Order $order 訂單物件
     * @return string HTML Form
     * @throws \Exception
     */
    public function generatePaymentForm(Order $order)
    {
        try {
            // 初始化 ECPay Factory，設定 HashKey 與 HashIV (用於加密)
            $factory = new \Ecpay\Sdk\Factories\Factory([
                'hashKey' => env('ECPAY_HASH_KEY'),
                'hashIv' => env('ECPAY_HASH_IV'),
                'hashMethod' => 'sha256', // 指定加密演算法
            ]);

            // 建立自動提交表單服務 (AutoSubmitFormWithCmvService)
            $autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');

            // 準備傳送給綠界的參數
            $input = [
                'MerchantID' => env('ECPAY_MERCHANT_ID'),
                'MerchantTradeNo' => 'BM' . time() . Str::random(4), // 特店交易編號 (必須唯一)
                'MerchantTradeDate' => date('Y/m/d H:i:s'),          // 交易時間
                'PaymentType' => 'aio',                              // 交易類型 (aio: All In One)
                'TotalAmount' => (int) $order->total_amount,         // 交易金額
                'TradeDesc' => "BlogMix Order #" . $order->id,       // 交易描述
                'ItemName' => $order->items->map(fn($item) => $item->article->title . ' x ' . $item->quantity)->implode('#'), // 商品名稱，用 # 分隔
                'ReturnURL' => url('/api/payment/callback'),         // 付款完成通知網址 (Server-to-Server)
                'ChoosePayment' => 'Credit',                         // 預設付款方式：信用卡
                'ClientBackURL' => url('/orders/' . $order->id),     // 付款完成後導回商店的網址 (Client redirect)
                'EncryptType' => 1,                                  // 加密類型
            ];

            // 更新訂單的交易編號 (TradeNo)，以便後續對帳
            $order->update(['trade_no' => $input['MerchantTradeNo']]);

            // 取得綠界金流 API URL
            $action = env('ECPAY_PAYMENT_URL');
            
            // 產生 HTML
            return $autoSubmitFormService->generate($input, $action);

        } catch (\Exception $e) {
            \Log::error('ECPay Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 驗證綠界回傳的資料 (Callback Verification)
     *
     * 驗證 CheckMacValue 是否正確，防止資料被篡改
     *
     * @param array $data 綠界 POST 過來的資料
     * @return array|array[] 如果驗證成功回傳資料，失敗回傳空陣列
     */
    public function validateCallback($data)
    {
        try {
            // 初始化 Factory
            $factory = new \Ecpay\Sdk\Factories\Factory([
                'hashKey' => env('ECPAY_HASH_KEY'),
                'hashIv' => env('ECPAY_HASH_IV'),
                'hashMethod' => 'sha256',
            ]);
            
            // 建立檢查碼驗證服務
            $checkMacValueService = $factory->create(\Ecpay\Sdk\Services\CheckMacValueService::class);
            
            // 驗證檢查碼 (Verify CheckMacValue)
            if ($checkMacValueService->verify($data)) {
                return $data; // 驗證成功，回傳原始資料
            }

            return []; // 驗證失敗，回傳空陣列

        } catch (\Exception $e) {
            \Log::error('ECPay Validation Error: ' . $e->getMessage());
            return [];
        }
    }
}
