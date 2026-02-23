@echo off
title NILAM Laravel Server
echo Starting Laravel development server...
echo.
echo IMPORTANT: For "php artisan serve" to work, in your .env file set:
echo   APP_URL=http://127.0.0.1:8000
echo   APP_BASE_PATH=
echo.
echo Then open in browser: http://127.0.0.1:8000
echo.
echo Press Ctrl+C to stop the server.
echo.

set PHP_EXE=php
where php >nul 2>nul
if errorlevel 1 (
    set PHP_EXE=C:\xampp\php\php.exe
    echo Using %PHP_EXE%
)

cd /d "%~dp0"
%PHP_EXE% artisan serve

pause
