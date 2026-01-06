<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 取得後台訂單列表 (Admin Order List)
     * 支援狀態篩選、多欄位搜尋
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.article']);

        // 狀態篩選 (Status Filter)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 搜尋邏輯 (Search Logic)
        if ($request->filled('search')) {
            $field = $request->get('search_field', 'id'); // 預設搜尋 ID
            $term = $request->search;

            if ($field === 'id') {
                $query->where('id', $term);  // ID 精確搜尋
            } elseif ($field === 'receiver_name') {
                 $query->where('receiver_name', 'like', "%{$term}%"); // 收件人姓名模糊搜尋
            } elseif ($field === 'receiver_email') {
                 $query->where('receiver_email', 'like', "%{$term}%"); // Email 模糊搜尋
            } else {
                 $query->where('id', $term);
            }
        }

        // 排序設定 (Sorting)
        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_direction', 'asc'));
        } else {
            $query->latest();
        }

        $limit = $request->get('per_page', 10);
        $orders = $query->paginate($limit);
            
        return response()->json($orders);
    }

    /**
     * 取得單一訂單詳情 (Order Detail)
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.article'])
            ->findOrFail($id);
            
        return response()->json($order);
    }

    /**
     * 更新訂單狀態 (Update Order Status)
     * e.g., Set to shipped, completed, cancelled
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled,completed',
            'payment_method' => 'nullable|string'
        ]);

        $order->update($validated);

        return response()->json($order);
    }
}
