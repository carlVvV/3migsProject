# Three Migs - E-commerce Platform

A full-stack e-commerce platform built with Laravel 12 and modern frontend technologies.

## Features

- **User Authentication**: Secure login/registration with Laravel Breeze
- **Role-based Access Control**: Admin and customer roles with Spatie Laravel Permission
- **Product Management**: Complete product catalog with variants and inventory
- **Shopping Cart**: Persistent cart functionality
- **Order Management**: Order processing and tracking
- **API Integration**: RESTful API with Laravel Sanctum authentication
- **Modern Frontend**: Responsive design with Tailwind CSS
- **Admin Dashboard**: Comprehensive admin panel for store management

## Tech Stack

### Backend
- **Laravel 12.0** - PHP Framework
- **PHP 8.2+** - Server-side language
- **SQLite** - Database (configurable)
- **Laravel Sanctum** - API Authentication
- **Spatie Laravel Permission** - Role & Permission Management
- **Laravel Breeze** - Authentication scaffolding

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Build tool and development server

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd three-migs
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=RolesAndAdminSeeder
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

## Running the Application

### Development Mode
```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server (for development)
npm run dev
```

### Production Mode
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Access Points

### Frontend Pages
- **Homepage**: http://127.0.0.1:8000/Homepage
- **Login**: http://127.0.0.1:8000/Login
- **Signup**: http://127.0.0.1:8000/Signup
- **Cart**: http://127.0.0.1:8000/Cart
- **Checkout**: http://127.0.0.1:8000/CheckOut
- **Product Details**: http://127.0.0.1:8000/ProductDetails
- **Account**: http://127.0.0.1:8000/Account

### Admin Panel
- **Admin Login**: http://127.0.0.1:8000/login
- **Admin Dashboard**: http://127.0.0.1:8000/admin/dashboard

### API Endpoints
- **Health Check**: http://127.0.0.1:8000/api/health
- **Products**: http://127.0.0.1:8000/api/v1/products
- **Authentication**: http://127.0.0.1:8000/api/v1/login

## Default Admin Credentials

- **Email**: `admin@example.com`
- **Password**: `password`

## API Documentation

### Authentication Endpoints
- `POST /api/v1/register` - User registration
- `POST /api/v1/login` - User login
- `POST /api/v1/logout` - User logout (requires authentication)

### Product Endpoints
- `GET /api/v1/products` - Get all products
- `GET /api/v1/products/{id}` - Get specific product
- `GET /api/v1/products/category/{category}` - Get products by category
- `GET /api/v1/categories` - Get all categories

### Cart Endpoints (Authenticated)
- `GET /api/v1/cart` - Get user's cart
- `POST /api/v1/cart/add` - Add item to cart
- `PUT /api/v1/cart/update/{id}` - Update cart item
- `DELETE /api/v1/cart/remove/{id}` - Remove cart item
- `DELETE /api/v1/cart/clear` - Clear cart

### Order Endpoints (Authenticated)
- `GET /api/v1/orders` - Get user's orders
- `POST /api/v1/orders` - Create new order
- `GET /api/v1/orders/{id}` - Get specific order

## Project Structure

```
three-migs/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/          # API controllers
│   │   │   └── ...           # Web controllers
│   │   └── Middleware/       # Custom middleware
│   ├── Models/               # Eloquent models
│   └── ...
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/             # Database seeders
├── public/                   # Public assets and frontend
│   ├── Homepage/            # Homepage files
│   ├── Login/               # Login page
│   ├── Cart/                # Shopping cart
│   └── ...                  # Other frontend pages
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
└── ...
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).