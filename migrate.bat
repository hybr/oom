@echo off
REM V4L (Vocal 4 Local) - Migration Script
REM Run this script to apply new migrations to an existing database

echo ========================================
echo V4L Migration Script
echo ========================================
echo.

if not exist "data\v4l.db" (
    echo ERROR: Database does not exist. Please run setup.bat first.
    pause
    exit /b 1
)

echo This will apply any new entity or process definitions to your existing database.
echo.
set /p confirm="Continue? (yes/no): "

if /i not "%confirm%"=="yes" (
    echo Operation cancelled.
    pause
    exit /b 0
)

echo.
echo Creating backup...
copy data\v4l.db data\v4l.db.backup.%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%

echo.
echo Applying migrations...

REM Check if there are new entity files to import
set /a count=0
for %%f in (metadata\entities\*.sql) do set /a count+=1

if %count% gtr 0 (
    echo Importing entity updates...

    REM Create temporary migration file for entities
    echo PRAGMA foreign_keys = OFF; > temp_migrate.sql
    echo BEGIN TRANSACTION; >> temp_migrate.sql
    for %%f in (metadata\entities\*.sql) do (
        echo -- Importing %%f >> temp_migrate.sql
        type "%%f" >> temp_migrate.sql
    )
    echo COMMIT; >> temp_migrate.sql
    echo PRAGMA foreign_keys = ON; >> temp_migrate.sql

    REM Apply entity migrations
    sqlite3 data\v4l.db < temp_migrate.sql 2>migration_errors.log
    del temp_migrate.sql
)

REM Check if there are new process files to import
set /a count=0
for %%f in (metadata\processes\*.sql) do set /a count+=1

if %count% gtr 0 (
    echo Importing process updates...

    REM Create temporary migration file for processes
    echo PRAGMA foreign_keys = OFF; > temp_migrate.sql
    echo BEGIN TRANSACTION; >> temp_migrate.sql
    for %%f in (metadata\processes\*.sql) do (
        echo -- Importing %%f >> temp_migrate.sql
        type "%%f" >> temp_migrate.sql
    )
    echo COMMIT; >> temp_migrate.sql
    echo PRAGMA foreign_keys = ON; >> temp_migrate.sql

    REM Apply process migrations
    sqlite3 data\v4l.db < temp_migrate.sql 2>>migration_errors.log
    del temp_migrate.sql
)

echo.
echo Migration complete!

REM Check for errors
if exist migration_errors.log (
    findstr /C:"Error:" migration_errors.log >nul
    if not errorlevel 1 (
        echo.
        echo WARNING: Some errors occurred. Check migration_errors.log for details.
        echo Your backup is available at: data\v4l.db.backup.*
    ) else (
        del migration_errors.log
    )
)

echo.
pause
