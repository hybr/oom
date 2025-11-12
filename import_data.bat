@echo off
REM V4L (Vocal 4 Local) - Data Import Script
REM This script imports entity data into existing database tables

echo ========================================
echo V4L Entity Data Import
echo ========================================
echo.

REM Check if database exists
if not exist "data\v4l.db" (
    echo ERROR: Database not found. Please run setup.bat first.
    pause
    exit /b 1
)

echo Importing entity data...
php import_entity_data.php

if errorlevel 1 (
    echo.
    echo ERROR: Data import failed!
    pause
    exit /b 1
)

echo.
echo ========================================
echo Data Import Complete!
echo ========================================
echo.
pause
