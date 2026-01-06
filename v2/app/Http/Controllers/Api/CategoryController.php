<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * 取得分類列表 (Get Category List)
     * 僅回傳狀態啟用且為主分類 (無 parent_id) 的項目，並包含子分類
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::where('status', true)
            ->whereNull('parent_id') // 僅抓取根分類
            ->with('children')       // 預載子分類
            ->orderBy('sort_order')
            ->get();
            
        return response()->json($categories);
    }
}
