@echo off
REM Wrapper for PowerShell Safe Build Script
REM Solves Error 1920 (Symlink Locking) by building in %TEMP%

echo Starting BlogMix Safe Build...
powershell -ExecutionPolicy Bypass -File ".\scripts\safe_build.ps1"

if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Build Failed!
    pause
    exit /b %errorlevel%
)

echo.
echo [DONE] Build process completed successfully.
pause
