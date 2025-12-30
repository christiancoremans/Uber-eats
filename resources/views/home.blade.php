@extends('layouts.app')

@section('title', 'Restaurants')

@section('content')
<div class="home-container">
    <div class="home-header">
        <h1>Ontdek Restaurants bij Jou in de Buurt</h1>
    </div>

    <div class="home-content">
        <!-- Left Sidebar - Filters -->
        <aside class="filters-sidebar">
            <div class="filters-card">
                <h3>Filters</h3>

                <!-- Categories Filter -->
                <div class="filter-section">
                    <h4>Categorieën</h4>
                    <div class="categories-list">
                        @foreach($categories as $category)
                            <label class="category-checkbox">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="filter-checkbox" data-filter="category">
                                <span class="checkmark"></span>
                                {{ $category->name }}
                                <span class="category-count">({{ $category->menu_items_count ?? 0 }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="filter-section">
                    <h4>Prijsniveau</h4>
                    <div class="price-slider-container">
                        <div class="price-range">
                            <span id="min-price">€0</span>
                            <span id="max-price">€50+</span>
                        </div>
                        <input type="range" id="price-slider" min="0" max="50" value="25" class="filter-slider" data-filter="price">
                        <div class="price-options">
                            <button type="button" class="price-option" data-price="10">€</button>
                            <button type="button" class="price-option" data-price="20">€€</button>
                            <button type="button" class="price-option active" data-price="30">€€€</button>
                            <button type="button" class="price-option" data-price="40">€€€€</button>
                        </div>
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="filter-section">
                    <h4>Minimum Beoordeling</h4>
                    <div class="rating-filter">
                        <div class="stars-selector">
                            @for($i = 5; $i >= 1; $i--)
                                <label class="star-option">
                                    <input type="radio" name="rating" value="{{ $i }}" class="filter-radio" data-filter="rating">
                                    <div class="stars">
                                        @for($j = 1; $j <= 5; $j++)
                                            <span class="star {{ $j <= $i ? 'filled' : '' }}">★</span>
                                        @endfor
                                        <span class="rating-text">& hoger</span>
                                    </div>
                                </label>
                            @endfor
                            <label class="star-option">
                                <input type="radio" name="rating" value="0" class="filter-radio" data-filter="rating" checked>
                                <div class="stars">
                                    <span class="rating-text">Alle beoordelingen</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Special Filter -->
                <div class="filter-section">
                    <h4>Speciale Kenmerken</h4>
                    <div class="special-filters">
                        <div class="featured-filter">
                            <label class="featured-checkbox">
                                <input type="checkbox" class="filter-checkbox" data-filter="featured">
                                <span class="featured-label">
                                    <span class="featured-icon">⭐</span>
                                    Uitgelicht
                                </span>
                            </label>
                            <p class="featured-help">Toon alleen restaurants met uitgelichte gerechten</p>
                        </div>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="button" id="apply-filters" class="btn-apply">
                        Filters Toepassen
                    </button>
                    <button type="button" id="reset-filters" class="btn-reset">
                        Reset Filters
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content - Restaurants -->
        <main class="restaurants-main">
            <!-- Search Bar -->
            <div class="main-header">
                <div class="search-container">
                    <form action="{{ route('home.search') }}" method="GET" class="search-bar-form">
                        <div class="search-bar">
                            <input type="text" name="q" id="main-search" placeholder="Zoek op restaurantnaam, categorie, gerecht..." value="{{ request('q') }}">
                            <button type="submit" id="main-search-btn">Zoeken</button>
                        </div>
                    </form>
                    <div class="search-stats">
                        <span id="restaurant-count">{{ $restaurants->total() }}</span> restaurants gevonden
                    </div>
                </div>
            </div>

            <!-- Restaurants List -->
            <div class="restaurants-list" id="restaurants-container">
                @if($restaurants->isEmpty())
                    <div class="no-results">
                        <div class="no-results-icon">🍽️</div>
                        <h3>Geen restaurants gevonden</h3>
                        <p>Probeer andere filters of zoektermen</p>
                    </div>
                @else
                    @foreach($restaurants as $restaurant)
                        <a href="{{ route('restaurant.show', $restaurant) }}" class="restaurant-card-link">
                            <div class="restaurant-card"
                                 data-categories="{{ $restaurant->categories->pluck('id')->implode(',') }}"
                                 data-price="{{ $restaurant->average_price }}"
                                 data-rating="{{ $restaurant->average_rating }}"
                                 data-featured="{{ $restaurant->has_featured_items ? '1' : '0' }}">
                                <!-- Restaurant Image -->
                                <div class="restaurant-image">
                                    @if($restaurant->getFirstMediaUrl('restaurant_logo'))
                                        <img src="{{ $restaurant->getFirstMediaUrl('restaurant_logo') }}" alt="{{ $restaurant->restaurant_name }}">
                                    @else
                                        <div class="image-placeholder">
                                            <span>🍴</span>
                                        </div>
                                    @endif
                                    @if($restaurant->has_featured_items)
                                        <div class="featured-badge">
                                            ⭐ Uitgelicht
                                        </div>
                                    @endif
                                </div>

                                <!-- Restaurant Info -->
                                <div class="restaurant-info">
                                    <!-- Line 1: Name and City -->
                                    <div class="restaurant-header">
                                        <h3>{{ $restaurant->restaurant_name ?? $restaurant->name }}</h3>
                                        <span class="separator">|</span>
                                        <span class="restaurant-city">{{ $restaurant->city }}</span>
                                    </div>

                                    <!-- Line 2: Rating and Categories -->
                                    <div class="restaurant-rating-categories">
                                        <div class="restaurant-rating">
                                            @if($restaurant->average_rating > 0)
                                                <span class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <span class="star {{ $i <= round($restaurant->average_rating) ? 'filled' : '' }}">★</span>
                                                    @endfor
                                                </span>
                                                <span class="rating-number">{{ number_format($restaurant->average_rating, 1) }}</span>
                                            @else
                                                <span class="no-reviews">Nog geen beoordelingen</span>
                                            @endif
                                        </div>

                                        <div class="restaurant-categories">
                                            @if($restaurant->categories->isNotEmpty())
                                                @foreach($restaurant->categories->take(5) as $category)
                                                    <span class="category-tag">{{ $category->name }}</span>
                                                @endforeach
                                                @if($restaurant->categories->count() > 5)
                                                    <span class="category-more">+{{ $restaurant->categories->count() - 5 }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Line 3: Prep Time and Price -->
                                    <div class="restaurant-details">
                                        <div class="detail-item">
                                            <span class="detail-icon">⏱️</span>
                                            <span class="detail-text">Gem. bereiding: {{ $restaurant->average_preparation_time }} min</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-icon">💰</span>
                                            <span class="detail-text">Prijsniveau:
                                                @if($restaurant->average_price < 10)
                                                    €
                                                @elseif($restaurant->average_price < 20)
                                                    €€
                                                @elseif($restaurant->average_price < 30)
                                                    €€€
                                                @else
                                                    €€€€
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            @if($restaurants->hasPages())
                <div class="pagination">
                    {{ $restaurants->links() }}
                </div>
            @endif
        </main>
    </div>
</div>

<style>
/* Container */
.home-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.home-header {
    text-align: center;
    margin-bottom: 2rem;
}

.home-header h1 {
    color: #333;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.home-header p {
    color: #666;
    font-size: 1.1rem;
}

/* Layout */
.home-content {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
}

/* Sidebar Filters */
.filters-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.filters-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 1.5rem;
}

.filters-card h3 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f0f0;
}

.filter-section {
    margin-bottom: 2rem;
}

.filter-section h4 {
    color: #333;
    font-size: 1rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

/* Categories List */
.categories-list {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.category-checkbox {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    cursor: pointer;
    color: #333;
    position: relative;
}

.category-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    position: relative;
    height: 18px;
    width: 18px;
    background-color: white;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    margin-right: 0.75rem;
}

.category-checkbox input:checked ~ .checkmark {
    background-color: #ff4d4d;
    border-color: #ff4d4d;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.category-checkbox input:checked ~ .checkmark:after {
    display: block;
    left: 5px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.category-count {
    margin-left: auto;
    color: #666;
    font-size: 0.85rem;
}

/* Price Slider */
.price-slider-container {
    padding: 0.5rem 0;
}

.price-range {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    color: #666;
    font-size: 0.9rem;
}

.filter-slider {
    width: 100%;
    height: 6px;
    background: #e0e0e0;
    border-radius: 3px;
    outline: none;
    -webkit-appearance: none;
}

.filter-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    background: #ff4d4d;
    border-radius: 50%;
    cursor: pointer;
}

.price-options {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.price-option {
    flex: 1;
    padding: 0.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    font-weight: 500;
    color: #666;
    transition: all 0.3s ease;
}

.price-option.active,
.price-option:hover {
    background: #ff4d4d;
    color: white;
    border-color: #ff4d4d;
}

/* Rating Filter */
.rating-filter {
    padding: 0.5rem 0;
}

.star-option {
    display: block;
    padding: 0.5rem 0;
    cursor: pointer;
}

.star-option input {
    display: none;
}

.stars {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.star {
    color: #e0e0e0;
    font-size: 1.1rem;
}

.star.filled {
    color: #ffd700;
}

.rating-text {
    margin-left: 0.5rem;
    color: #666;
    font-size: 0.9rem;
}

/* Special Filters */
.special-filters {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.featured-filter {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.featured-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.featured-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #333;
    font-weight: 600;
    font-size: 1rem;
}

.featured-icon {
    font-size: 1.3rem;
    color: #ffd700;
}

.featured-help {
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #666;
    margin-left: 2rem;
}

/* Filter Actions */
.filter-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.btn-apply {
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
    padding: 0.875rem;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-apply:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
}

.btn-reset {
    background: #f8f9fa;
    color: #666;
    padding: 0.875rem;
    border-radius: 8px;
    font-weight: 500;
    border: 2px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-reset:hover {
    background: #e9ecef;
}

/* Main Content */
.main-header {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.search-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.search-bar-form {
    flex: 1;
}

.search-bar {
    display: flex;
    gap: 0.5rem;
}

.search-bar input {
    flex: 1;
    padding: 0.875rem 1.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.search-bar input:focus {
    outline: none;
    border-color: #ff4d4d;
}

.search-bar button {
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
    padding: 0.875rem 2rem;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-bar button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
}

.search-stats {
    color: #666;
    font-size: 0.95rem;
    white-space: nowrap;
}

.search-stats span {
    font-weight: 600;
    color: #ff4d4d;
}

/* Restaurants List */
.restaurants-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.restaurant-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.restaurant-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.5rem;
    min-height: 200px;
}

.restaurant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.restaurant-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.restaurant-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ff4d4d, #ff8a00);
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-placeholder span {
    font-size: 4rem;
    color: white;
}

.featured-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.restaurant-info {
    padding: 1.5rem 1.5rem 1.5rem 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Line 1: Name and City */
.restaurant-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.restaurant-header h3 {
    color: #333;
    font-size: 1.8rem;
    margin: 0;
    font-weight: 700;
}

.separator {
    color: #ccc;
    font-weight: 300;
}

.restaurant-city {
    color: #666;
    font-size: 1.1rem;
    font-weight: 500;
}

/* Line 2: Rating and Categories */
.restaurant-rating-categories {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.restaurant-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.restaurant-rating .stars {
    display: flex;
    gap: 0.1rem;
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
    font-size: 1.1rem;
}

.no-reviews {
    color: #999;
    font-style: italic;
    font-size: 0.95rem;
}

.restaurant-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.category-tag {
    background: #f0f0f0;
    color: #666;
    padding: 0.35rem 0.85rem;
    border-radius: 18px;
    font-size: 0.9rem;
    font-weight: 500;
}

.category-more {
    color: #ff4d4d;
    font-size: 0.9rem;
    font-weight: 500;
    align-self: center;
}

/* Line 3: Prep Time and Price */
.restaurant-details {
    display: flex;
    gap: 2rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-icon {
    font-size: 1.2rem;
}

.detail-text {
    color: #333;
    font-weight: 500;
    font-size: 1rem;
}

/* No Results */
.no-results {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.no-results-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-results h3 {
    color: #333;
    margin-bottom: 0.5rem;
}

.no-results p {
    color: #666;
}

/* Pagination */
.pagination {
    margin-top: 2rem;
    text-align: center;
}

.pagination nav {
    display: inline-block;
}

.pagination .pagination {
    list-style: none;
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    padding: 0;
}

.pagination .page-item .page-link {
    display: block;
    padding: 0.5rem 1rem;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination .page-item.active .page-link {
    background: #ff4d4d;
    color: white;
    border-color: #ff4d4d;
}

.pagination .page-item:not(.disabled) .page-link:hover {
    background: #f8f9fa;
}

.pagination .page-item.disabled .page-link {
    color: #999;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 1024px) {
    .home-content {
        grid-template-columns: 1fr;
    }

    .filters-sidebar {
        position: static;
        margin-bottom: 2rem;
    }

    .restaurant-card {
        grid-template-columns: 250px 1fr;
    }
}

@media (max-width: 768px) {
    .restaurant-card {
        grid-template-columns: 1fr;
        grid-template-rows: auto 1fr;
    }

    .restaurant-image {
        height: 180px;
    }

    .restaurant-info {
        padding: 1.5rem;
    }

    .restaurant-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .separator {
        display: none;
    }

    .restaurant-rating-categories {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .restaurant-details {
        flex-direction: column;
        gap: 0.75rem;
    }

    .search-container {
        flex-direction: column;
    }

    .search-bar {
        width: 100%;
    }

    .search-bar-form {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .restaurant-header h3 {
        font-size: 1.5rem;
    }

    .restaurant-rating-categories {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .restaurant-categories {
        flex-wrap: wrap;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const restaurantsContainer = document.getElementById('restaurants-container');
    const restaurantCards = document.querySelectorAll('.restaurant-card');
    const restaurantCount = document.getElementById('restaurant-count');

    // Filter elements
    const categoryCheckboxes = document.querySelectorAll('.filter-checkbox[data-filter="category"]');
    const priceSlider = document.getElementById('price-slider');
    const priceOptions = document.querySelectorAll('.price-option');
    const ratingRadios = document.querySelectorAll('.filter-radio[data-filter="rating"]');
    const featuredCheckbox = document.querySelector('.filter-checkbox[data-filter="featured"]');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const resetFiltersBtn = document.getElementById('reset-filters');

    // Current filter state
    let filters = {
        categories: [],
        maxPrice: 50, // Changed from 50 to match the default
        minRating: 0,
        featured: false
    };

    // Price slider update
    priceSlider.addEventListener('input', function() {
        const minPrice = document.getElementById('min-price');
        const maxPrice = document.getElementById('max-price');
        const value = this.value;

        minPrice.textContent = '€0';
        maxPrice.textContent = value === '50' ? '€50+' : `€${value}`;
        filters.maxPrice = parseInt(value);

        // Update price options
        priceOptions.forEach(option => {
            const optionPrice = parseInt(option.dataset.price);
            option.classList.toggle('active', optionPrice <= value);
        });
    });

    // Price option click
    priceOptions.forEach(option => {
        option.addEventListener('click', function() {
            const price = parseInt(this.dataset.price);
            priceSlider.value = price;
            filters.maxPrice = price;

            // Update slider display
            priceSlider.dispatchEvent(new Event('input'));

            // Update active state
            priceOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Category checkboxes
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                filters.categories.push(this.value);
            } else {
                filters.categories = filters.categories.filter(id => id !== this.value);
            }
        });
    });

    // Rating radios
    ratingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                filters.minRating = parseFloat(this.value);
            }
        });
    });

    // Featured checkbox
    if (featuredCheckbox) {
        featuredCheckbox.addEventListener('change', function() {
            filters.featured = this.checked;
        });
    }

    // Apply filters
    applyFiltersBtn.addEventListener('click', applyFilters);

    // Reset filters
    resetFiltersBtn.addEventListener('click', function() {
        // Reset all checkboxes
        document.querySelectorAll('.filter-checkbox').forEach(cb => {
            cb.checked = false;
        });

        // Reset rating radios
        document.querySelectorAll('.filter-radio').forEach(radio => {
            radio.checked = radio.value === '0';
        });

        // Reset price slider
        priceSlider.value = 25;
        priceSlider.dispatchEvent(new Event('input'));

        // Reset price options
        priceOptions.forEach(option => {
            option.classList.remove('active');
            if (option.dataset.price === '30') {
                option.classList.add('active');
            }
        });

        // Reset filter state
        filters = {
            categories: [],
            maxPrice: 25,
            minRating: 0,
            featured: false
        };

        // Apply reset filters
        applyFilters();
    });

    // Filter function
    function applyFilters() {
        let visibleCount = 0;

        // Check if ALL filters are empty/cleared
        const allFiltersEmpty = filters.categories.length === 0 &&
                            filters.maxPrice === 50 && // Default max price
                            filters.minRating === 0 &&
                            filters.featured === false;

        restaurantCards.forEach(card => {
            let show = true;

            // If all filters are empty, show all cards
            if (allFiltersEmpty) {
                show = true;
            } else {
                // Check categories
                if (filters.categories.length > 0) {
                    const cardCategories = card.dataset.categories.split(',').filter(Boolean);
                    const hasMatchingCategory = filters.categories.some(catId =>
                        cardCategories.includes(catId)
                    );
                    show = show && hasMatchingCategory;
                }

                // Check price
                const cardPrice = parseFloat(card.dataset.price || 0);
                show = show && cardPrice <= filters.maxPrice;

                // Check rating
                const cardRating = parseFloat(card.dataset.rating || 0);
                show = show && cardRating >= filters.minRating;

                // Check featured
                if (filters.featured) {
                    show = show && card.dataset.featured === '1';
                }
            }

            // Show/hide card
            if (show) {
                card.parentElement.style.display = 'block';
                visibleCount++;
            } else {
                card.parentElement.style.display = 'none';
            }
        });

        // Update count
        restaurantCount.textContent = visibleCount;

        // Show no results message
        const noResults = document.querySelector('.no-results');
        if (visibleCount === 0) {
            if (!noResults) {
                const noResultsHtml = `
                    <div class="no-results">
                        <div class="no-results-icon">🍽️</div>
                        <h3>Geen restaurants gevonden</h3>
                        <p>Probeer andere filters of zoektermen</p>
                    </div>
                `;
                restaurantsContainer.innerHTML = noResultsHtml;
            }
        } else if (noResults) {
            noResults.remove();
        }
    }

    // Initialize price slider
    priceSlider.dispatchEvent(new Event('input'));
});
</script>
@endsection
