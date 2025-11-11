@echo off
REM V4L (Vocal 4 Local) - Clean Setup Script
REM This script removes the existing database and allows you to start fresh

echo ========================================
echo V4L Clean Setup Script
echo ========================================
echo.
echo WARNING: This will delete your existing database!
echo All data will be lost.
echo.
set /p confirm="Are you sure you want to continue? (yes/no): "

if /i not "%confirm%"=="yes" (
    echo Operation cancelled.
    pause
    exit /b 0
)

echo.
echo Cleaning up...

REM Remove database
if exist "data\v4l.db" (
    echo Removing database...
    del /f /q data\v4l.db
)

REM Remove error log
if exist "setup_errors.log" (
    del /f /q setup_errors.log
)

REM Remove session files
if exist "data\sessions\*" (
    echo Cleaning session files...
    del /f /q data\sessions\*
)

echo.
echo Cleanup complete!
echo.
echo You can now run setup.bat to create a fresh database.
echo.
pause
