<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;

class ServiceTicketController extends Controller
{
    /**
     * 取得客服工單列表 (Admin Ticket List)
     * 支援狀態、分類篩選與複合搜尋
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // 在正式環境應確保僅管理員可存取 (Ensure Admin Access)
        $query = ServiceTicket::with('user', 'category');

        // 狀態篩選 (Status Filter)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 分類篩選 (Category Filter)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 搜尋邏輯 (Search Logic)
        if ($request->filled('search')) {
            $field = $request->get('search_field');
            $search = $request->search;

            if ($field === 'id') {
                $query->where('id', $search);  // ID 精確搜尋
            } elseif ($field === 'subject') {
                $query->where('subject', 'like', "%{$search}%"); // 主題模糊搜尋
            } else {
                // 優化：智慧判斷搜尋類型 (Smart Search)
                $query->where(function($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%");
                    
                    // 僅當輸入為數字時才搜尋 ID (避免對 ID 欄位進行 Full Scan)
                    if (is_numeric($search)) {
                        $q->orWhere('id', $search);
                    }
                });
            }
        }

        // 排序設定 (Sorting)
        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_direction', 'asc'));
        } else {
            $query->latest(); // 預設最新優先
        }

        $limit = $request->get('per_page', 10);
        $tickets = $query->paginate($limit);
        return response()->json($tickets);
    }

    /**
     * 取得單一工單詳情 (Get Ticket Detail)
     * 包含回覆紀錄與相關使用者資訊
     */
    public function show($id)
    {
        $ticket = ServiceTicket::withoutGlobalScopes()->with(['user', 'category', 'replies.user'])->findOrFail($id);
        return response()->json($ticket);
    }

    /**
     * 回覆工單 (Reply to Ticket)
     * 管理員回覆後，將狀態更新為 'replied'
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reply(Request $request, string $id)
    {
        $ticket = ServiceTicket::findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // 建立回覆紀錄 (Create Reply)
        $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        // 更新工單狀態為已回覆 (Status -> Replied)
        $ticket->update(['status' => 'replied']);

        return response()->json($ticket->load('replies.user'));
    }
    
    /**
     * 結案工單 (Close Ticket)
     * 將狀態設為 'closed'
     */
    public function close(string $id)
    {
        $ticket = ServiceTicket::findOrFail($id);
        $ticket->update(['status' => 'closed']);
        return response()->json($ticket);
    }
}
