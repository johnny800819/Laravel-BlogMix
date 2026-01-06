<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * 取得目前用戶的購物車 (Get Current Cart)
     * 若已登入：使用 User ID 查詢
     * 若未登入：使用 Header 或 Cookie 中的 Session ID 查詢
     *
     * @param Request $request
     * @return Cart|null
     */
    private function getCart(Request $request)
    {
        // 1. 已登入用戶優先
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        // 2. 訪客用戶：嘗試取得 Session ID
        $sessionId = $request->header('X-Session-ID') ?? $request->cookie('session_id');

        if (!$sessionId) {
            return null; // 無 Session 資訊，無法定位購物車
        }

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * 顯示購物車內容 (Show Cart)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        if (!$cart) {
            return response()->json(['items' => [], 'total' => 0]);
        }

        // 預先載入商品關聯資料
        $cart->load('items.article');

        return response()->json([
            'id' => $cart->id,
            'items' => $cart->items,
            'total' => $cart->total(), // 呼叫 Model 中的計算總額方法
        ]);
    }

    /**
     * 加入商品至購物車 (Add Item to Cart)
     * 若商品已存在則增加數量，否則新增項目
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'quantity' => 'integer|min:1',
        ]);

        // 確保取得或建立購物車 (Ensure Cart Exists)
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            // 對於訪客，必須提供 X-Session-ID
            $sessionId = $request->header('X-Session-ID');
            if (!$sessionId) {
                return response()->json(['message' => 'Please login to add to cart.'], 401);
            }
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        // 檢查購物車內是否已有該商品
        $item = $cart->items()->where('article_id', $request->article_id)->first();

        if ($item) {
            // 已存在：增加數量
            $item->increment('quantity', $request->quantity ?? 1);
        } else {
            // 不存在：新增項目
            $cart->items()->create([
                'article_id' => $request->article_id,
                'quantity' => $request->quantity ?? 1
            ]);
        }
        
        // 重新載入最新狀態回傳給前端
        $cart->load('items.article');
        
        return response()->json([
            'cart' => [
                'id' => $cart->id,
                'items' => $cart->items,
                'total' => $cart->total(),
            ]
        ], 201);
    }

    /**
     * 更新購物車項目數量 (Update Item Quantity)
     *
     * @param Request $request
     * @param int $itemId CartItem ID
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
             'quantity' => 'required|integer|min:1',
        ]);
        
        $cart = $this->getCart($request);
        
        if (!$cart) {
             return response()->json(['message' => 'Cart not found'], 404);
        }

        $item = $cart->items()->where('id', $itemId)->firstOrFail();
        $item->update(['quantity' => $request->quantity]);

        return response()->json($item);
    }

    /**
     * 移除購物車項目 (Remove Item)
     *
     * @param int $itemId CartItem ID
     */
    public function destroy(Request $request, $itemId)
    {
        $cart = $this->getCart($request);
        
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        
        $cart->items()->where('id', $itemId)->delete();

        return response()->json(['message' => 'Item removed']);
    }
}
