@echo off
REM V4L (Vocal 4 Local) - Development Server Starter

echo ========================================
echo V4L Development Server
echo ========================================
echo.

REM Check if database exists
if not exist "data\v4l.db" (
    echo WARNING: Database not found!
    echo Please run setup.bat first to create the database.
    echo.
    pause
    exit /b 1
)

REM Check if .env exists
if not exist "config\.env" (
    echo WARNING: Configuration file not found!
    echo Please run setup.bat first to create config\.env
    echo.
    pause
    exit /b 1
)

echo Starting PHP development server...
echo.
echo Server URL: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.
echo ========================================
echo.

cd public
php -S localhost:8000

cd ..
