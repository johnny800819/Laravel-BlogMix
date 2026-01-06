<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceTicketController extends Controller
{
    /**
     * 取得我的工單列表 (My Tickets)
     * 僅回傳當前登入使用者的工單
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tickets = Auth::user()->serviceTickets()->latest()->paginate(10);
        return response()->json($tickets);
    }

    /**
     * 建立新工單 (Create Ticket)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $ticket = Auth::user()->serviceTickets()->create($validated);

        return response()->json($ticket, 201);
    }

    /**
     * 取得工單詳情 (Ticket Detail)
     * 包含分類與回覆紀錄
     */
    public function show(string $id)
    {
        $ticket = Auth::user()->serviceTickets()->with(['category', 'replies.user'])->findOrFail($id);
        return response()->json($ticket);
    }

    /**
     * 使用者回覆工單 (User Reply)
     */
    public function reply(Request $request, string $id)
    {
        $ticket = Auth::user()->serviceTickets()->findOrFail($id);
        
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $reply = $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message']
        ]);

        return response()->json($reply, 201);
    }
}
