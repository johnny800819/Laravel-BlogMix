# 專案憲法：BlogMix (Constitution)

## 1. 核心原則 (Core Principles)
1.  **現代化標準**：使用目前穩定版本的 Laravel 和 Vue。避免使用已棄用的模式。
2.  **型別安全**：盡可能使用 PHP Type Hinting 和 TypeScript Interfaces。
3.  **關注點分離 (Separation of Concerns)**：Controller 應僅負責處理 HTTP 請求。商業邏輯應歸類於 Services 或 Actions。
4.  **Vibe Coding 友善**：程式碼應具備自我描述性 (Self-documenting)。註解應解釋「為什麼 (Why)」，而非「做什麼 (What)」。
5.  **語言規範**：**所有註解 (Comments) 必須使用繁體中文**。這是一條鐵律。

## 2. 編碼規範 (Coding Standards)

### 2.1 後端 (Laravel)
- **命名慣例**：
    - Models: PascalCase, 單數 (例如 `Article`, `Order`)。
    - Tables: snake_case, 複數 (例如 `articles`, `orders`)。
    - Controllers: `TopicController`。
- **程式碼風格**：遵循 PSR-12 標準。
- **Service 模式**：
    - 複雜邏輯（例如：結帳流程）**必須**使用 Service (`CheckoutService`)。
- **Eloquent 模型**：
    - 一律使用 `InnoDB` 引擎 (若使用 MySQL)。
    - 使用 Mass Assignment 保護 (`$fillable`)。
    - 使用 API Resources 來處理 JSON 回應。
    - **資料庫文件化**：Migration 中必須使用 `$table->string('col')->comment('中文欄位說明')` 為每個欄位加上註解。

### 2.2 前端 (Vue 3)
- **Composition API**：使用 `<script setup>` 語法糖。
- **命名慣例**：
    - Components: PascalCase (例如 `ProductCard.vue`)。
    - Props/Events: camelCase。
- **CSS**：
    - 優先使用 Tailwind Utility classes。
    - **自定義 CSS**：允許使用，但必須良好管理（例如 Scoped CSS 或獨立 SCSS 模組），嚴禁雜亂無章的堆疊 (疊床架屋)。
- **美學設計 (Aesthetics)**：
    - 參考舊版截圖 (Screenshots) 的版面配置，但**必須**進行現代化美感升級。
    - 設計應追求「優雅」、「美麗」，避免過時或「醜陋」的 UI。使用陰影、圓角、適當的留白與現代配色。

## 3. 工作流規則 (Workflow Rules)
- **Commits**：使用原子化提交 (Atomic commits) 並附上清晰訊息 (遵循 Conventional Commits)。
- **文件**：在程式碼變更偏離規格前，必須先更新規格書 (`project_specification.md`)。
- **語言**：根據使用者規則，核心文件與註解必須使用 **繁體中文 (Traditional Chinese)**。

## 4. 特定的 "BlogMix" 規則
- **價格欄位**：所有新實作**必須**引用 `price` 欄位，**嚴禁**使用 `art_view` 作為價格（修復舊版 Bug）。

## 5. 基礎設施規範 (Infrastructure Standards)
- **Docker 容器命名**：嚴禁使用預設隨機命名。所有容器名稱必須遵循 `[專案名]-[服務名]` 格式。
    - 範例：`blogmix-app`, `blogmix-mysql`, `blogmix-redis`。
    - 實作方式：於 `docker-compose.yml` 中顯式定義 `container_name` 屬性。

## 6. AI 協作與參考文件 (AI Collaboration)
- **專案手冊**：關於開發環境、編譯架構 (Temp Build) 與常見問題，請參閱 [`Project_Handbook.md`](./Project_Handbook.md)。
- **變更管理**：任何架構變更或新功能增刪，務必同步更新本目錄下的規格文件。
