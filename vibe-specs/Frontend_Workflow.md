# 前端開發與構建工作流程 (Frontend Development & Build Workflow)

> 💡 **AI 協作提示**：詳細的 PowerShell 自動化建置腳本，請參閱 [`Project_Handbook.md`](./Project_Handbook.md)。

## 限制總覽 (Constraint Overview)
- **伺服器環境**: 離線 Windows Server 2022 (無網際網路存取)。
- **容器**: 運作於 WSL2 Docker 中的 `laravel.test` (Linux)。
- **問題**: 由於缺乏網路連線，無法在伺服器/容器內執行 `npm install`。若依賴套件遺失或平台不符，`npm run build` 也會失敗。

## 標準作業程序 (SOP)

基於上述限制，**前端編譯必須在本地端 (Local) 進行**。

### 1. 開發 (本地端 Local Machine)
- **編輯程式碼**: 直接在對應的網路磁碟機 (`V:`) 上編輯 `.vue` 和 `.js` 檔案。
- **本地構建 (Build Locally)**:
  > ⚠️ **警告**: 切勿直接複製整個專案目錄！`public/storage` (Symlink) 會導致複製工具 (如 robocopy) 鎖死 (Error 1920)。
  1.  請使用 **「外科手術式複製 (Surgical Copy)」**：僅複製 `package.json`, 設定檔與 `resources` 目錄。
  2.  詳細 PowerShell 腳本請務必參閱 [`Project_Handbook.md`](./Project_Handbook.md#22-powershell-自動化腳本-auto-script)。
  3.  執行 `npm install` 與 `npm run build`。

### 2. 部署 (同步至伺服器 Sync to Server)
- **同步產物**: 將產生的 `public/build` 目錄複製回網路磁碟機 (`V:\PHP\Laravel-BlogMix-master\v2\public\build`)。
- **工具**: 視需要使用 `xcopy` 或批次腳本來繞過工具與權限限制。
- **切勿 (Do NOT)**: 嘗試透過 SSH 在伺服器上執行 `npm` 指令。

### 3. 驗證 (Verification)
- 重新整理瀏覽器 (`http://10.13.1.20:8081`)。
- 透過視覺或控制台 (Console) 驗證變更。

---
**注意**: 後端 (PHP/Laravel) 指令 *可以* 也 *應該* 透過 SSH (`php artisan ...`) 執行，因為它們在容器內執行，且通常不需要外部網路存取執行邏輯 (除非是拉取 composer 套件，這同樣會受到限制)。
