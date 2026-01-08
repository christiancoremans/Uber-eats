@extends('layouts.app')

@section('title', $restaurant->restaurant_name ?? $restaurant->name)

@section('content')
<div class="restaurant-menu-container">
    <!-- Left Side - Main Content (75%) -->
    <div class="menu-main">
        <!-- Banner Section -->
        <div class="restaurant-banner">
            @if($restaurant->getFirstMediaUrl('banner'))
                <img src="{{ $restaurant->getFirstMediaUrl('banner') }}" alt="{{ $restaurant->restaurant_name ?? $restaurant->name }}" class="banner-image">
            @else
                <div class="banner-placeholder">
                    <span>🏪 {{ $restaurant->restaurant_name ?? $restaurant->name }}</span>
                </div>
            @endif
        </div>

        <!-- Restaurant Info -->
        <div class="restaurant-info-header">
            <div class="restaurant-logo-name">
                @if(Auth::user()->getFirstMediaUrl('profile_picture'))
                        <img src="{{ Auth::user()->getFirstMediaUrl('profile_picture') }}" alt="{{ Auth::user()->name }}" class="profile-img" width="100px">
                @else
                    <div class="logo-placeholder">
                        <span>🍽️</span>
                    </div>
                @endif
                <div class="restaurant-name-location">
                    <h1>{{ $restaurant->restaurant_name ?? $restaurant->name }}</h1>
                    <div class="restaurant-location">
                        <span class="separator">|</span>
                        <span class="city">{{ $restaurant->city }}</span>
                    </div>
                </div>
            </div>

            <div class="restaurant-rating">
                <div class="stars">
                    @php
                        $averageRating = $restaurant->reviews()->avg('rating') ?? 0;
                        $reviewCount = $restaurant->reviews()->count();
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= round($averageRating) ? 'filled' : '' }}">★</span>
                    @endfor
                    <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
                    <span class="review-count">({{ $reviewCount }} beoordelingen)</span>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="menu-search">
            <div class="search-container">
                <input type="text" id="menu-search-input" placeholder="Zoek gerechten of categorieën...">
                <button type="button" id="menu-search-btn">🔍</button>
            </div>
        </div>

        <!-- Categories Navigation -->
        <div class="categories-navigation">
            <button type="button" class="nav-arrow left-arrow" id="prev-categories">‹</button>
            <div class="categories-scroll" id="categories-scroll">
                @foreach($categories as $category)
                    <button type="button" class="category-tab" data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
            <button type="button" class="nav-arrow right-arrow" id="next-categories">›</button>
        </div>

        <!-- Menu Items by Category -->
        <div class="menu-items-container">
            @foreach($menuByCategory as $categoryName => $items)
                <div class="menu-category-section" data-category="{{ $items[0]->category_id ?? 'other' }}">
                    <h2 class="category-title">{{ $categoryName }}</h2>
                    <div class="menu-items-grid">
                        @foreach($items as $item)
                            <div class="menu-item-card" onclick="addToCart({{ json_encode([
                                'id' => $item->id,
                                'name' => $item->name,
                                'price' => $item->price,
                                'description' => $item->description
                            ]) }})">
                                <div class="menu-item-image">
                                    @if($item->getFirstMediaUrl('menu_item_images'))
                                        <img src="{{ $item->getFirstMediaUrl('menu_item_images') }}" alt="{{ $item->name }}">
                                    @else
                                        <div class="menu-item-placeholder">
                                            <span>🍴</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="menu-item-info">
                                    <h3 class="item-name">{{ $item->name }}</h3>
                                    <p class="item-price">€{{ number_format($item->price, 2) }}</p>
                                    <p class="item-description">{{ $item->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        @livewire('restaurant-review', ['restaurantId' => $restaurant->id])
    </div>

    <!-- Right Side - Shopping Cart (25%) -->
<div class="shopping-cart">
    <div class="cart-header">
        <h2>🛒 Winkelwagen</h2>
    </div>

    <div class="cart-items" id="cart-items">
        <!-- Cart items will be added here dynamically -->
        <div class="empty-cart">
            <p>Je winkelwagen is leeg</p>
            <p>Voeg items toe aan je bestelling</p>
        </div>
    </div>

    <!-- Total and Checkout Section - Always Visible -->
    <div class="cart-total-section">
        <div class="total-line"></div>
        <div class="total-row">
            <span class="total-label">Totaal</span>
            <span class="total-amount" id="cart-total">€0.00</span>
        </div>
        <button type="button" class="checkout-btn" id="checkout-btn" disabled>Bestellen</button>
    </div>
</div>

<style>
.restaurant-menu-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
    display: grid;
    grid-template-columns: 75% 25%;
    gap: 2rem;
}

/* Left Side Styles */
.menu-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.restaurant-banner {
    width: 100%;
    height: 200px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.banner-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ff4d4d, #ff8a00);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: bold;
}

.restaurant-info-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 2px solid #f0f0f0;
}

