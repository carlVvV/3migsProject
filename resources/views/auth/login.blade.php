<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Top Header */
        .top-header {
            background-color: #000;
            color: white;
            padding: 8px 0;
        }
        
        .top-header-content {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .sale-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .shop-now {
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
        }
        
        /* Main Header */
        .main-header {
            background-color: white;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .main-header-content {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .brand-name {
            font-size: 25px;
            font-weight: bold;
            color: #000;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
        }
        
        .nav-link {
            color: #000;
            text-decoration: none;
            font-size: 13px;
            position: relative;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #000;
        }
        
        .search-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .search-box {
            background-color: #f5f5f5;
            border-radius: 4px;
            padding: 6px 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            width: 200px;
        }
        
        .search-input {
            background: transparent;
            border: none;
            outline: none;
            font-size: 13px;
            color: #000;
            flex: 1;
        }
        
        .search-icon {
            width: 24px;
            height: 24px;
            color: #000;
        }
        
        .header-icons {
            display: flex;
            gap: 20px;
        }
        
        .header-icon {
            width: 24px;
            height: 24px;
            color: #000;
            cursor: pointer;
        }
        
        /* Main Content */
        .main-content {
            display: flex;
            min-height: calc(100vh - 300px);
            margin: 40px 0;
            padding: 0 40px;
        }
        
        .side-image {
            width: 45%;
            background-color: #cbe3e8;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            margin-right: 20px;
        }
        
        .side-image img {
            width: 100%;
            height: 100%;
            margin-right: 20px;
            object-fit: cover;
            object-position: center;
        }
        
        .login-form-section {
            width: 70%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            margin-left: 20px;
        }
        
        .login-form-container {
            width: 100%;
            max-width: 400px;
        }
        
        .form-header {
            margin-bottom: 25px;
            text-align: center;
        }
        
        .form-title {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin-bottom: 12px;
        }
        
        .form-subtitle {
            font-size: 14px;
            color: #000;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            color: #000;
            margin-bottom: 5px;
        }
        
        .form-input {
            width: 100%;
            border: none;
            border-bottom: 2px solid #000;
            background: transparent;
            padding: 5px 0;
            font-size: 13px;
            outline: none;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .login-button {
            background-color: #db4444;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 30px;
            font-size: 14px;
            cursor: pointer;
            min-width: 100px;
        }
        
        .forgot-password {
            color: #db4444;
            text-decoration: none;
            font-size: 14px;
        }
        
        .signup-link {
            text-align: center;
            color: #000;
            font-size: 13px;
        }
        
        .signup-link a {
            color: #db4444;
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            background-color: #000;
            color: white;
            padding: 30px 0 15px;
            margin-top: 40px;
        }
        
        .footer-content {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-main {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 20px;
        }
        
        .footer-column h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: white;
        }
        
        .footer-column p, .footer-column a {
            color: #fafafa;
            text-decoration: none;
            margin-bottom: 10px;
            display: block;
            font-size: 13px;
        }
        
        .footer-column a:hover {
            color: #db4444;
        }
        
        .subscribe-form {
            display: flex;
            align-items: center;
            border: 1.5px solid #fafafa;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 10px;
        }
        
        .subscribe-input {
            background: transparent;
            border: none;
            outline: none;
            color: #fafafa;
            flex: 1;
            font-size: 13px;
        }
        
        .subscribe-input::placeholder {
            color: #fafafa;
        }
        
        .send-icon {
            width: 18px;
            height: 18px;
            color: #fafafa;
            cursor: pointer;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-icon {
            width: 18px;
            height: 18px;
            color: #fafafa;
            cursor: pointer;
        }
        
        .footer-bottom {
            border-top: 1px solid #fafafa;
            padding-top: 10px;
            text-align: center;
        }
        
        .copyright {
            color: #fafafa;
            font-size: 13px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .footer-main {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                height: auto;
            }
            
            .side-image, .login-form-section {
                width: 100%;
            }
            
            .side-image {
                height: 250px;
            }
            
            .login-form-section {
                padding: 30px 20px;
            }
            
            .main-header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                gap: 20px;
            }
            
            .search-section {
                flex-direction: column;
                gap: 12px;
            }
        }
        
        @media (max-width: 640px) {
            .footer-main {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .top-header-content, .main-header-content {
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="top-header-content">
            <div class="sale-info">
                <span>Summer Sale For All Gown & Barong And Free Express Delivery</span>
                <span class="shop-now">ShopNow</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">
        <div class="main-header-content">
            <div class="brand-name">3Migs Gowns & Barong</div>
            
            <nav class="nav-links">
                <a href="#" class="nav-link active">Home</a>
                <a href="#" class="nav-link">Contact</a>
                <a href="#" class="nav-link">About</a>
                <a href="#" class="nav-link">Sign Up</a>
            </nav>
            
            <div class="search-section">
                <div class="search-box">
                    <input type="text" placeholder="What are you looking for?" class="search-input">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <div class="header-icons">
                    <div class="header-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="header-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                    </div>
                    <div class="header-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Left Side - Image -->
        <div class="side-image">
            <img src="{{ asset('build/images/auth/shopping-image.png') }}" alt="Shopping">
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-form-section">
            <div class="login-form-container">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">
                        <p class="text-sm text-green-800">{{ session('status') }}</p>
                    </div>
                @endif

                <!-- Form Header -->
                <div class="form-header">
                    <h1 class="form-title">Log in to 3Migs Gowns & Barong</h1>
                    <p class="form-subtitle">Enter your details below</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label class="form-label">Email or Phone Number</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            class="form-input"
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            class="form-input"
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="login-button">
                            Log In
                        </button>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                Forgot Password?
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Register Link -->
                <div class="signup-link">
                    <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <div class="footer-main">
                <!-- Column 1: Brand & Subscribe -->
                <div class="footer-column">
                    <h3>3Migs Gowns & Barong</h3>
                    <p>Subscribe</p>
                    <div class="subscribe-form">
                        <input type="email" placeholder="Enter your email" class="subscribe-input">
                        <svg class="send-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                </div>

                <!-- Column 2: Support -->
                <div class="footer-column">
                    <h3>Support</h3>
                    <p>Pandi, Bulacan</p>
                    <p>3migs@gmail.com</p>
                    <p>+639*********</p>
                </div>

                <!-- Column 3: Account -->
                <div class="footer-column">
                    <h3>Account</h3>
                    <a href="#">My Account</a>
                    <a href="#">Login / Register</a>
                    <a href="#">Cart</a>
                    <a href="#">Wishlist</a>
                    <a href="#">Shop</a>
                </div>

                <!-- Column 4: Quick Links & App -->
                <div class="footer-column">
                    <h3>Quick Link</h3>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms Of Use</a>
                    <a href="#">FAQ</a>
                    <a href="#">Contact</a>
                    
                    <div class="social-icons">
                        <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.665 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                        </svg>
                        <svg class="social-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p class="copyright">Copyright Group 6 2025. All right reserved</p>
            </div>
        </div>
    </div>
</body>
</html>
