@echo off
echo ==========================================
echo   Starting BlogMix (Laravel Sail)...
echo ==========================================

:: 直接使用 Docker Compose 啟動，無需依賴本機 PHP/Sail 腳本
docker compose up -d

echo.
echo [SUCCESS] Environment is running.
echo URLs:
echo   - App: http://localhost
echo   - Mailpit: http://localhost:8025
echo   - MySQL: localhost:3306
echo.
pause
