<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3-Migs Gowns & Barong</title>
    @vite(['ProfileDropdownHomepage/app.css', 'ProfileDropdownHomepage/app.js'])
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="font-sans text-gray-800 antialiased leading-normal bg-gray-100">
    <!-- Top Bar -->
    <div class="top-bar bg-black text-white py-2 text-sm text-center flex justify-between items-center px-4 md:px-20">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Content for top bar -->
            <span class="text-xs md:text-sm">Summer Sale For All Gowns & Barong And Free Express Delivery - OFF 50%!</span>
            <div class="language-selector text-xs md:text-sm">
                English <span class="ml-1 text-xs align-middle">&#9660;</span>
            </div>
        </div>
    </div>

    <!-- Header/Navigation Bar -->
    <header class="bg-white py-4 border-b border-gray-200 relative">
        <div class="container mx-auto flex justify-between items-center px-4 md:px-0">
            <div class="logo">
                <h1 class="text-xl md:text-2xl font-bold">3-Migs Gowns & Barong</h1>
            </div>
            <nav class="hidden md:block">
                <ul class="flex space-x-6">
                    <li><a href="#" class="text-gray-800 font-bold hover:text-red-500">Home</a></li>
                    <li><a href="#" class="text-gray-800 font-bold hover:text-red-500">Shop</a></li>
                    <li><a href="#" class="text-gray-800 font-bold hover:text-red-500">Categories</a></li>
                    <li><a href="#" class="text-gray-800 font-bold hover:text-red-500">About</a></li>
                    <li><a href="#" class="text-gray-800 font-bold hover:text-red-500">Contact</a></li>
                </ul>
            </nav>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search..." class="border border-gray-300 px-3 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <a href="#" class="text-gray-800 text-lg hover:text-red-500">🛒</a>
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <a href="#" class="text-gray-800 text-lg relative cursor-pointer hover:text-red-500" @click="open = !open">
                        <i class="fas fa-user"></i><span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs px-1">2</span>
                    </a>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-3 w-56 rounded-md shadow-lg bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-white hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-user-circle mr-3 text-gray-300"></i> Manage My Account
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-white hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-box mr-3 text-gray-300"></i> My Order
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-white hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-times-circle mr-3 text-gray-300"></i> My Cancellations
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-white hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-star mr-3 text-gray-300"></i> My Reviews
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-white hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-sign-out-alt mr-3 text-gray-300"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h2>Up to 10% off Voucher</h2>
                    <img src="https://via.placeholder.com/400x300?text=Product+Image" alt="Product Image">
                </div>
                <div class="hero-pagination">
                    <span>•</span><span>•</span><span>•</span>
                </div>
            </div>
        </section>

        <!-- Flash Sales Section -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold flex items-center">
                        <span class="w-2.5 h-7 bg-red-500 mr-2"></span> Flash Sales
                    </h2>
                    <div class="text-xl font-bold text-red-500">03 23 18 56</div>
                </div>
                <div class="flex overflow-x-auto pb-5 -mx-4">
                    <!-- Product Card 1 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+1" alt="Product 1" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name 1</h3>
                        <p class="font-bold text-red-500 mb-2">$100</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 2 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+2" alt="Product 2" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name 2</h3>
                        <p class="font-bold text-red-500 mb-2">$120</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 3 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+3" alt="Product 3" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name 3</h3>
                        <p class="font-bold text-red-500 mb-2">$90</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 4 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+4" alt="Product 4" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name 4</h3>
                        <p class="font-bold text-red-500 mb-2">$150</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Browse By Category Section -->
        <section class="py-12 text-center">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-center mb-8">
                    <h2 class="text-2xl font-bold flex items-center">
                        <span class="w-2.5 h-7 bg-red-500 mr-2"></span> Browse By Category
                    </h2>
                </div>
                <div class="flex flex-wrap justify-center gap-5">
                    <div class="w-32">
                        <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-2 flex items-center justify-center text-2xl">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <p class="text-sm">Ready to Wear</p>
                    </div>
                    <div class="w-32">
                        <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-2 flex items-center justify-center text-2xl">
                            <i class="fas fa-ring"></i>
                        </div>
                        <p class="text-sm">Bridal</p>
                    </div>
                    <div class="w-32">
                        <div class="w-20 h-20 bg-red-500 text-white rounded-full mx-auto mb-2 flex items-center justify-center text-2xl">
                            <i class="fas fa-male"></i>
                        </div>
                        <p class="text-sm">Barongs</p>
                    </div>
                    <div class="w-32">
                        <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-2 flex items-center justify-center text-2xl">
                            <i class="fas fa-female"></i>
                        </div>
                        <p class="text-sm">Gowns</p>
                    </div>
                    <div class="w-32">
                        <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-2 flex items-center justify-center text-2xl">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <p class="text-sm">Shipping</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Best Selling Products Section -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold flex items-center">
                        <span class="w-2.5 h-7 bg-red-500 mr-2"></span> Best Selling Products
                    </h2>
                    <button class="bg-red-500 text-white px-5 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">View All</button>
                </div>
                <div class="flex overflow-x-auto pb-5 -mx-4">
                    <!-- Product Card 1 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <img src="https://via.placeholder.com/200x250?text=Product+A" alt="Product A" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name A</h3>
                        <p class="font-bold text-red-500 mb-2">$110</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 2 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <img src="https://via.placeholder.com/200x250?text=Product+B" alt="Product B" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name B</h3>
                        <p class="font-bold text-red-500 mb-2">$130</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 3 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <img src="https://via.placeholder.com/200x250?text=Product+C" alt="Product C" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name C</h3>
                        <p class="font-bold text-red-500 mb-2">$95</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 4 -->
                    <div class="flex-none w-60 mx-4 bg-white border border-gray-200 p-4 text-center relative">
                        <img src="https://via.placeholder.com/200x250?text=Product+D" alt="Product D" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name D</h3>
                        <p class="font-bold text-red-500 mb-2">$160</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Elegance Banner -->
        <section class="bg-gray-800 text-white py-20 text-center relative overflow-hidden">
            <div class="container mx-auto px-4 flex flex-col items-center justify-center relative z-10">
                <h2 class="text-4xl font-bold mb-5 max-w-xl">Step into Elegance with 3-Migs Gowns & Barong</h2>
                <button class="bg-green-500 text-white px-8 py-3 text-lg cursor-pointer rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">Shop Now</button>
                <img src="https://via.placeholder.com/400x300?text=Elegant+Barong" alt="Elegant Barong" class="absolute right-0 bottom-0 h-full w-auto object-cover opacity-50">
            </div>
        </section>

        <!-- Explore Our Products Section -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-start mb-8">
                    <h2 class="text-2xl font-bold flex items-center">
                        <span class="w-2.5 h-7 bg-red-500 mr-2"></span> Explore Our Products
                    </h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    <!-- Product Card 1 -->
                    <div class="bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+E" alt="Product E" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name E</h3>
                        <p class="font-bold text-red-500 mb-2">$85</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 2 -->
                    <div class="bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+F" alt="Product F" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name F</h3>
                        <p class="font-bold text-red-500 mb-2">$115</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 3 -->
                    <div class="bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+G" alt="Product G" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name G</h3>
                        <p class="font-bold text-red-500 mb-2">$70</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 4 -->
                    <div class="bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+H" alt="Product H" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name H</h3>
                        <p class="font-bold text-red-500 mb-2">$140</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 5 -->
                    <div class="bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+I" alt="Product I" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name I</h3>
                        <p class="font-bold text-red-500 mb-2">$105</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="bg-white border border-gray-200 p-4 text-center relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1">Sale</span>
                        <img src="https://via.placeholder.com/200x250?text=Product+J" alt="Product J" class="max-w-full h-auto mb-2 mx-auto">
                        <h3 class="text-base font-semibold mb-2">Product Name J</h3>
                        <p class="font-bold text-red-500 mb-2">$125</p>
                        <div class="text-orange-400 mb-2">⭐⭐⭐⭐</div>
                        <button class="bg-gray-800 text-white px-4 py-2 text-sm">Add to Cart</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- New Arrival Section -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-start mb-8">
                    <h2 class="text-2xl font-bold flex items-center">
                        <span class="w-2.5 h-7 bg-red-500 mr-2"></span> New Arrival
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-gray-800 text-white p-8 min-h-[300px] flex flex-col justify-center items-center">
                        <h3 class="text-2xl font-bold mb-3">Placeholder Large Block</h3>
                        <p class="text-gray-300">Description for large block.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="bg-gray-800 text-white p-5 min-h-[140px] flex flex-col justify-center items-center relative overflow-hidden">
                            <h3 class="text-lg font-bold mt-0 mb-2">Menswear Collection</h3>
                            <p class="text-gray-300 text-sm">Discover our latest menswear collection.</p>
                        </div>
                        <div class="bg-gray-800 text-white p-5 min-h-[140px] flex flex-col justify-center items-center relative overflow-hidden">
                            <h3 class="text-lg font-bold mt-0 mb-2">Barongs</h3>
                            <p class="text-gray-300 text-sm">Elegant barongs for every occasion.</p>
                        </div>
                        <div class="bg-gray-800 text-white p-5 min-h-[140px] flex flex-col justify-center items-center relative overflow-hidden">
                            <h3 class="text-lg font-bold mt-0 mb-2">Gowns</h3>
                            <p class="text-gray-300 text-sm">Exquisite gowns for your special events.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-black text-white py-12 mt-8">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-4">
            <div>
                <h3 class="text-xl font-bold mb-4">3Migs Gowns & Barong</h3>
                <p class="text-sm">Subscribe</p>
                <p class="text-xs mb-4">Get 10% off your first order</p>
                <div class="flex">
                    <input type="email" placeholder="Enter your email" class="bg-gray-800 text-white text-sm px-4 py-2 rounded-l-md focus:outline-none border border-white">
                    <button class="bg-red-500 px-4 py-2 rounded-r-md hover:bg-red-600 border border-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-4">Support</h3>
                <p class="text-sm">Pandi, Bulacan</p>
                <p class="text-sm">3migs@gmail.com</p>
                <p class="text-sm">+639*********</p>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-4">Account</h3>
                <ul>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">My Account</a></li>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">Login / Register</a></li>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">Cart</a></li>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">Wishlist</a></li>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">Shop</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-4">Quick Link</h3>
                <ul>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">Privacy Policy</a></li>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">Terms Of Use</a></li>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-sm hover:underline">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-xs mt-8">
            <p class="text-gray-500">Copyright Group 6 2025. All right reserved</p>
        </div>
    </footer>

    <script>
        document.querySelector('.profile-icon').addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelector('.profile-dropdown').classList.toggle('show');
        });

        // Close the dropdown if the user clicks outside of it
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.profile-icon') && !event.target.closest('.profile-dropdown')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        });
    </script>
    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

