<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceTicketController;
use App\Http\Controllers\Api\Admin\ServiceTicketController as AdminServiceTicketController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// --------------------------------------------------------------------------
// 公開路由 (Public Routes)
// 不需要權限驗證即可存取
// --------------------------------------------------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', function() { return response()->json(['message' => 'Unauthenticated'], 401); })->name('login');
Route::post('/forgot-password', [App\Http\Controllers\Api\PasswordResetLinkController::class, 'store']); // 發送重置信
Route::post('/reset-password', [App\Http\Controllers\Api\NewPasswordController::class, 'store']); // 執行重置
Route::get('/categories', [CategoryController::class, 'index']); // 取得分類列表
Route::get('/ranklist', [App\Http\Controllers\Api\ArticleController::class, 'ranklist']); // 取得排行榜
Route::get('/articles', [App\Http\Controllers\Api\ArticleController::class, 'index']); // 取得文章列表
Route::get('/articles/{id}', [App\Http\Controllers\Api\ArticleController::class, 'show']); // 取得文章詳情

// --------------------------------------------------------------------------
// 受保護的路由 (Protected Routes)
// 必須夾帶有效 Token (Sanctum Auth)
// --------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    // === 認證管理 (Auth Management) ===
    Route::post('/logout', [AuthController::class, 'logout']); // 登出
    Route::get('/me', [AuthController::class, 'me']);           // 取得目前使用者資料

    // === 購物車功能 (Cart Features) ===
    Route::get('/cart', [App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post('/cart', [App\Http\Controllers\Api\CartController::class, 'store']); // 加入購物車
    Route::post('/cart/items', [App\Http\Controllers\Api\CartController::class, 'store']); // 相容路徑
    Route::put('/cart/items/{itemId}', [App\Http\Controllers\Api\CartController::class, 'update']); // 更新數量
    Route::delete('/cart/items/{itemId}', [App\Http\Controllers\Api\CartController::class, 'destroy']); // 移除項目

    // === 使用者客服工單 (User Service Tickets) ===
    Route::apiResource('tickets', ServiceTicketController::class);
    Route::post('/tickets/{id}/replies', [ServiceTicketController::class, 'reply']);

    // === 訂單功能 (Order Workflow) ===
    Route::post('/orders', [App\Http\Controllers\Api\OrderController::class, 'store']);       // 建立訂單
    Route::get('/orders', [App\Http\Controllers\Api\OrderController::class, 'index']);        // 訂單列表
    Route::get('/orders/{id}', [App\Http\Controllers\Api\OrderController::class, 'show']);    // 訂單詳情
    Route::post('/orders/{id}/pay', [App\Http\Controllers\Api\OrderController::class, 'pay']); // 發起付款

    // ----------------------------------------------------------------------
    // 管理員後台路由 (Admin Routes)
    // 必須具備 Admin 權限 (is_admin middleware)
    // ----------------------------------------------------------------------
    Route::prefix('admin')->middleware(['is_admin'])->group(function () {
        // 客服工單管理
        Route::get('/tickets', [AdminServiceTicketController::class, 'index']);
        Route::get('/tickets/{id}', [AdminServiceTicketController::class, 'show']);
        Route::put('/tickets/{id}/reply', [AdminServiceTicketController::class, 'reply']);
        Route::patch('/tickets/{id}/close', [AdminServiceTicketController::class, 'close']);

        // 分類管理
        Route::apiResource('categories', AdminCategoryController::class);
        
        // 營運儀表板
        Route::get('/dashboard/stats', [App\Http\Controllers\Api\Admin\DashboardController::class, 'stats']);

        // 文章管理 (CRUD)
        Route::apiResource('articles', App\Http\Controllers\Api\Admin\ArticleController::class);

        // 訂單管理 (出貨/狀態更新)
        Route::get('/orders', [App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
        Route::get('/orders/{id}', [App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
        Route::put('/orders/{id}', [App\Http\Controllers\Api\Admin\OrderController::class, 'update']);
    });
});

// --------------------------------------------------------------------------
// 第三方回調 (Third-party Callbacks)
// --------------------------------------------------------------------------
// 綠界金流付款結果通知 (Public access required)
Route::post('/payment/callback', [App\Http\Controllers\Api\PaymentController::class, 'callback']);

// --------------------------------------------------------------------------
// 測試用路由 (Testing & Mocking)
// 模擬綠界金流環境 (非生產環境使用)
// --------------------------------------------------------------------------
Route::group(['prefix' => 'mock'], function () {
    Route::post('/ecpay', [App\Http\Controllers\Api\MockEcpayController::class, 'checkout']);
    Route::post('/ecpay/callback', [App\Http\Controllers\Api\MockEcpayController::class, 'callback']);
});
