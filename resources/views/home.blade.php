<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Three Migs - Premium Gowns & Barong') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg p-8 mb-8 text-white">
                <div class="text-center">
                    <h1 class="text-4xl font-bold mb-4">Welcome to Three Migs</h1>
                    <p class="text-xl mb-6">Discover our exclusive collection of wedding gowns, barong tagalog, and evening wear</p>
                    <a href="#products" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                        Shop Now
                    </a>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Shop by Category</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($categories as $category)
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition duration-300">
                        <div class="w-16 h-16 bg-purple-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-2xl">👗</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                        <a href="#" class="text-purple-600 hover:text-purple-800 font-medium">Browse →</a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Featured Products Section -->
            <div id="products" class="mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Featured Products</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-4xl">🖼️</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $product->short_description }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-purple-600">₱{{ number_format($product->base_price, 2) }}</span>
                                <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="flex-1 bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300">
                                    Add to Cart
                                </button>
                                <button class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300">
                                    View
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <span class="text-2xl">✂️</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Custom Tailoring</h3>
                    <p class="text-gray-600">Perfect fit guaranteed with our expert tailoring services</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <span class="text-2xl">🚚</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Free Shipping</h3>
                    <p class="text-gray-600">Free shipping on orders over ₱5,000</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <span class="text-2xl">💳</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Secure Payment</h3>
                    <p class="text-gray-600">Multiple payment options including GCash and COD</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
