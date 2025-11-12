@echo off
REM V4L (Vocal 4 Local) - Make User Super Admin
REM This script grants super admin privileges to a user

echo ========================================
echo Make User Super Admin
echo ========================================
echo.

if "%~1"=="" (
    php make_admin.php
) else (
    php make_admin.php %1
)

if errorlevel 1 (
    echo.
    echo ERROR: Failed to make user admin
    pause
    exit /b 1
)

echo.
pause
