# Frontend Integration Guide

## 🚀 Frontend Successfully Integrated!

Your React/Vite frontend from [https://github.com/AshCodes01/Capstone.git](https://github.com/AshCodes01/Capstone.git) has been successfully integrated with your Laravel backend.

## 📁 Project Structure

```
three-migs/
├── app/Http/Controllers/Api/     # API Controllers for frontend
├── public/frontend/              # Frontend static files
│   ├── Homepage/
│   ├── Login/
│   ├── Signup/
│   ├── Cart/
│   ├── CheckOut/
│   └── ... (all frontend pages)
├── routes/api.php                # API routes
└── routes/web.php               # Web routes including frontend
```

## 🌐 Access Your Frontend

### **Frontend Pages:**
- **Homepage:** http://127.0.0.1:8000/frontend/Homepage
- **Login:** http://127.0.0.1:8000/frontend/Login
- **Signup:** http://127.0.0.1:8000/frontend/Signup
- **Cart:** http://127.0.0.1:8000/frontend/Cart
- **Checkout:** http://127.0.0.1:8000/frontend/CheckOut
- **Product Details:** http://127.0.0.1:8000/frontend/ProductDetails
- **Account:** http://127.0.0.1:8000/frontend/Account

### **Admin Panel (Laravel):**
- **Admin Dashboard:** http://127.0.0.1:8000/admin/dashboard
- **Login:** http://127.0.0.1:8000/login

## 🔌 API Endpoints

### **Authentication:**
- `POST /api/v1/login` - User login
- `POST /api/v1/register` - User registration
- `POST /api/v1/logout` - User logout
- `POST /api/v1/forgot-password` - Forgot password
- `POST /api/v1/reset-password` - Reset password

### **Products:**
- `GET /api/v1/products` - Get all products
- `GET /api/v1/products/{id}` - Get single product
- `GET /api/v1/products/category/{category}` - Get products by category
- `GET /api/v1/categories` - Get all categories

### **Cart:**
- `GET /api/v1/cart` - Get cart contents
- `POST /api/v1/cart/add` - Add item to cart
- `PUT /api/v1/cart/update/{id}` - Update cart item
- `DELETE /api/v1/cart/remove/{id}` - Remove cart item
- `DELETE /api/v1/cart/clear` - Clear cart

### **Orders (Protected):**
- `GET /api/v1/orders` - Get user orders
- `POST /api/v1/orders` - Create new order
- `GET /api/v1/orders/{id}` - Get order details

## 🔧 Frontend Integration Steps

### **1. Update Frontend API Calls**

In your frontend JavaScript files, update API calls to use the Laravel backend:

```javascript
// Example: Login API call
const loginUser = async (email, password) => {
    const response = await fetch('http://127.0.0.1:8000/api/v1/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
    });
    
    const data = await response.json();
    if (data.success) {
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));
    }
    return data;
};

// Example: Get products
const getProducts = async () => {
    const response = await fetch('http://127.0.0.1:8000/api/v1/products');
    const data = await response.json();
    return data.data;
};
```

### **2. Add Authentication Headers**

For protected routes, include the authentication token:

```javascript
const getAuthHeaders = () => {
    const token = localStorage.getItem('token');
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };
};
```

### **3. Update Form Actions**

Update your HTML forms to use JavaScript instead of direct form submission:

```html
<!-- Instead of direct form action -->
<form onsubmit="handleLogin(event)">
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

<script>
function handleLogin(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    loginUser(formData.get('email'), formData.get('password'));
}
</script>
```

## 🚀 Running the Project

### **Start Laravel Server:**
```bash
cd three-migs
php artisan serve
```

### **Start Frontend Development (Optional):**
```bash
cd frontend
npm run dev
```

## 🔐 Admin Access

- **Email:** admin@example.com
- **Password:** password
- **URL:** http://127.0.0.1:8000/login

## 📝 Next Steps

1. **Update frontend JavaScript** to use the API endpoints
2. **Add error handling** for API responses
3. **Implement loading states** for better UX
4. **Add form validation** on the frontend
5. **Test all functionality** end-to-end

## 🛠️ Development Tips

- Use browser developer tools to debug API calls
- Check Laravel logs in `storage/logs/laravel.log`
- Use Postman or similar tools to test API endpoints
- Monitor network requests in browser dev tools

Your frontend is now successfully integrated with the Laravel backend! 🎉
