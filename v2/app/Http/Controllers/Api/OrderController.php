<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * 取得使用者訂單列表 (Get User Orders)
     * 支援分頁與日期範圍篩選
     */
    public function index(Request $request)
    {
        // 查詢當前登入使用者的訂單，並預先載入商品詳情
        $query = Auth::user()->orders()
            ->with('items.article');

        // 日期範圍篩選 (Start Date)
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        // 日期範圍篩選 (End Date)
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 排序：最新優先，支援自動分頁
        $orders = $query->latest()
            ->paginate($request->get('per_page', 10));
            
        return response()->json($orders);
    }

    /**
     * 取得單一訂單詳情 (Get Order Detail)
     */
    public function show($id)
    {
        // 確保只能查詢自己的訂單
        $order = Auth::user()->orders()
            ->with('items.article')
            ->findOrFail($id);
            
        return response()->json($order);
    }

    /**
     * 建立新訂單 (Create New Order)
     * 包含購物車結帳邏輯、庫存檢查(如需)、快照價格
     */
    public function store(Request $request)
    {
        // 驗證輸入資料
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        
        // 取得使用者購物車
        $cart = Cart::where('user_id', $user->id)->first();

        // 檢查購物車是否為空
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => '購物車是空的'], 400);
        }

        // 使用資料庫交易 (Transaction) 確保資料一致性
        return DB::transaction(function () use ($user, $cart, $request) {
            // 1. 建立訂單主檔 (Order)
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending', // 初始狀態：待付款
                'total_amount' => $cart->total(),
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
            ]);

            // 2. 將購物車項目轉為訂單明細 (Order Items)
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'article_id' => $cartItem->article_id,
                    'quantity' => $cartItem->quantity,
                    'price_at_purchase' => $cartItem->article->price, // 重要：快照當下價格 (Snapshot Price)
                ]);
            }

            // 3. 清空購物車 (Clear Cart)
            $cart->items()->delete();
            $cart->delete();

            return response()->json($order->load('items'), 201);
        });
    }

    /**
     * 發起付款 (Initiate Payment)
     * 產生 ECPay 若接金流所需的 HTML 表單
     */
    public function pay(\App\Services\PaymentService $paymentService, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->status === 'paid') {
             return response()->json(['message' => '訂單已付款'], 400);
        }

        // 產生 ECPay 表單 HTML (Generate Form)
        // 此 Service 會負責處理綠界所需的 CheckMacValue 與參數加密
        $html = $paymentService->generatePaymentForm($order);
        
        return response($html);
    }
}
