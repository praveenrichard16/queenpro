# Installation Guide

## Quick Start

1. **Install Composer** (if not already installed):
   - Download from https://getcomposer.org/download/
   - Follow the installation instructions for Windows

2. **Install Node.js** (if not already installed):
   - Download from https://nodejs.org/
   - Install the LTS version

3. **Set up the database**:
   - Create a MySQL database named `ecommerce_store`
   - Update the database credentials in `.env` file

4. **Run the installation script**:
   ```bash
   # Windows
   install.bat
   
   # Or manually run these commands:
   composer install
   npm install
   copy .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   npm run build
   ```

5. **Start the application**:
   ```bash
   php artisan serve
   ```

6. **Visit the application**:
   - Open your browser and go to `http://localhost:8000`

## Manual Installation

If the installation script doesn't work, follow these steps manually:

### Step 1: Install Dependencies
```bash
composer install
npm install
```

### Step 2: Environment Setup
```bash
copy .env.example .env
php artisan key:generate
```

### Step 3: Database Setup
1. Create a MySQL database named `ecommerce_store`
2. Update `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecommerce_store
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

### Step 4: Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### Step 5: Build Frontend Assets
```bash
npm run build
```

### Step 6: Start the Application
```bash
php artisan serve
```

## Troubleshooting

### Composer Issues
- Make sure PHP is in your system PATH
- Try running `php -v` to check if PHP is working
- Download Composer manually from https://getcomposer.org/download/

### Database Issues
- Make sure MySQL is running
- Check database credentials in `.env` file
- Ensure the database `ecommerce_store` exists

### Node.js Issues
- Make sure Node.js and NPM are installed
- Try running `node -v` and `npm -v` to check installation

### Permission Issues
- Make sure the `storage` and `bootstrap/cache` directories are writable
- On Windows, you might need to run as administrator

## Features

Once installed, you'll have access to:
- Product catalog with categories
- Shopping cart functionality
- Order management system
- Responsive design
- Sample data for testing

## Next Steps

After installation, consider:
- Adding user authentication
- Implementing payment gateways
- Adding admin functionality
- Customizing the design
- Adding more features

For more information, see the main README.md file.
