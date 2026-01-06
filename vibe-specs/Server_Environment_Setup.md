# Windows Server 2022 離線開發環境建置指南 (WSL 2 + Docker + SSH + SMB)

這是一份針對 **完全離線 (Offline)** 的 Windows Server 2022 環境建置手冊。

## 🤖 給 AI 的交接協定 (AI Protocol)
**當您開啟新的 Antigravity 視窗並讀取此文件時，請嚴格遵守以下規則：**

> 💡 **快速上手**：關於日常開發指令與編譯流程，請優先參閱 [`Project_Handbook.md`](./Project_Handbook.md)。本文件主要用於環境建置與除錯。
1.  **模式識別**：此環境為 **Hybrid SMB + SSH**。
    *   **檔案系統**：您看到的檔案位於 **SMB 網路磁碟機** (如 `V:`)，可直接讀寫。
    *   **執行環境**：您**無法**在本機執行 `docker` 或 `php`。
2.  **指令封裝**：所有終端機指令**必須**透過 SSH 發送到 Server 執行。
    *   ❌ 錯誤：`docker compose up -d`
    *   ✅ 正確：`ssh feb16@10.13.1.20 "wsl docker compose -f /mnt/c/Users/Administrator/Desktop/專案程式/PHP/Laravel-BlogMix-master/v2/compose.yaml up -d"`
    *   *(注意：各種路徑請根據實際狀況調整，Server 端的 C 槽對應 `/mnt/c/`)*
3.  **禁止事項**：
    *   不要嘗試下載 vscode-server。
    *   不要嘗試在本機安裝 PHP/Node.js (除非 User 明確要求)。

---

## 🏗️ 系統架構與角色圖解

```mermaid
graph TD
    Client[Client PC<br>(Antigravity / VS Code)]

    subgraph "Windows Server 2022 (Host)"
        WinSMB[SMB Service<br>(Port 445)]
        WinSSH[OpenSSH Server<br>(Port 22)]
        
        subgraph "WSL 2 (Virtualization Layer)"
            Ubuntu[Ubuntu 24.04 (Distro)]
            
            subgraph "Docker Environment"
                DockerEng[Docker Engine]
                
                subgraph "Container Workloads"
                    App[Laravel App]
                    DB[MySQL 8.0]
                    Cache[Redis]
                    Mgmt[Portainer]
                end
            end
        end
    end

    %% 連線流向
    Client -->|SSH 指令控制| WinSSH
    Client -->|SMB 檔案編輯| WinSMB
    
    %% 內部依賴
    WinSMB -.->|V: 槽映射| Ubuntu
    WinSSH -.->|wsl 指令轉發| Ubuntu
    Ubuntu --> DockerEng
    DockerEng --> App
    DockerEng --> DB
    DockerEng --> Cache
    DockerEng --> Mgmt
```

### 角色說明
*   **Client PC**：您的開發機，負責寫 Code (透過網路磁碟機) 和發送指令 (透過 SSH)。
*   **Windows Server 2022**：實體/VM 主機，負責區域網路連接 (SSH/SMB) 和提供 WSL 2 基礎設施。
*   **WSL 2 (Virtualization)**：Windows 的 Linux 子系統層，負責在 Windows Kernel 上模擬 Linux Kernel，提供高效能的虛擬化環境。
*   **Ubuntu 24.04 (Guest OS)**：運行在 WSL 2 之上的 Linux 發行版，是我們安裝 Docker Engine 的實際作業系統。
*   **Docker Container**：實際應用程式 (Laravel, MySQL 等) 運作的地方，提供隔離的執行環境，確保應用程式與底層系統依賴分離。

---

本指南涵蓋了從 VM 硬體設定到 Docker 部署，以及最終的遠端開發工作流 (Hybrid SMB+SSH) 的完整細節。

---

## ⚡ 精簡重點版 (Fast Track)

對於熟悉環境的管理員，請依照以下檢核點快速設定：

