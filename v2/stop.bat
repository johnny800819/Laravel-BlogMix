@echo off
echo ==========================================
echo   Stopping BlogMix (Laravel Sail)...
echo ==========================================

:: 停止容器
docker compose stop

echo.
echo [SUCCESS] Environment stopped.
echo.
pause
