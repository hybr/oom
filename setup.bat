@echo off
REM V4L (Vocal 4 Local) - Windows Setup Script
setlocal enabledelayedexpansion

echo ========================================
echo V4L Setup Script
echo ========================================
echo.

REM Check if data directory exists
if not exist "data" (
    echo Creating data directory...
    mkdir data
    mkdir data\uploads
)

REM Check if logs directory exists
if not exist "logs" (
    echo Creating logs directory...
    mkdir logs
)

REM Check if .env file exists
if not exist "config\.env" (
    echo Creating .env file from template...
    copy "config\.env.example" "config\.env" >nul
    echo .env file created. Please edit config\.env with your settings.
)

REM Check if database exists
if exist "data\v4l.db" (
    echo Database already exists. Skipping database creation.
    echo If you want to recreate the database, delete data\v4l.db first.
    goto :end
)

echo Creating SQLite database...
echo This may take a few minutes...
echo.

REM Create database and import initial schema
echo [1/5] Creating database schema...
sqlite3 data\v4l.db < metadata\initial.sql 2>nul
if errorlevel 1 (
    echo ERROR: Failed to create initial schema
    pause
    exit /b 1
)

REM Import all entity definitions
echo [2/5] Importing entity definitions...

echo PRAGMA foreign_keys = OFF; > temp_import.sql
echo BEGIN TRANSACTION; >> temp_import.sql
for %%f in (metadata\entities\*.sql) do (
    type "%%f" >> temp_import.sql
)
echo COMMIT; >> temp_import.sql
echo PRAGMA foreign_keys = ON; >> temp_import.sql

sqlite3 data\v4l.db < temp_import.sql 2>setup_errors.log
del temp_import.sql

REM Import process definitions
echo [3/5] Importing process definitions...

echo PRAGMA foreign_keys = OFF; > temp_import.sql
echo BEGIN TRANSACTION; >> temp_import.sql
for %%f in (metadata\processes\*.sql) do (
    type "%%f" >> temp_import.sql
)
echo COMMIT; >> temp_import.sql
echo PRAGMA foreign_keys = ON; >> temp_import.sql

sqlite3 data\v4l.db < temp_import.sql 2>>setup_errors.log
del temp_import.sql

echo.
echo [4/5] Creating database tables from metadata...
php create_tables.php

echo.
echo [5/5] Importing entity data...
php import_entity_data.php

echo.
echo Database created successfully!

REM Check for critical errors
if exist setup_errors.log (
    findstr /C:"Error:" setup_errors.log >nul
    if not errorlevel 1 (
        echo.
        echo NOTE: Some warnings occurred during import. Check setup_errors.log for details.
        echo The database should still be functional for basic operations.
    ) else (
        del setup_errors.log
    )
)

:end
echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Edit config\.env with your settings
echo 2. Start PHP development server:
echo    cd public
echo    php -S localhost:8000
echo 3. Open http://localhost:8000 in your browser
echo.
echo For production deployment, see README.md
echo.
pause
