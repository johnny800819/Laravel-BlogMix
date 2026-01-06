<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * 取得後台分類列表 (Admin Category List)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::orderBy('sort_order')->paginate(20);
        return response()->json($categories);
    }

    /**
     * 新增分類 (Create Category)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'integer',
            'status' => 'boolean',
        ]);

        $category = Category::create($validated);
        return response()->json($category, 201);
    }

    /**
     * 取得單一分類詳情 (Category Detail)
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * 更新分類 (Update Category)
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'integer',
            'status' => 'boolean',
        ]);

        $category->update($validated);
        return response()->json($category);
    }

    /**
     * 刪除分類 (Delete Category)
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
