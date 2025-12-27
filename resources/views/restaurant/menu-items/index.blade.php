@extends('layouts.app')

@section('title', 'Menu Items')

@section('content')
<div class="menu-items-container">
    <div class="page-header">
        <div>
            <h1>Menu Items</h1>
            <p>Beheer je menu items</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('restaurant.categories.index') }}" class="btn-secondary">
                📂 Categorieën
            </a>
            <a href="{{ route('restaurant.menu-items.create') }}" class="btn-primary">
                ➕ Nieuw Item
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($menuItems->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🍽️</div>
            <h3>Nog geen menu items</h3>
            <p>Begin met het toevoegen van items aan je menu</p>
            <a href="{{ route('restaurant.menu-items.create') }}" class="btn-primary">
                Eerste Item Toevoegen
            </a>
        </div>
    @else
        <div class="filters">
            <div class="filter-group">
                <label for="category_filter">Filter op categorie:</label>
                <select id="category_filter">
                    <option value="">Alle categorieën</option>
                    @foreach($menuItems->unique('category_id')->pluck('category') as $category)
                        @if($category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="menu-items-table">
            <table>
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Categorie</th>
                        <th>Prijs</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menuItems as $item)
                        <tr data-category="{{ $item->category_id }}">
                            <td>
                                <div class="item-info">
                                    <div class="item-name">{{ $item->name }}</div>
                                    @if($item->description)
                                        <div class="item-description">{{ Str::limit($item->description, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $item->category->name }}</td>
                            <td>€{{ number_format($item->price, 2, ',', '.') }}</td>
                            <td>
                                <span class="status-badge {{ $item->is_active ? 'active' : 'inactive' }}">
                                    {{ $item->is_active ? 'Actief' : 'Inactief' }}
                                </span>
                                @if(!$item->in_stock)
                                    <span class="stock-badge">Uitverkocht</span>
                                @endif
                                @if($item->is_featured)
                                    <span class="featured-badge">⭐ Uitgelicht</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('restaurant.menu-items.edit', $item) }}" class="btn-edit">
                                        ✏️
                                    </a>
                                    <form action="{{ route('restaurant.menu-items.destroy', $item) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('Weet je zeker dat je dit menu item wilt verwijderen?')">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<style>
.menu-items-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    color: #333;
    font-size: 2rem;
    margin: 0 0 0.5rem 0;
}

.page-header p {
    color: #666;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #666;
    margin-bottom: 2rem;
}

.filters {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.filter-group label {
    font-weight: 500;
    color: #333;
}

.filter-group select {
    padding: 0.5rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: white;
}

.menu-items-table {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.menu-items-table table {
    width: 100%;
    border-collapse: collapse;
}

.menu-items-table thead {
    background: #f8f9fa;
}

.menu-items-table th {
    padding: 1rem;
    text-align: left;
    color: #333;
    font-weight: 600;
    border-bottom: 2px solid #e0e0e0;
}

.menu-items-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.menu-items-table tr:hover {
    background: #f8f9fa;
}

.item-info {
    display: flex;
    flex-direction: column;
}

.item-name {
    font-weight: 500;
    color: #333;
}

.item-description {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.25rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.5rem;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

.stock-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    background: #fff3cd;
    color: #856404;
    margin-right: 0.5rem;
}

.featured-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    background: #d1ecf1;
    color: #0c5460;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-edit {
    background: #e3f2fd;
    color: #1976d2;
    padding: 0.5rem;
    border-radius: 6px;
    text-decoration: none;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-edit:hover {
    background: #bbdefb;
}

.btn-delete {
    background: #f8d7da;
    color: #721c24;
    padding: 0.5rem;
    border-radius: 6px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-delete:hover {
    background: #f5c6cb;
}

.inline-form {
    margin: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category_filter');
    const tableRows = document.querySelectorAll('.menu-items-table tbody tr');

    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            const selectedCategory = this.value;

            tableRows.forEach(row => {
                if (selectedCategory === '' || row.dataset.category === selectedCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
