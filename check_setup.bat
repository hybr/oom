@echo off
REM V4L (Vocal 4 Local) - Setup Verification Script

echo ========================================
echo V4L Setup Verification
echo ========================================
echo.

set /a errors=0

REM Check PHP
echo [1/8] Checking PHP...
php -v >nul 2>&1
if errorlevel 1 (
    echo [FAIL] PHP not found. Please install PHP 8.2 or higher.
    set /a errors+=1
) else (
    for /f "tokens=2" %%i in ('php -v ^| findstr /R "^PHP"') do (
        echo [OK] PHP version %%i found
    )
)

REM Check SQLite
echo [2/8] Checking SQLite...
sqlite3 -version >nul 2>&1
if errorlevel 1 (
    echo [FAIL] SQLite3 not found. Please install SQLite3.
    set /a errors+=1
) else (
    for /f "tokens=1" %%i in ('sqlite3 -version') do (
        echo [OK] SQLite version %%i found
    )
)

REM Check directories
echo [3/8] Checking directories...
if not exist "data" (
    echo [FAIL] data/ directory missing
    set /a errors+=1
) else (
    echo [OK] data/ directory exists
)

if not exist "logs" (
    echo [FAIL] logs/ directory missing
    set /a errors+=1
) else (
    echo [OK] logs/ directory exists
)

if not exist "public" (
    echo [FAIL] public/ directory missing
    set /a errors+=1
) else (
    echo [OK] public/ directory exists
)

REM Check configuration
echo [4/8] Checking configuration...
if not exist "config\.env" (
    echo [WARN] .env file not found (will use defaults)
) else (
    echo [OK] .env file exists
)

REM Check database
echo [5/8] Checking database...
if not exist "data\v4l.db" (
    echo [WARN] Database not created yet. Run setup.bat to create it.
) else (
    echo [OK] Database file exists

    REM Check if database has tables
    for /f %%i in ('sqlite3 data\v4l.db "SELECT COUNT(*) FROM entity_definition;" 2^>nul') do set entity_count=%%i
    if defined entity_count (
        if %entity_count% gtr 0 (
            echo [OK] Database has %entity_count% entities defined
        ) else (
            echo [WARN] Database exists but has no entities
            set /a errors+=1
        )
    ) else (
        echo [FAIL] Cannot read database
        set /a errors+=1
    )
)

REM Check PHP extensions
echo [6/8] Checking PHP extensions...
php -m | findstr /C:"pdo_sqlite" >nul
if errorlevel 1 (
    echo [FAIL] PHP extension pdo_sqlite not found
    set /a errors+=1
) else (
    echo [OK] pdo_sqlite extension loaded
)

php -m | findstr /C:"sqlite3" >nul
if errorlevel 1 (
    echo [FAIL] PHP extension sqlite3 not found
    set /a errors+=1
) else (
    echo [OK] sqlite3 extension loaded
)

php -m | findstr /C:"mbstring" >nul
if errorlevel 1 (
    echo [WARN] PHP extension mbstring not found (recommended)
) else (
    echo [OK] mbstring extension loaded
)

REM Check file permissions (basic check)
echo [7/8] Checking file permissions...
echo test > data\test.tmp 2>nul
if errorlevel 1 (
    echo [FAIL] data/ directory not writable
    set /a errors+=1
) else (
    del data\test.tmp >nul 2>&1
    echo [OK] data/ directory is writable
)

echo test > logs\test.tmp 2>nul
if errorlevel 1 (
    echo [FAIL] logs/ directory not writable
    set /a errors+=1
) else (
    del logs\test.tmp >nul 2>&1
    echo [OK] logs/ directory is writable
)

REM Check core files
echo [8/8] Checking core files...
if not exist "lib\core\Database.php" (
    echo [FAIL] Core file Database.php missing
    set /a errors+=1
) else (
    echo [OK] Core files present
)

echo.
echo ========================================
echo Verification Results
echo ========================================

if %errors%==0 (
    echo.
    echo [SUCCESS] All checks passed!
    echo.
    if not exist "data\v4l.db" (
        echo Next step: Run setup.bat to create the database
    ) else (
        echo Next step: Run start_dev_server.bat to start the application
    )
) else (
    echo.
    echo [FAILED] %errors% error(s) found
    echo Please fix the errors above before proceeding
)

echo.
pause
