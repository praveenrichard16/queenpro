# Queen Pro - E-commerce Platform

Queen Pro is a complete e-commerce application built with Laravel featuring product catalog, shopping cart, order management, lead management, drip campaigns, WhatsApp integration, and more.

## Features

- **Product Management**: Browse products by category, search, and filter
- **Shopping Cart**: Add/remove items, update quantities, session-based cart
- **Order Management**: Complete checkout process with order tracking
- **Responsive Design**: Mobile-friendly Bootstrap-based UI
- **Admin Features**: Manage products, categories, and orders

## Installation

### Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js and NPM (for frontend assets)

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 2: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Database Configuration

1. Create a MySQL database (e.g., `queen_pro` or `ecommerce_store`)
2. Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=queen_pro
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Run Migrations and Seeders

```bash
# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### Step 5: Build Frontend Assets

```bash
# Build assets for production
npm run build

# Or run in development mode
npm run dev
```

### Step 6: Start the Application

```bash
# Start the development server
php artisan serve
```

Visit `http://localhost:8000` to see your ecommerce store!

## Usage

### Browsing Products

- Visit the home page to see featured products
- Use the "Products" menu to browse all products
- Filter by category, price range, or search terms
- Click on any product to view details

### Shopping Cart

- Add products to cart from product pages
- View cart contents and update quantities
- Remove items or clear the entire cart
- Proceed to checkout when ready

### Placing Orders

- Fill in customer and shipping information
- Choose payment method
- Review order summary
- Complete the order

### Managing Orders

- View order history
- Track order status
- View order details and items

## Database Structure

### Tables

- `categories` - Product categories
- `products` - Product information
- `orders` - Order details
- `order_items` - Individual items in orders
- `cart` - Shopping cart items (optional, uses sessions by default)

### Sample Data

The application comes with sample data including:
- 5 product categories
- 12 sample products
- Various product images (placeholder)

## Customization

### Adding New Products

1. Create a new product in the database
2. Add product images to the `public/images` directory
3. Update the product model if needed

### Styling

- Modify `resources/css/app.css` for custom styles
- Update `resources/views/layouts/app.blade.php` for layout changes
- Use Bootstrap classes for responsive design

### Features

- Add user authentication
- Implement payment gateways
- Add product reviews and ratings
- Create admin dashboard
- Add inventory management

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue on GitHub or contact the development team.

---

**Note**: This is a basic ecommerce implementation. For production use, consider adding security features, payment processing, user authentication, and admin functionality.
