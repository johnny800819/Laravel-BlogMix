# 後端開發與驗證工作流程 (Backend Development & Verification Workflow)

> 💡 **AI 協作提示**：關於環境限制與指令規範，請參閱 [`Project_Handbook.md`](./Project_Handbook.md) 的核心守則。

## 限制總覽 (Constraint Overview)
- **環境**: Windows Server 2022 (Host) + WSL2 Docker (Container).
- **連線**: 透過 SSH (`feb16@10.13.1.20`) 進入 Host，再透過 `docker exec` 進入容器。
- **資料庫**: MySQL 運行於 Docker 中，外部工具可能難以直接連線，主要依賴 CLI。

## 標準作業程序 (SOP)

### 1. 程式碼編輯 (Code Editing)
- **方式**: 直接編輯網路磁碟機 (`V:\PHP\Laravel-BlogMix-master\v2`) 上的 PHP 檔案。
- **生效**: 存檔後即時生效 (Container 掛載 Host 目錄)。

### 2. 指令執行 (Command Execution)
- **進入點**: 透過 SSH 連線至 Host。
- **執行方式**:
  ```bash
  # 範例：執行 Artisan 指令
  wsl docker compose -f /path/to/compose.yaml exec laravel.test php artisan <command>
  ```
- **常用指令**:
  - `php artisan migrate`: 資料庫遷移。
  - `php artisan tinker`: 互動式 Shell (注意：PowerShell 下引號處理較複雜)。
  - `php artisan route:list`: 檢查路由。

### 3. 資料庫驗證 (Database Verification)
- **已知問題**: 使用 `php artisan tinker` 透過 SSH 執行複雜查詢時，常因 PowerShell 引號 (`"`, `'`) 或特殊字元 (`$`) 導致語法錯誤或輸出亂碼。
- **建議模式 (The Working Pattern)**:
  1.  在 `v2/dev_scripts/` (或根目錄) 建立專用 PHP 腳本 (如 `verify_order.php`)。
  2.  腳本內容包含完整的 Eloquent 查詢與清晰的 `echo` 輸出。
  3.  透過 SSH 執行該腳本：
      ```bash
      ... exec laravel.test php dev_scripts/verify_order.php
      ```
  4.  驗證後可刪除或保留於 `dev_scripts` 目錄供日後使用。

### 4. 模擬開發 (Mocking Strategy)
- **場景**: 第三方服務 (如 ECPay) 無法在離線/內網環境測試。
- **模式**: **Config-Driven Mocking**。
  - **Controller**: 建立 `MockEcpayController` 模擬第三方頁面與 Callback。
  - **Config**: 使用 `.env` 中的 `ECPAY_BASE_URL` 切換真實/模擬環境。
  - **驗證**: 瀏覽器導向 Mock 頁面 -> 手動觸發模擬成功 -> 驗證 Backend 狀態更新。

## 故障排除 (Troubleshooting)
- **500 Internal Server Error**: 優先檢查 `storage/logs/laravel.log`。
- **SQL Error**: 確認 Migration 是否已執行 (`php artisan migrate:status`)。
- **Frontend Assets**: 若頁面樣式遺失，請參閱 `Frontend_Workflow.md` 執行構建與同步。

## 開發與驗證規範 (Development & Verification Standards)

### 1. 目錄結構 (Directory Structure)
- **`tests/`**: 存放正規的自動化測試 (PHPUnit)。適用於長期維護、CI/CD 流程的測試案例。
- **`dev_scripts/`**:存放臨時性、環境特定的驗證腳本 (如 `verify_fix.php`)。
  - **原則**: **建議分開**。`dev_scripts` 允許我們在受限環境下快速驗證，而不污染正規測試庫。
  - **管理**: 定期清理不再需要的腳本，或將有價值的邏輯重構入 `tests/`。

### 2. 資料品質 (Data Quality)
- **原則**: **Realistic Mock Data**。
- **要求**: 建立假資料 (Seeder/Tests) 時，必須使用真實世界的格式。
  - ❌ 禁止: `Test User`, `asdf`, `123456`
  - ✅ 建議: `Lisa Chen`, `John Smith`, `0912-345-678`

### 3. 代理人操作規範 (Agent Operational Protocols)
- **瀏覽器效能**:
  - **分頁限制**: 同時開啟分頁不得超過 **5** 頁。
  - **主動關閉**: 完成任務後，或分頁過多時，必須主動關閉不再使用的分頁 (`close_browser_page`)。
- **檔案維護**:
  - **截圖管理**: 任務完成後，若截圖已嵌入文件 (如 `walkthrough.md`) 則保留；未使用的中間過程截圖應予以刪除，避免占用空間。
