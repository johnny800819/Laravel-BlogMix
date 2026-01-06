<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * 取得文章列表 (Get Article List)
     * 支援分頁、分類篩選、關鍵字搜尋
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // 預設查詢：已發佈且包含分類資訊
        $query = Article::published()->with('category');

        // 篩選：分類 ID
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 篩選：標題關鍵字搜尋
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 排序：最新優先，預設每頁 12 筆
        $articles = $query->latest()->paginate(12);

        return response()->json($articles);
    }

    /**
     * 取得單一文章詳情 (Get Article Detail)
     * 同時增加文章瀏覽次數 (View Count)
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        // 查詢：已發佈且包含分類資訊，找不到則回傳 404
        $article = Article::published()->with('category')->findOrFail($id);
        
        // 增加瀏覽次數
        $article->increment('view_count');

        return response()->json($article);
    }

    /**
     * 取得熱門文章排行榜 (Get Rank List)
     * 依照瀏覽次數降冪排序，取前 10 名
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ranklist()
    {
        $articles = Article::published()
            ->with('category')
            ->orderByDesc('view_count')  // 優先依照瀏覽次數排序
            ->orderByDesc('created_at')  // 次要依照建立時間排序 (避免同分)
            ->take(10)                   // 取前 10 筆
            ->get();

        return response()->json($articles);
    }
}
