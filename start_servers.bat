@echo off
echo === PHP Order Management System ===
echo.

echo Checking PHP extensions...
php check_extensions.php
if %errorlevel% neq 0 (
    echo.
    echo Please install missing PHP extensions before continuing.
    pause
    exit /b 1
)

echo.
echo Starting web server on http://localhost:8000
echo Press Ctrl+C to stop
echo.

start /b php services/websocket/SimpleWebSocketServer.php
php -S localhost:8000 -t public/

pause