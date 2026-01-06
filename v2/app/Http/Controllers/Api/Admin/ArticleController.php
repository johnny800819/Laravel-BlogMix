<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * 取得後台文章列表 (Admin Article List)
     * 支援多欄位搜尋與自訂排序
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // 預載分類與訂單項目數量 (用於判斷熱門度或依賴)
        $query = Article::with('category')->withCount('orderItems');

        // 搜尋邏輯 (Search Logic)
        if ($request->filled('search')) {
            $field = $request->get('search_field');
            $term = $request->search;
            
            // 根據指定欄位進行搜尋
            if ($field && in_array($field, ['title', 'id', 'price'])) {
                // ID 使用精確搜尋，其他欄位使用模糊搜尋
                if ($field === 'id') {
                    $query->where($field, $term);
                } else {
                    $query->where($field, 'like', "%{$term}%");
                }
            } else {
                // 預設搜尋：標題模糊搜尋
                $query->where('title', 'like', "%{$term}%");
            }
        }

        // 排序邏輯 (Sorting)
        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_direction', 'asc'));
        } else {
            $query->latest(); // 預設：最新優先
        }

        $limit = $request->get('per_page', 10);
        $articles = $query->paginate($limit);
        return response()->json($articles);
    }

    /**
     * 新增文章 (Create Article)
     * 處理基本資料與圖片上傳
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * 新增文章 (Store Article)
     * 
     * 主要處理：
     * 1. 驗證輸入資料 (標題、內容、分類、價格等)。
     * 2. XSS 防護：對內容進行 HTML 清洗。
     * 3. 圖片上傳：儲存至 public disk 並記錄路徑。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
            'image' => 'nullable|image|max:2048', // 限制 2MB
        ]);

        // XSS Sanitization
        $validated['content'] = $this->cleanHtml($validated['content']);

        // 處理圖片上傳 (Handle Image Upload)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = $path;
        }

        $article = Article::create($validated);

        return response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    /**
     * 更新文章 (Update Article)
     * 
     * 主要處理：
     * 1. 驗證欄位：允許部分更新 (Nullable Check)。
     * 2. XSS 防護：再次清洗 HTML 內容。
     * 3. 圖片邏輯：
     *    - 上傳新圖：刪除舊圖 -> 儲存新圖。
     *    - 移除圖片：明確刪除舊圖並設為 null。
     *    - 保留原圖：若未上傳且未移除，則不動。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'category_id' => 'exists:categories,id',
            'price' => 'numeric|min:0',
            'is_published' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        // XSS Sanitization
        if (isset($validated['content'])) {
            $validated['content'] = $this->cleanHtml($validated['content']);
        }

        // 圖片處理邏輯 (Image Handling)
        if ($request->hasFile('image')) {
            // 若有舊圖，先刪除
            if ($article->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($article->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($article->image_path);
            }
            
            // 上傳新圖
            $path = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = $path;
        } elseif ($request->boolean('remove_image')) {
             // 明確移除圖片 (Explicit Removal)
             if ($article->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($article->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($article->image_path);
            }
            $validated['image_path'] = null;
        }

        $article->update($validated);

        return response()->json($article);
    }

    /**
     * 清洗 HTML 內容 (Clean HTML Content)
     * 防範 XSS 攻擊 (Cross-Site Scripting)
     * 
     * 策略：
     * 使用 strip_tags 僅允許白名單內的標籤，過濾掉 potentially dangerous tags (如 script, iframe, object 等)。
     * 注意：這是一個基本的後端防護，前端顯示時仍建議配合 DOMPurify 使用。
     * 
     * @param string $content
     * @return string
     */
    private function cleanHtml($content)
    {
        // Allowed tags whitelist
        $allowed_tags = '<h1><h2><h3><h4><h5><h6><p><br><b><i><u><strong><em><ul><ol><li><a><img><span><div><blockquote>';
        return strip_tags($content, $allowed_tags);
    }

    /**
     * 刪除文章 (Delete Article)
     * 
     * 防呆機制 (Safety Check)：
     * 在刪除前檢查 `order_items` 表。若該商品(文章)已被其購買過(存在於訂單中)，
     * 則禁止刪除，以確保歷史訂單資料的完整性 (Foreign Key Integrity / Data Consistency)。
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            $article = Article::findOrFail($id);

            // 檢查是否已存在訂單關聯 (Check Dependencies)
            // 若商品已被購買，禁止刪除以保護歷史訂單完整性
            if (\Illuminate\Support\Facades\DB::table('order_items')->where('article_id', $id)->exists()) {
                return response()->json(['message' => '無法刪除：此文章（商品）已有相關訂單紀錄。'], 409);
            }

            $article->delete();
            return response()->json(['message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete article: ' . $e->getMessage()], 500);
        }
    }
}
