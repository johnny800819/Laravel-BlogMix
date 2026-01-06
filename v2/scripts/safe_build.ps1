# safe_build.ps1
# 專為避開 Windows Server 2022 + Symlink (Error 1920) 鎖定問題設計
# 使用 "Surgical Copy" 策略：只在 Temp 目錄編譯，僅同步 Build 產物

$ErrorActionPreference = "Stop"

# 1. 設定路徑
$ProjectRoot = "v:\PHP\Laravel-BlogMix-master\v2"
$TempBuildDir = "$env:TEMP\blogmix_build_safe"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   BlogMix v2 Safe Build Triggered" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Source: $ProjectRoot"
Write-Host "Temp:   $TempBuildDir"

# 2. 清理暫存區
Write-Host "`n[1/5] Cleaning Temp Directory..." -ForegroundColor Yellow
if (Test-Path $TempBuildDir) { 
    Remove-Item $TempBuildDir -Recurse -Force 
}
New-Item -ItemType Directory -Path $TempBuildDir | Out-Null

# 3. 外科手術式複製 (Surgical Copy)
# 只複製 package.json, 設定檔與 source code (避開 node_modules & public/storage)
Write-Host "[2/5] Copying Essential Files (Surgical Copy)..." -ForegroundColor Yellow

$essentialFiles = @(
    "package.json",
    "package-lock.json",
    "vite.config.js",
    "tailwind.config.js",
    "postcss.config.js",
    "jsconfig.json"
)

foreach ($file in $essentialFiles) {
    if (Test-Path "$ProjectRoot\$file") {
        Copy-Item "$ProjectRoot\$file" $TempBuildDir
    }
}

# 複製 resources 目錄 (核心程式碼)
Copy-Item "$ProjectRoot\resources" $TempBuildDir -Recurse

# 4. 執行建置
Write-Host "[3/5] Installing Dependencies in Temp..." -ForegroundColor Yellow
Set-Location $TempBuildDir
try {
    npm install
} catch {
    Write-Error "npm install failed!"
    exit 1
}

Write-Host "[4/5] Building Assets..." -ForegroundColor Yellow
try {
    npm run build
} catch {
    Write-Error "npm run build failed!"
    exit 1
}

# 5. 同步產物回專案 (只同步 public/build)
Write-Host "[5/5] Syncing Artifacts back to V: ..." -ForegroundColor Yellow

if (Test-Path "$TempBuildDir\public\build") {
    # 使用 Robocopy 同步 build 資料夾
    # /MIR: 鏡像 (會刪除目標多餘檔案)
    # /FFT: 容許 2秒時間差 (適合跨磁碟)
    # /Z: 可重新啟動模式
    # /R:3 /W:5: 失敗重試參數
    # /NFL /NDL /NJH /NJS: 減少雜訊輸出
    robocopy "$TempBuildDir\public\build" "$ProjectRoot\public\build" /MIR /FFT /Z /R:3 /W:5 /NFL /NDL /NJH /NJS
    
    # Check robocopy exit code (Anything below 8 is success)
    if ($LASTEXITCODE -ge 8) {
        Write-Error "Robocopy failed with exit code $LASTEXITCODE"
        exit 1
    }

    Write-Host "`n[SUCCESS] Build Complete & Synced!" -ForegroundColor Green
    Get-Item "$ProjectRoot\public\build\manifest.json" | Select-Object LastWriteTime
} else {
    Write-Error "Build output (public/build) not found in temp!"
    exit 1
}
