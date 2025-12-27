@extends('layouts.app')

@section('title', 'Menu Dashboard')

@section('content')
<div class="menu-dashboard">
    <div class="dashboard-header">
        <h1>Menu Beheren</h1>
        <p>Beheer je menu categorieën en items</p>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon">📁</div>
            <div class="stat-info">
                <h3>{{ $categoriesCount }}</h3>
                <p>Categorieën</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🍽️</div>
            <div class="stat-info">
                <h3>{{ $menuItemsCount }}</h3>
                <p>Menu Items</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3>{{ $activeMenuItemsCount }}</h3>
                <p>Actieve Items</p>
            </div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="{{ route('restaurant.categories.index') }}" class="action-card">
            <div class="action-icon">📂</div>
            <h3>Categorieën Beheren</h3>
            <p>Maak en beheer menu categorieën</p>
        </a>

        <a href="{{ route('restaurant.menu-items.index') }}" class="action-card">
            <div class="action-icon">🍕</div>
            <h3>Menu Items Beheren</h3>
            <p>Voeg en beheer menu items toe</p>
        </a>

        <a href="{{ route('restaurant.categories.create') }}" class="action-card">
            <div class="action-icon">➕</div>
            <h3>Nieuwe Categorie</h3>
            <p>Voeg een nieuwe categorie toe</p>
        </a>

        <a href="{{ route('restaurant.menu-items.create') }}" class="action-card">
            <div class="action-icon">🥗</div>
            <h3>Nieuw Menu Item</h3>
            <p>Voeg een nieuw menu item toe</p>
        </a>
    </div>
</div>

<style>
.menu-dashboard {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.dashboard-header {
    text-align: center;
    margin-bottom: 3rem;
}

.dashboard-header h1 {
    color: #333;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.dashboard-header p {
    color: #666;
    font-size: 1.1rem;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2.5rem;
}

.stat-info h3 {
    font-size: 2rem;
    color: #ff4d4d;
    margin: 0;
}

.stat-info p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

.dashboard-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.action-card:hover {
    border-color: #ff4d4d;
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(255, 77, 77, 0.2);
}

.action-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.action-card h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
}

.action-card p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .menu-dashboard {
        padding: 1rem;
    }

    .dashboard-stats,
    .dashboard-actions {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