1.  **VM 設定** **[Server]**：VMware 硬體相容性升級至 **v16+**，並勾選 `Virtualize Intel VT-x/EPT`。
2.  **移除 Hyper-V** **[Server]**：Server 2022 必須**移除** Server Role 中的 `Hyper-V` (避免與 WSL 衝突)。
3.  **安裝 WSL 2** **[Server]**：
    *   僅需安裝 `wsl_update_x64.msi` (Kernel Update)。
    *   匯入 Ubuntu 24.04 Tarball：`wsl --import Ubuntu-24.04 C:\WSL\Ubuntu rootfs.tar.gz`。
4.  **安裝 Docker Engine** **[Server]**：
    *   離線安裝約 17 個 `.deb` 套件 (核心元件 + 防火牆依賴)。
    *   **關鍵修正**：切換 iptables 模式 `update-alternatives --set iptables /usr/sbin/iptables-legacy`。
5.  **部署 Portainer** **[Server]**：
    *   離線載入 `portainer.tar` 並啟動 Container (Port 9000)。
6.  **部署 OpenSSH Server** **[Server]**：
    *   安裝 GitHub 版 Win32-OpenSSH 至 `C:\Program Files\OpenSSH-Win64`。
    *   執行 `FixHostFilePermissions.ps1` 修復權限。
7.  **SSH 金鑰授權** **[Server]**：
    *   管理員公鑰必須放在：`C:\ProgramData\ssh\administrators_authorized_keys`。
    *   **必須設定 ACL**：只允許 SYSTEM 和 Administrators 群組完全控制。
8.  **Client 工作流 (Hybrid)** **[Client]**：
    *   **編輯**：掛載 Server 資料夾為 **網路磁碟機** (透過 SMB)。
    *   **執行**：AI 透過 SSH Tunnel (免密碼) 自動執行 `wsl docker` 指令。

---

<br>
<br>

## 📘 詳細建置手冊 (Detailed Step-by-Step)

以下紀錄了所有遇到的坑與解決方案，請按順序執行。

### 1. 虛擬機與 OS 準備 **[在 Server 端執行]**

**目標**：讓 Server 2022 能夠支援 WSL 2 運行。

#### 1.1 VMware 設定 (關鍵相容性)
*   **硬體相容性**：必須升級至 **Workstation 16.x** 或更高。
*   **CPU 設定**：勾選 `Virtualize Intel VT-x/EPT or AMD-V/RVI`。
*   **`.vmx` 參數修正**：
    我們在安裝過程中曾遇到 VM 啟動失敗，後來嘗試修改 `.vmx` 檔 (如 `hypervisor.cpuid.v0 = "FALSE"` 或 `mks.enable3d = "TRUE"`)。
    *   **最終結論**：WSL 2 主要依賴 GUI 介面中的 `Virtualize Intel VT-x/EPT` 勾選即可，`.vmx` 的修改若造成不穩定應還原。

#### 1.2 Windows Server 設定
*   **衝突檢查**：WSL 2 依賴的是底層的 Virtual Machine Platform 功能，而不是 Hyper-V 的完整角色。
*   **關鍵動作**：如果曾經安裝過 `Hyper-V` 角色，請**移除它** (這是我們遇到的主要阻礙之一)。
*   **必要功能啟用**：
    *   Virtual Machine Platform
    *   Windows Subsystem for Linux

### 2. WSL 2 與 Ubuntu 離線部署 **[在 Server 端執行]**

**目標**：在沒有 Microsoft Store 的 Server 環境安裝 Linux。

#### 2.1 依賴套件安裝
我們僅安裝了核心更新，並未安裝用於 GUI 的 Xaml 套件 (Server Core 不需要)：
1.  **WSL Kernel Update** (`wsl_update_x64.msi`)。

#### 2.2 匯入 Linux 發行版
我們無法使用 `wsl --install` (因為沒網際網路)。
1.  **準備**：下載 Ubuntu 24.04 的 Rootfs Tarball (`install.tar.gz`)。
2.  **匯入**：
    ```powershell
    # 建立安裝目錄
    New-Item -Path "C:\WSL\Ubuntu" -ItemType Directory
    # 匯入 (這會建立 ext4.vhdx)
    wsl --import Ubuntu-24.04 "C:\WSL\Ubuntu" "C:\Users\...\install.tar.gz" --version 2
    ```
