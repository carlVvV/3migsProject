# Admin Dashboard - Three Migs

A modern, responsive admin dashboard built with Laravel, Tailwind CSS, and JavaScript. This dashboard provides comprehensive analytics and management tools for your e-commerce platform.

## Features

### 🎯 Core Dashboard Components
- **Statistics Cards**: Orders completed, pending, cancelled, and total users
- **Order Management**: Comprehensive order statistics with filtering and sorting
- **Website Analytics**: Visitor tracking, product views, and order metrics
- **Best Sellers**: Top-performing products with progress bars
- **Customer Retention**: Interactive charts showing retention rates over time

### 🚀 Interactive Features
- Real-time data filtering and sorting
- Responsive design for all devices
- Interactive charts using Chart.js
- Smooth animations and transitions
- Refresh functionality for live data updates

### 🎨 Design Features
- Modern, clean interface using Tailwind CSS
- Gradient sidebar with navigation
- Hover effects and smooth transitions
- Professional color scheme
- Mobile-responsive layout

## Installation & Setup

### 1. Prerequisites
- Laravel 10+ installed
- PHP 8.1+ 
- MySQL/PostgreSQL database
- Composer

### 2. File Structure
```
three-migs/
├── app/Http/Controllers/Admin/
│   └── DashboardController.php
├── resources/views/admin/
│   └── dashboard.blade.php
├── resources/css/
│   └── dashboard.css
└── routes/
    └── web.php
```

### 3. Database Setup
Ensure you have the following models and tables:
- `User` model with users table
- `Order` model with orders table
- `Product` model with products table
- `Category` model with categories table
- `OrderItem` model with order_items table

### 4. Routes
The dashboard is accessible at:
- Main dashboard: `/admin`
- API endpoints:
  - `/admin/dashboard-data` - Get dashboard statistics
  - `/admin/filtered-orders` - Get filtered/sorted orders
  - `/admin/customer-retention` - Get retention data

## Usage

### Accessing the Dashboard
1. Navigate to `/admin` in your browser
2. Ensure you're logged in with admin privileges
3. The dashboard will load with empty values initially

### Connecting to Database
The dashboard is designed to work with empty values initially. To connect to your database:

1. **Update the DashboardController**: Modify the methods in `DashboardController.php` to match your database schema
2. **Customize Queries**: Adjust the database queries in the controller methods
3. **Add Analytics**: Integrate with your analytics system for visitor tracking

### Key Methods to Customize

#### `getOrdersData()`
- Fetches order statistics for current and previous month
- Calculates percentage changes
- Customize based on your order status values

#### `getUsersData()`
- User count and new user registration
- Modify user creation date field if different

#### `getWebsiteStats()`
- Placeholder for visitor tracking
- Integrate with Google Analytics or similar services

#### `getBestSellers()`
- Product performance based on order items
- Adjust the join query based on your schema

#### `getCustomerRetention()`
- Monthly retention calculation
- Implement your retention logic

## Customization

### Styling
- **Colors**: Modify the CSS variables in `dashboard.css`
- **Layout**: Adjust the Tailwind classes in `dashboard.blade.php`
- **Charts**: Customize Chart.js options in the JavaScript

### Data Structure
The dashboard expects this data structure:

```javascript
{
  orders: {
    completed: 0,
    pending: 0,
    cancelled: 0,
    completedChange: 0,
    pendingChange: 0,
    cancelledChange: 0
  },
  users: {
    total: 0,
    change: 0
  },
  website: {
    visitors: 0,
    productViews: 0,
    newOrders: 0,
    cancelled: 0
  },
  bestSellers: [],
  orderStats: []
}
```

### Adding New Features
1. **New API Endpoint**: Add route in `web.php`
2. **Controller Method**: Create method in `DashboardController`
3. **Frontend Integration**: Update JavaScript in dashboard view
4. **Styling**: Add CSS classes as needed

## API Endpoints

### GET `/admin/dashboard-data`
Returns comprehensive dashboard statistics.

**Response:**
```json
{
  "success": true,
  "data": {
    "orders": {...},
    "users": {...},
    "website": {...},
    "bestSellers": [...],
    "orderStats": [...]
  }
}
```

### GET `/admin/filtered-orders`
Returns filtered and sorted orders.

**Parameters:**
- `status`: Order status filter (all, completed, pending, cancelled)
- `sort`: Sort order (date, amount, status)

### GET `/admin/customer-retention`
Returns customer retention data for charts.

## Troubleshooting

### Common Issues

1. **Empty Dashboard**
   - Check if routes are properly configured
   - Verify admin middleware is working
   - Check browser console for JavaScript errors

2. **Data Not Loading**
   - Verify database connections
   - Check if models exist and are properly configured
   - Review API endpoint responses

3. **Styling Issues**
   - Ensure `dashboard.css` is accessible
   - Check if Tailwind CSS is loading
   - Verify Font Awesome icons are loading

### Debug Mode
Enable Laravel debug mode to see detailed error messages:
```php
// .env
APP_DEBUG=true
```

## Performance Optimization

### Database Queries
- Add database indexes on frequently queried fields
- Use eager loading for relationships
- Implement query caching for static data

### Frontend Optimization
- Minify CSS and JavaScript in production
- Use CDN for external libraries
- Implement lazy loading for charts

## Security Considerations

1. **Admin Access**: Ensure proper authentication and authorization
2. **SQL Injection**: Use Laravel's query builder and Eloquent ORM
3. **XSS Protection**: Laravel provides built-in protection
4. **CSRF Protection**: Enabled by default in Laravel

## Browser Support

- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This dashboard is part of the Three Migs project. Please refer to your project's license terms.

## Support

For support and questions:
1. Check the troubleshooting section
2. Review Laravel documentation
3. Check browser console for errors
4. Verify database connectivity

---

**Note**: This dashboard is designed to work with empty values initially. All data connections and business logic should be implemented according to your specific requirements and database schema.
