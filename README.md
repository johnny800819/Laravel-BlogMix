# Laravel BlogMix Project

**BlogMix** 是一個全端電商與部落格平台的專案，展示相關技術與成果。

---

## 📂 專案結構 (Project Structure)

本儲存庫採用 Monorepo 結構，完整保存了專案的演進歷史：

### **[v2/ (2026年重構版本)](./v2)**
> **🚀 現代化全端應用 (Full-Stack App)**
- **技術堆疊**: Laravel 11 (PHP 8.2+), Vue 3 (Composition API), Vite, Tailwind CSS, Pinia.
- **架構模式**: 前後端分離 (SPA), 模組化 CSS, 外科手術式建置 (Surgical Build).
- **關鍵功能**:
    - **🛍️ 完整電商購物流程**: 購物車、訂單管理、**綠界金流 (ECPay)** 整合。
    - **📝 內容管理系統 (CMS)**: 支援所見即所得 (WYSIWYG) 編輯器的文章發布系統。
    - **🎫 客服工單系統**: 用戶與管理員的雙向溝通管道。
    - **🎨 現代化 UI/UX**: 玻璃擬態 (Glassmorphism) 設計風格、深色模式 (Dark Mode) 支援。
    - **🛡️ 企業級架構**: 具備全域錯誤處理機制 (Global Error Handling)。
- **專案狀態**: **維護中**。
- **相關文件**: 詳細安裝與架構說明請參閱 [v2/README.md](./v2/README.md)。

### **[v1/ (舊版封存)](./v1)**
> **🏛️ 歷史參考資料 (Legacy Reference)**
- **技術堆疊**: Laravel 5.2 (PHP 5.6/7.0), jQuery, Bootstrap 3 (Sass).
- **架構模式**: 傳統 MVC 架構 (Server-Side Rendering) 搭配 Blade 樣板引擎。
- **關鍵功能**:
    - **基礎部落格**: 簡單的文章 CRUD 與分類管理 (CategoryMain/Sub)。
    - **傳統購物車**: 基於 Session 的購物車實作 (無 API)。
    - **手動整合功能**:
        - 手動實作 `admin.login` Middleware。
        - 閉包路由 (Closure Route) 模擬 Storage Link。
        - Google reCAPTCHA v2 手動驗證流程。
- **專案狀態**: **已封存**。
- **備註說明**: 保留此版本是為了：
    1.  對照重構前後的架構差異 (SSR vs SPA)。
    2.  查詢原始商業邏輯與資料庫設計 (如 `blog_user` 表結構)。

---

## 📜 歷史沿革 (History)

- **2016-2017**: 專案初次開發，建立基礎部落格與購物功能。
- **2025/2026**: 啟動 **Project Revival (v2)** 計畫。
    - 目標是引入「現代化前端體驗 (SPA)」與「強型別後端架構」。
    - 在保留原始業務邏輯 (Business Logic) 的前提下，徹底重寫了每一行程式碼。