3.  **啟動**：輸入 `wsl -d Ubuntu-24.04` 進入。

### 3. Docker Engine 離線安裝 (WSL 內部) **[在 Server 端執行]**

**目標**：在 Ubuntu 內建立 Docker 執行環境 (不使用 Docker Desktop)。

#### 3.1 準備 `.deb` 檔案
你需要從 `download.docker.com` 預先下載以下套件 (對應 Ubuntu 版本)：
*   `containerd.io`
*   `docker-ce-cli`
*   `docker-ce`
*   `docker-buildx-plugin`
*   `docker-compose-plugin`

#### 3.2 安裝與修正依賴
**我們總共手動安裝了約 17 個 .deb 套件**，這是在完全離線環境下最耗時的步驟。

1.  **Docker 核心元件 (5 個)**：
    *   `containerd.io`
    *   `docker-ce-cli`
    *   `docker-ce`
    *   `docker-buildx-plugin`
    *   `docker-compose-plugin`

2.  **防火牆與網路相依套件 (8 個)**：
    *   `libnfnetlink0`
    *   `libnetfilter-conntrack3`
    *   `libnftnl11`
    *   `libip6tc2`
    *   `libnftables1`
    *   `nftables`
    *   `libip4tc2`
    *   `iptables`

3.  **其他系統相依 (約 4 個)**：
    *   視 Ubuntu 映像檔初始狀態而定，通常還包含 `libltdl7`, `pigz`, `slirp4netns` 或 `git` 相關套件。

**安裝指令**：
```bash
# 建議將所有 deb 檔放在同一目錄，一次安裝讓 dpkg 自動解決順序
sudo dpkg -i *.deb
```
3.  **關鍵報錯修正**：啟動時若出現 `iptables failed...`：
    *   原因：新版 Docker 與 Ubuntu 24.04 的 nftables 防火牆後端不相容。
    *   解法：強制切換回 legacy 模式。
        ```bash
        sudo update-alternatives --set iptables /usr/sbin/iptables-legacy
        sudo update-alternatives --set ip6tables /usr/sbin/ip6tables-legacy
        ```
4.  **啟動服務**：`sudo service docker start`。

#### 3.3 專案容器服務架構 (Service Architecture)
我們使用 `Docker Compose` 來管理專案的多個相依服務，這些服務運行在彼此隔離的容器中，確保 Server 本身環境乾淨：

| 服務名稱 | Image | 用途與說明 |
| :--- | :--- | :--- |
| **blogmix** | `blogmix-v2-blogmix` | **核心應用程式**。基於 PHP 8.x，負責運行 Laravel 程式碼。對外 Port: `8000` (Web), `5173` (Vite)。 |
| **mysql** | `mysql:8.0` | **資料庫**。資料持久化存儲於 Volume。Root 密碼由 `.env` 控制。 |
| **redis** | `redis:alpine` | **快取與 Session**。用於提升效能與存放使用者 Session。 |
| **mailpit** | `axllent/mailpit` | **郵件測試伺服器**。攔截應用程式發出的所有信件，提供 Web 介面 (Port: `8025`) 查看，避免誤寄真信。 |
| **phpmyadmin** | `phpmyadmin` | **資料庫管理介面**。提供圖形化網頁 (Port: `8080`) 來管理 MySQL 資料庫。 |

---

---

### 8. 後續維護：離線新增 Docker Image

**目標**：在沒有網路的 Server 上新增 Docker 映像檔 (如 Redis, MySQL, 自定義專案)。

#### 8.1 下載與打包 **[在 Client 端執行]**
```powershell
# 1. 下載 image
docker pull redis:alpine

# 2. 打包成 tar 檔
docker save -o redis.tar redis:alpine
```

#### 8.2 傳輸到 Server **[在 Client 端執行]**
利用 SMB 分享資料夾直接丟進去：
*   將 `redis.tar` 複製到 `\\ServerIP\Project` (即 Z/V 槽)。

