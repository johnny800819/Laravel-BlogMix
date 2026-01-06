# 技術設計文件：BlogMix 現代化 (Technical Design)

## 1. 架構概觀 (Architecture Overview)

### 1.1 技術堆疊 (Tech Stack)
- **框架**：Laravel 11.x (PHP 8.2+)
- **前端**：Vue.js 3 (Composition API) + Tailwind CSS (透過 Laravel Vite 建置)
- **開發環境 (Development Environment)**：Docker (透過 Laravel Sail)，確保環境一致性。
- **資料庫**：
    - **預設建議**：**SQLite** (開發與測試極快，Laravel 11 預設，無需安裝 Server，檔案型資料庫)。
    - **生產環境選項**：若考量高併發或既有習慣，可無縫切換至 **MySQL 8.0** (InnoDB)。
    - *決策*：開發階段優先使用 SQLite 以加速 Vibe Coding 流程，上線前可視需求遷移至 MySQL。
- **狀態管理**：Pinia (用於購物車/使用者 Session 管理)
- **API**：RESTful API 用於前後端通訊。

### 1.3 開發者體驗 (Developer Experience)
為確保開發效率，專案根目錄將提供以下「一鍵腳本」：
- `start.bat`: 一鍵啟動 Docker 環境 (Laravel Sail)。
- `stop.bat`: 一鍵關閉 Docker 環境。
- *開發者無需記憶複雜 Docker 指令，只需執行此批次檔即可。*

### 1.4 Docker 容器命名策略 (Container Naming Strategy)
為方便管理與識別，Docker 容器**必須**顯式命名 (Explicit Naming)，不可使用 Docker 預設的隨機名稱。
- **格式**：`blogmix-[service]`
- **清單**：
    - App: `blogmix-app`
    - DB: `blogmix-mysql` (or `blogmix-sqlite`)
    - Cache: `blogmix-redis`
    - Mail: `blogmix-mailpit`

### 1.2 關鍵架構變更
- **模型標準化**：將 Models 從 `app/Http/Model` 移動至標準的 `app/Models` 目錄，符合現代 Laravel 最佳實踐。
- **資料庫文件化**：在 Migration 定義欄位時，**必須**使用 `->comment('...')` 方法寫入中文註解，讓資料庫結構本身即是文件。
- **價格邏輯修復**：明確地在 `articles` 表中新增 `price` 欄位（或若需擴充則建立獨立 `products` 表）。*在此階段，為了保留 "BlogMix" 概念，我們將在 `articles` 中新增 `price` 欄位，不再濫用 `art_view`。*
- **服務層 (Service Layer)**：將商業邏輯（訂單處理、購物車管理）從 Controller 抽離至 Services (`OrderService`, `CartService`)。

## 2. 資料庫結構提案 (Database Schema)

### 2.1 使用者與認證 (Users & Auth)
使用標準 Laravel `users` 表，並視需要擴充欄位。
- `id`, `name`, `email`, `password`, `role` (admin/member), `timestamps`.

### 2.2 內容與商務 (Content & Commerce)
**`categories`** (文章分類)
- `id`, `parent_id` (可為空，用於子分類), `name`, `sort_order` (排序), `status` (啟用/停用), `deleted_at` (軟刪除), `timestamps`.

**`articles`** (同時作為商品)
- `id`, `category_id`, `title`, `slug`, `content` (文字內容), `price` (小數點, **新增欄位**), `view_count`, `is_published`, `timestamps`.

### 2.3 訂單系統 (Order System)
**`orders`**
- `id`, `user_id`, `status` (pending, paid, shipped, cancelled), `total_amount`, `receiver_name`, `receiver_phone`, `shipping_address`, `payment_method`, `timestamps`.

**`order_items`**
- `id`, `order_id`, `article_id`, `quantity`, `price_at_purchase` (購買時單價), `timestamps`.

### 2.4 購物車 (Shopping Cart)
**`carts`**
- `id`, `user_id` (訪客購物車可為 null), `session_id`, `timestamps`.

**`cart_items`**
- `id`, `cart_id`, `article_id`, `quantity`, `timestamps`.

### 2.5 客戶服務 (Customer Service)
**`service_tickets`** (原名 `blog_service_list`)
- `id`, `user_id`, `subject`, `content`, `status` (open, replied, closed), `reply_content`, `timestamps`.

## 3. API 設計規劃 (API Design)

### 3.1 公開 API (Public)
- `GET /api/articles`: 取得文章列表 (篩選: 分類, 搜尋).
- `GET /api/articles/{slug}`: 取得文章詳情.

### 3.2 會員 API (Member)
- `GET /api/cart`: 取得目前購物車.
- `POST /api/cart/items`: 新增項目至購物車.
- `POST /api/orders`: 結帳/建立訂單.
- `GET /api/orders`: 訂單歷史紀錄.
- `POST /api/tickets`: 建立客服提問.

### 3.3 管理員 API (Admin)
- `GET /api/admin/dashboard`: 統計數據.
- `Resource /api/admin/articles`: 管理文章.
- `Resource /api/admin/orders`: 管理訂單.

## 4. 專案目錄結構 (Directory Structure) 📂

```text
v2/
├── app/                  # 核心程式碼 (Controllers, Models, Services)
├── config/               # 設定檔
├── database/             # Migrations, Seeds
├── public/               # Web Root (build 產物位於此)
│   └── build/            # Vite 編譯輸出
├── resources/            # 前端原始碼
│   ├── js/               # Vue 應用程式
│   ├── css/              # Tailwind/CSS
│   └── views/            # Blade Templates
├── routes/               # API & Web 路由
├── scripts/              # [NEW] 自動化與工具腳本
│   └── safe_build.ps1    # 安全建置腳本 (Surgical Copy)
├── storage/              # Logs, Uploads (Symlinked to public)
└── sync_build.bat        # Windows 快速建置入口 (呼叫 scripts/safe_build.ps1)
```
