@echo off
echo Installing Laravel Ecommerce Store...
echo.

echo Step 1: Installing Composer dependencies...
composer install
if %errorlevel% neq 0 (
    echo Error: Failed to install Composer dependencies
    pause
    exit /b 1
)

echo.
echo Step 2: Installing Node.js dependencies...
npm install
if %errorlevel% neq 0 (
    echo Error: Failed to install Node.js dependencies
    pause
    exit /b 1
)

echo.
echo Step 3: Setting up environment...
if not exist .env (
    copy .env.example .env
    echo Environment file created
) else (
    echo Environment file already exists
)

echo.
echo Step 4: Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo Error: Failed to generate application key
    pause
    exit /b 1
)

echo.
echo Step 5: Running database migrations...
php artisan migrate
if %errorlevel% neq 0 (
    echo Error: Failed to run migrations
    echo Please check your database configuration in .env file
    pause
    exit /b 1
)

echo.
echo Step 6: Seeding database with sample data...
php artisan db:seed
if %errorlevel% neq 0 (
    echo Error: Failed to seed database
    pause
    exit /b 1
)

echo.
echo Step 7: Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo Error: Failed to build frontend assets
    pause
    exit /b 1
)

echo.
echo Installation completed successfully!
echo.
echo To start the application, run: php artisan serve
echo Then visit: http://localhost:8000
echo.
pause