#### 8.3 載入 Image **[在 Client 或 Server 端執行]**
若使用自動化流程，可透過 SSH 在 Client 端觸發：
```bash
# 假設檔案在 V 槽對應的 /mnt/c/... 路徑
ssh feb16@10.13.1.20 wsl docker load -i /mnt/c/專案路徑/redis.tar
```
載入成功後，`ssh feb16@10.13.1.20 wsl docker images` 就能看到該 Image。

### 4. Portainer 離線部署 (圖形化管理) **[在 Server 端執行]**

**目標**：提供 Web 介面管理 Docker，避免每次都要敲指令。

1.  **準備 Image**：從 `hub.docker.com` 下載 `portainer/portainer-ce:latest` 的 `docker save` 匯出檔 (tar)。
2.  **載入 Image**：
    ```bash
    wsl docker load -i portainer.tar
    ```
3.  **啟動容器**：
    ```bash
    wsl docker run -d -p 9000:9000 --name portainer --restart=always -v /var/run/docker.sock:/var/run/docker.sock portainer/portainer-ce:latest
    ```
4.  **訪問**：瀏覽器打開 `http://ServerIP:9000` 設定管理員密碼。

### 5. OpenSSH Server 離線部署 **[在 Server 端執行]**

**目標**：讓外部 Client 能以 SSH 控制 Server。這是最複雜的一環。

#### 5.1 安裝 OpenSSH
Server 2022 的 `Add-WindowsCapability` 離線失效，必須用 GitHub 版本的 Win32-OpenSSH。