.restaurant-logo-name {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.restaurant-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.logo-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff4d4d, #ff8a00);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.restaurant-name-location h1 {
    margin: 0;
    color: #333;
    font-size: 2rem;
}

.restaurant-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.25rem;
    color: #666;
}

.separator {
    color: #ccc;
}

.restaurant-rating .stars {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.star {
    color: #e0e0e0;
    font-size: 1.2rem;
}

.star.filled {
    color: #ffd700;
}

.rating-number {
    font-weight: 600;
    color: #333;
    margin-left: 0.5rem;
}

.review-count {
    color: #666;
    font-size: 0.9rem;
    margin-left: 0.25rem;
}

.menu-search {
    padding: 1rem 0;
}

.search-container {
    display: flex;
    gap: 0.5rem;
}

.search-container input {
    flex: 1;
    padding: 0.875rem 1.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.search-container input:focus {
    outline: none;
    border-color: #ff4d4d;
}

.search-container button {
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
    border: none;
    border-radius: 25px;
    width: 50px;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.search-container button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
}

.categories-navigation {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.nav-arrow {
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.2rem;
    color: #666;
    transition: all 0.3s ease;
}

.nav-arrow:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}

.categories-scroll {
    flex: 1;
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    padding: 0.5rem 0;
    scroll-behavior: smooth;
}

.categories-scroll::-webkit-scrollbar {
    display: none;
}

.category-tab {
    padding: 0.5rem 1rem;
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 20px;
    white-space: nowrap;
    cursor: pointer;
    color: #666;
    font-weight: 500;
    transition: all 0.3s ease;
}

.category-tab:hover,
.category-tab.active {
    background: #ff4d4d;
    color: white;
    border-color: #ff4d4d;
}

.menu-items-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    margin-top: 1rem;
}

.category-title {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.menu-items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.menu-item-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    display: grid;
    grid-template-columns: 100px 1fr;
    gap: 1rem;
    min-height: 120px;
}

.menu-item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.menu-item-image {
    height: 120px;
    overflow: hidden;
}

.menu-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.menu-item-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ff4d4d, #ff8a00);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.menu-item-info {
    padding: 1rem 1rem 1rem 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.item-name {
    margin: 0;
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
}

.item-price {
    margin: 0.5rem 0;
    color: #ff4d4d;
    font-size: 1.1rem;
    font-weight: 700;
}

.item-description {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* Right Side - Shopping Cart Styles */
.shopping-cart {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    height: calc(100vh - 4rem);
    position: sticky;
    top: 2rem;
}

.cart-header {
    padding: 1.5rem;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-header h2 {
    margin: 0;
    color: #333;
    font-size: 1.5rem;
}

.cart-items {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
}

.empty-cart {
    text-align: center;
    padding: 3rem 1rem;
    color: #999;
}

.empty-cart p {
    margin: 0.5rem 0;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    margin-bottom: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.cart-item-name {
    font-weight: 500;
    color: #333;
}

.cart-item-price {
    color: #ff4d4d;
    font-weight: 600;
    margin-left: 10px;
}


.checkout-btn {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
}

.checkout-btn:disabled {
    background: #e0e0e0;
    color: #999;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Cart Item Quantity Controls */
.cart-item {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.cart-item-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-item-name {
    font-weight: 500;
    color: #333;
    font-size: 0.95rem;
}

.cart-item-price {
    color: #ff4d4d;
    font-weight: 600;
    font-size: 0.95rem;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: white;
    border-radius: 6px;
    padding: 0.25rem;
    border: 1px solid #e0e0e0;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: #f8f9fa;
    border-radius: 4px;
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.quantity-btn:hover {
    background: #e9ecef;
}

.quantity-btn.minus {
    color: #ff4d4d;
}

.quantity-btn.plus {
    color: #28a745;
}

.quantity-display {
    font-weight: 600;
    color: #333;
    min-width: 40px;
    text-align: center;
    font-size: 1rem;
}

.remove-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: #fff5f5;
    border-radius: 4px;
    font-size: 1rem;
    color: #ff4d4d;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.remove-btn:hover {
    background: #ffe6e6;
}

/* Cart Summary */
.cart-summary {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-top: 1rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    color: #666;
}

.summary-row.total {
    border-top: 2px solid #e0e0e0;
    margin-top: 0.5rem;
    padding-top: 0.75rem;
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
}

/* Total and Checkout Section */
.cart-total-section {
    padding: 1.5rem;
    background: white;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
}

.total-line {
    height: 1px;
    background: #e0e0e0;
    margin: 0 0 1rem 0;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.75rem 0;
}

.total-label {
    font-weight: 600;
    color: #333;
    font-size: 1.2rem;
}

.total-amount {
    font-weight: 700;
    color: #ff4d4d;
    font-size: 1.3rem;
}

.checkout-btn {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.checkout-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
}

.checkout-btn:disabled {
    background: #e0e0e0;
    color: #999;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .restaurant-menu-container {
        grid-template-columns: 1fr;
    }

    .shopping-cart {
        height: auto;
        position: static;
    }

    .menu-items-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .restaurant-info-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .restaurant-logo-name {
        width: 100%;
    }

    .categories-navigation {
        overflow-x: auto;
    }

    .menu-item-card {
        grid-template-columns: 1fr;
        grid-template-rows: 150px 1fr;
    }

    .menu-item-info {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .restaurant-menu-container {
        padding: 1rem;
    }

    .restaurant-banner {
        height: 150px;
    }

    .restaurant-logo {
        width: 60px;
        height: 60px;
    }

    .logo-placeholder {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .restaurant-name-location h1 {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Cart state
let cart = [];

// Find item in cart
function findCartItem(itemId) {
    return cart.find(item => item.id === itemId);
}

// Add item to cart
function addToCart(item) {
    const existingItem = findCartItem(item.id);

    if (existingItem) {
        // Increase quantity if item already exists
        existingItem.quantity += 1;
    } else {
        // Add new item to cart
        cart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: 1
        });
    }

    // Update cart display
    updateCartDisplay();

    // Show notification
    showNotification(`${item.name} toegevoegd aan winkelwagen`);
}

// Remove item from cart
function removeFromCart(itemId) {
    const itemIndex = cart.findIndex(item => item.id === itemId);

    if (itemIndex !== -1) {
        cart.splice(itemIndex, 1);
        updateCartDisplay();
    }
}

// Update item quantity
function updateQuantity(itemId, newQuantity) {
    const item = findCartItem(itemId);

    if (item) {
        if (newQuantity < 1) {
            removeFromCart(itemId);
            return;
        }

        item.quantity = newQuantity;
        updateCartDisplay();
    }
}

// Update cart display
function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    const deliveryCost = 0; // Fixed delivery cost

    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart">
                <p>Je winkelwagen is leeg</p>
                <p>Voeg items toe aan je bestelling</p>
            </div>
        `;
        checkoutBtn.disabled = true;
        // Reset total to €0.00
        cartTotal.textContent = `€0.00`;
    } else {
        let cartHTML = '';
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            cartHTML += `
                <div class="cart-item" data-id="${item.id}">
                    <div class="cart-item-info">
                        <span class="cart-item-name">${item.name}</span>
                        <span class="cart-item-price">€${itemTotal.toFixed(2)}</span>
                    </div>
                    <div class="cart-item-quantity">
                        <button type="button" class="quantity-btn minus" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">−</button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button type="button" class="quantity-btn plus" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                        <button type="button" class="remove-btn" onclick="removeFromCart(${item.id})">🗑️</button>
                    </div>
                </div>
            `;
        });
        cartItems.innerHTML = cartHTML;
        checkoutBtn.disabled = false;

        // Calculate totals
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const totalWithDelivery = subtotal + deliveryCost;

        // Update total amount
        cartTotal.textContent = `€${totalWithDelivery.toFixed(2)}`;
    }
}

// Show notification
function showNotification(message) {
    // Remove existing notifications
    document.querySelectorAll('.notification').forEach(n => n.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(90deg, #ff4d4d, #ff8a00);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
        z-index: 1000;
        animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s;
    `;

    // Add styles for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Add to page
    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Categories navigation
document.addEventListener('DOMContentLoaded', function() {
    const categoriesScroll = document.getElementById('categories-scroll');
    const prevBtn = document.getElementById('prev-categories');
    const nextBtn = document.getElementById('next-categories');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const menuSections = document.querySelectorAll('.menu-category-section');

    // Scroll categories
    if (prevBtn && nextBtn && categoriesScroll) {
        prevBtn.addEventListener('click', () => {
            categoriesScroll.scrollBy({ left: -200, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            categoriesScroll.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }

    // Category tab click
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category');

            // Update active tab
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Scroll to category section
            const targetSection = document.querySelector(`.menu-category-section[data-category="${categoryId}"]`);
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('menu-search-input');
    const searchBtn = document.getElementById('menu-search-btn');

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();

        if (searchTerm === '') {
            // Show all items
            menuSections.forEach(section => {
                section.style.display = 'block';
                const items = section.querySelectorAll('.menu-item-card');
                items.forEach(item => item.style.display = 'grid');
            });
            return;
        }

        // Search through menu items
        menuSections.forEach(section => {
            let hasMatches = false;
            const items = section.querySelectorAll('.menu-item-card');

            items.forEach(item => {
                const itemName = item.querySelector('.item-name').textContent.toLowerCase();
                const itemDesc = item.querySelector('.item-description').textContent.toLowerCase();

                if (itemName.includes(searchTerm) || itemDesc.includes(searchTerm)) {
                    item.style.display = 'grid';
                    hasMatches = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide category section based on matches
            section.style.display = hasMatches ? 'block' : 'none';
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', performSearch);
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }

    // Checkout button
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            if (cart.length > 0) {
                // Calculate totals for alert
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const deliveryCost = 2.50;
                const totalWithDelivery = subtotal + deliveryCost;

                const orderDetails = cart.map(item =>
                    `- ${item.name}: ${item.quantity} × €${item.price.toFixed(2)} = €${(item.price * item.quantity).toFixed(2)}`
                ).join('\n');

                alert(`Bestelling geplaatst!\n\nItems:\n${orderDetails}\n\nSubtotaal: €${subtotal.toFixed(2)}\nBezorgkosten: €${deliveryCost.toFixed(2)}\nTotaal: €${totalWithDelivery.toFixed(2)}`);

                // Clear cart after order
                cart = [];
                updateCartDisplay();
            }
        });
    }

    // Initialize cart display
    updateCartDisplay();
});
</script>
@endsection