1.  **下載**：[OpenSSH-Win64.zip](https://github.com/PowerShell/Win32-OpenSSH/releases)。
2.  **路徑陷阱 (Critical)**：
    *   ❌ **錯誤**：放在 `C:\Users\Administrator\Desktop\OpenSSH`。
        *   後果：Service 啟動時是 SYSTEM 權限，無法讀取 User Profile 內的檔案，導致連線瞬間斷開 (`Connection reset`)。
    *   ✅ **正確**：搬移至 **`C:\Program Files\OpenSSH-Win64`**。
3.  **安裝腳本**：
    進入該目錄執行：
    ```powershell
    .\install-sshd.ps1
    ```

#### 5.2 權限與設定修復
OpenSSH 對權限有極度潔癖，權限稍有不對就會拒絕連線。
1.  **執行權限修復工具**：
    ```powershell
    .\FixHostFilePermissions.ps1 -Confirm:$false
    ```
2.  **Debug 模式診斷**：若連線被重置，請停止服務並執行 `.\sshd.exe -ddd` 看 Log。
3.  **sshd_config 語法陷阱**：
    *   若使用 `Add-Content` 在檔案尾端加入設定，很容易不小心加到 `Match Group administrators` 區塊內。
    *   **症狀**：服務無法啟動 (`Restart-Service` 失敗)。
    *   **解法**：手動用記事本將 `SyslogFacility` 和 `LogLevel` 移到設定檔**第一行**。

### 6. SSH 免密碼登入 (管理員專用 - 完整流程)

為了實現自動化控制，我們在 Client 端建立了金鑰，並將其註冊到 Server 端。

#### 6.1 產生金鑰 **[在 Client 端執行]**
我們在個人帳號下產生了一組專用的 SSH Key：
```powershell
# 在 Client 端執行
ssh-keygen -t ed25519
```
這會產生 `id_ed25519` (私鑰) 與 `id_ed25519.pub` (公鑰)。

#### 6.2 註冊金鑰 (關鍵設定) **[在 Server 端執行]**
由於登入帳號是管理員 (`feb16`)，Windows OpenSSH **不會**讀取使用者家目錄的 `authorized_keys`。
我們必須將 Client 的 **公鑰內容** (`id_ed25519.pub`) 複製到 Server 的全域設定檔：
*   **檔案位置**：`C:\ProgramData\ssh\administrators_authorized_keys`

#### 6.3 權限設定 (ACL) **[在 Server 端執行]**
該檔案必須設定正確的存取權限 (ACL)，否則 SSH Service 會拒絕讀取。
```powershell
# 在 Server 端執行
icacls "C:\ProgramData\ssh\administrators_authorized_keys" /inheritance:r /grant "Administrators:F" /grant "SYSTEM:F"
```

### 7. 最終工作流與 AI 自動化 (SMB + SSH)

由於離線環境下 VS Code Remote Agent 安裝過於困難 (依賴特定 Commit ID)，我們建置了一套 **Hybrid Vibe Coding 環境**。

#### 7.1 磁碟掛載 (V: 槽) **[在 Server 與 Client 端執行]**
我們直接利用 Windows 原生分享：
1.  **Server 端**：已透過指令開啟 `Project` 資料夾分享。
2.  **Client 端**：已建立 **網路磁碟機 (如 V: 槽)** 映射至 Server 資料夾 (例如 `V:\PHP\Laravel-BlogMix-master\v2`)。
    *   **Antigravity 角色**：AI 會直接讀取/修改 **網路磁碟機** 的檔案，實現「本地編輯，遠端生效」。

#### 7.2 AI 自動化執行 (SSH Tunnel) **[在 Client 端執行]**
Antigravity 無法直接在 Client 跑 Server 的 Docker，因此我們賦予 AI **SSH 執行能力**：
*   **AI 操作邏輯**：當需要重啟 Docker 時，AI 會自動執行：
    ```bash
    ssh feb16@10.13.1.20 "wsl docker compose restart"
    ```
*   **達成效果**：您只需下達自然語言指令 (如「幫我重啟專案」)，AI 就會自動透過 SSH 去 Server 執行對應動作，完全不需要您手動切換視窗。

### 8. 前端開發模式：限制與解決方案

#### 8.1 環境限制總結

**核心問題**：在完全離線環境下，無法在容器內安裝 npm 依賴以啟動 Vite Dev Server (HMR)。

| 限制項目 | 具體影響 | 嘗試方案 | 結果 |
|---------|---------|---------|------|
| **Server 完全離線** | 容器內無法執行 `npm ci` 下載套件 | 在容器執行 `npm install` | ❌ `ETIMEDOUT` |
| **SMB 平台偵測** | 在 Windows Volume 上安裝會得到 Windows 二進位檔 | Client Docker 掛載 Volume 安裝 | ❌ 得到 `rollup-win32` 而非 `rollup-linux` |
| **符號連結損壞** | Windows/Linux 檔案系統對符號連結處理不同 | 複製 node_modules 到容器 | ❌ `.bin/*` 執行檔無法運作 |
| **Alpine npm Bug** | `npm error Exit handler never called!` | 使用 `node:20-alpine` | ❌ 安裝失敗 |

#### 8.2 Volume Overlay 設定（最佳實踐）

**目的**：隔離 `node_modules` 目錄，使其不受 SMB 共享影響，這是 Docker + SMB 環境的標準做法。

**設定方式** (`compose.yaml`):
```yaml
services:
    blogmix:
        volumes:
            - '.:/var/www/html'                      # SMB 掛載（主要程式碼）
            - 'node_modules:/var/www/html/node_modules'  # Volume 覆蓋（隔離依賴）
        # ... 其他設定

volumes:
    node_modules:
        driver: local
```

**優點**：
- ✅ 符合 Docker 官方建議
- ✅ 避免 SMB 對大量小檔案的性能問題
- ✅ 平台特定二進位檔正確隔離
- ✅ 未來有網路時可快速啟用 Dev Server

#### 8.3 當前解決方案：靜態編譯模式

**開發流程** **[在 Client 端執行]**:
```powershell
# 1. 修改 Vue/CSS 檔案（透過 VS Code 編輯 V: 槽）

# 2. 編譯前端資源
cd V:\PHP\Laravel-BlogMix-master\v2
npm run build   # 需時約 30-60 秒

# 3. 重新整理瀏覽器查看變更
```

**技術說明**：
- Laravel 從 `public/build` 目錄提供靜態資源
- 無需 Vite Dev Server 運行
- 無 Hot Module Replacement (HMR)

**適用場景**：
- ✅ 離線開發環境
- ✅ 大部分功能開發需求
- ✅ 穩定可靠的方案

#### 8.4 未來改善方案（需網路連線）

**當 Server 有網路連線時**，可一次性完成 Vite Dev Server 設定：

```bash
# 1. 在容器內安裝 Linux 版依賴（5分鐘）
ssh feb16@10.13.1.20 "wsl docker compose -f /mnt/c/.../compose.yaml exec blogmix npm ci"

# 2. 啟動 Vite Dev Server（背景執行）
ssh feb16@10.13.1.20 "wsl docker compose -f /mnt/c/.../compose.yaml exec -d blogmix npm run dev"
```

**效果**：
- ✅ 完整的 HMR 體驗
- ✅ 程式碼修改即時更新
- ✅ 開發效率大幅提升

**驗證**：
- 檢查 `public/hot` 檔案是否生成
- 瀏覽器開發者工具應顯示 Vite WebSocket 連線
- 修改程式碼應自動重載頁面

#### 8.5 關鍵經驗總結

1.  **Volume Overlay 是必要的**：即使在離線環境下無法立即使用，這個設定仍是最佳實踐，為未來升級奠定基礎。
2.  **靜態編譯是務實選擇**：在離線環境下，`npm run build` 是唯一可行且穩定的方案。
3.  **平台偵測問題**：npm 會根據檔案系統（而非容器）偵測平台，導致在 Windows Volume 上安裝 Linux 二進位檔時失敗。
4.  **離線環境限制**：無法繞過 npm registry，必須在有網路時完成依賴安裝。

#### 8.6 關鍵修復：Windows SMB 前端建置 (Recall Solution)

**問題**：
直接在 V: 槽 (SMB) 執行 `npm run build` 會因為 Windows 檔案鎖定與 `esbuild` 通訊問題而失敗 (`esbuild: Socket.readFromStdout error`)。同時 Server 離線無法在容器內建置。

**解決方案 (Local Proxy Build)**：
將專案複製到本機 C 槽暫存區，建置完畢後再將 `public/build` 同步回 V: 槽。

**標準作業流程 (PowerShell)**：
```powershell
# 1. 定義路徑
$Source = "V:\PHP\Laravel-BlogMix-master\v2"
$Dest = "$env:TEMP\blogmix_build_v2"

# 2. 複製必要檔案 (排除 node_modules)
if (Test-Path $Dest) { Remove-Item $Dest -Recurse -Force }
New-Item $Dest -ItemType Directory | Out-Null
Copy-Item "$Source\package*.json", "$Source\*.config.js" $Dest
robocopy "$Source\resources" "$Dest\resources" /E /NFL /NDL /NJH /NJS
robocopy "$Source\public" "$Dest\public" /E /NFL /NDL /NJH /NJS

# 3. 補全缺失依賴 (若 package.json 不完整)
cd $Dest
npm install
# 確保 PostCSS/Tailwind/PrimeVue 存在 (若專案原本漏掉)
npm install -D tailwindcss postcss autoprefixer
npm install primevue@3 --save-prod 

# 4. 建置
npm run build

# 5. 同步回 Server (V: 槽)
if ($?) {
    robocopy "$Dest\public\build" "$Source\public\build" /E /NFL /NDL /NJH /NJS
    Write-Host "✅ Build Synced to Server Successfully!" -ForegroundColor Green
}
```

---

#### 8.7 核心觀念釐清：Runtime 連結 vs Devtime 建置

在開發過程中，我們使用了兩種不同的「繞路/修正」技巧，很容易混淆，在此特別說明：

| 項目 | **符號連結 (Symbolic Link)** | **代理建置 (Proxy Build)** |
| :--- | :--- | :--- |
| **指令** | `php artisan storage:link` | `Copy-Item ...` (PowerShell Script) |
| **層級** | **Runtime (執行期)** | **Devtime (開發期)** |
| **目的** | 讓 Web Server (Nginx) 能讀取 `storage` 內的圖片。 | 繞過 Windows SMB 網路磁碟機的 I/O 效能瓶頸。 |
| **原因** | Laravel 架構預設將公開檔案與私有檔案分離，需透過連結橋接。 | `npm run build` 會產生大量小檔案讀寫，直接在 V: 槽執行會卡死或報錯。 |
| **執行頻率** | **只需一次** (部署時)。 | **每次修改前端** (JS/CSS) 後。 |
| **關聯性** | 與 SMB 無關，這是 Linux/Laravel 的標準需求。 | 純粹為了應對離線/SMB 環境的特殊權宜之計。 |

**結論**：即便我們修好了符號連結，Proxy Build 依然是必要的，因為那是為了解決 SMB 檔案鎖定與傳輸過慢的物理限制。

---
*前端開發模式章節更新日期：2026-01-02*


*文件建立日期：2025-12-23*

### 9. Agent 足跡與清理協定 (Agent Footprint Protocol)

為了確保開發環境整潔，Agent 在運作過程中產生的臨時檔案應遵循以下紀錄與清理規範：

#### 9.1 足跡分佈 (Locations)
1.  **大腦記憶區 (Brain Artifacts)**
    *   **路徑**：`C:\Users\yu-an\.gemini\antigravity\brain\{Session-ID}`
    *   **內容**：
        *   `*.md` (核心文件：task, implementation_plan, walkthrough) ➜ **保留**
        *   `*.png`, `*.webp` (瀏覽器測試截圖與錄影) ➜ **可定期清理** (數量隨開發次數線性增長)
    
2.  **暫存建置區 (Temp Build Dir)**
    *   **路徑**：`$env:TEMP\blogmix_build_v2` (或其他自定義 Temp)
    *   **內容**：完整的前端原始碼副本與 `node_modules`。
    *   **策略**：**保留作為快取 (Keep as Cache)**。
        *   由於 SMB 安裝 `node_modules` 耗時且不穩定，保留此目錄可讓後續的「Local Proxy Build」直接沿用依賴，大幅加速建置流程。
        *   僅在磁碟空間不足或專案長期封存時才考慮刪除。

3.  **工具腳本 (Utility Scripts)**
    *   **路徑**：
        *   `dev_scripts/` 目錄 (e.g., `dev_scripts/verify_fix.php`) ➜ **建議位置** (保持根目錄整潔)
        *   專案根目錄 ➜ **禁止** (避免污染環境，若不慎建立請立即刪除)
        *   Agent 根目錄 (`.gemini/*.py`) ➜ **保留或詢問刪除**

#### 9.2 清理指令 (Cleanup Commands)
當專案階段性任務完成時，應執行以下清理：

```powershell
# 1. 清理前端暫存建置
Remove-Item "$env:TEMP\blogmix_build_v2" -Recurse -Force -ErrorAction SilentlyContinue

# 2. 清理過期截圖 (保留最近 7 天或全部刪除)
$BrainPath = "C:\Users\yu-an\.gemini\antigravity\brain\*"
Get-ChildItem "$BrainPath\*.webp", "$BrainPath\*.png" | Remove-Item -Force
```

---
*Agent 足跡協定更新日期：2026-01-02*

### 10. 常用維護指令 (Common Maintenance Commands)

當您需要重啟服務或套用設定變更時，請依據需求選擇以下方式（皆需透過 SSH 在 Server 端執行）：

#### 10.1 一般重啟 (Soft Restart)
*   **時機**：程式碼卡住、怪怪的，或單純想重啟應用程式。
*   **指令**：
    ```bash
    cd Desktop\專案程式\PHP\Laravel-BlogMix-master\v2
    wsl docker compose restart
    ```

#### 10.2 完整重啟 (Hard Restart / Recreate)
*   **時機**：
    *   修改了 `compose.yaml` (例如改名、新增容器)。
    *   修改了 `.env` 設定檔。
    *   需要徹底釋放資源時。
*   **指令**：
    ```bash
    cd Desktop\專案程式\PHP\Laravel-BlogMix-master\v2
    # 1. 停止並移除容器 (資料庫資料保留在 Volume 不會消失)
    wsl docker compose down
    
    # 2. 重新讀取設定並啟動
    wsl docker compose up -d
    ```

