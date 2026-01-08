@extends('layouts.app')

@section('title', 'Categorieën')

@section('content')
<div class="categories-container">
    <div class="page-header">
        <div>
            <h1>Categorieën</h1>
            <p>Beheer je menu categorieën</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('restaurant.menu-items.index') }}" class="btn-secondary">
                🍽️ Menu Items
            </a>
            <a href="{{ route('restaurant.categories.create') }}" class="btn-primary">
                ➕ Nieuwe Categorie
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($categories->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📂</div>
            <h3>Nog geen categorieën</h3>
            <p>Voeg categorieën toe om je menu te organiseren</p>
            <a href="{{ route('restaurant.categories.create') }}" class="btn-primary">
                Eerste Categorie Toevoegen
            </a>
        </div>
    @else
        <div class="filters">
            <div class="filter-group">
                <label for="status_filter">Filter op status:</label>
                <select id="status_filter">
                    <option value="">Alle status</option>
                    <option value="active">Alleen actief</option>
                    <option value="inactive">Alleen inactief</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="sort_filter">Sorteer op:</label>
                <select id="sort_filter">
                    <option value="name">Naam A-Z</option>
                    <option value="sort_order">Sorteervolgorde</option>
                    <option value="newest">Nieuwste eerst</option>
                    <option value="oldest">Oudste eerst</option>
                </select>
            </div>
        </div>

        <div class="categories-table">
            <table>
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Omschrijving</th>
                        <th>Sorteervolgorde</th>
                        <th>Menu Items</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr data-status="{{ $category->is_active ? 'active' : 'inactive' }}"
                            data-sort-order="{{ $category->sort_order }}"
                            data-created-at="{{ $category->created_at->timestamp }}">
                            <td>
                                <div class="category-info">
                                    <div class="category-name">{{ $category->name }}</div>
                                    @if($category->is_default)
                                        <div class="category-tag default">Standaard</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($category->description)
                                    <div class="category-description">{{ Str::limit($category->description, 80) }}</div>
                                @else
                                    <span class="text-muted">Geen omschrijving</span>
                                @endif
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td>
                                <span class="items-count">{{ $category->menuItems()->count() }} items</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $category->is_active ? 'active' : 'inactive' }}">
                                    {{ $category->is_active ? 'Actief' : 'Inactief' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('restaurant.categories.edit', $category) }}" class="btn-edit">
                                        ✏️
                                    </a>
                                    <form action="{{ route('restaurant.categories.destroy', $category) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('Weet je zeker dat je deze categorie wilt verwijderen?')">
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

        <div class="stats-summary">
            <div class="stat-card">
                <div class="stat-icon">📂</div>
                <div class="stat-info">
                    <div class="stat-value">{{ $categories->count() }}</div>
                    <div class="stat-label">Totaal categorieën</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <div class="stat-value">{{ $categories->where('is_active', true)->count() }}</div>
                    <div class="stat-label">Actieve categorieën</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🍽️</div>
                <div class="stat-info">
                    @php
                        $totalItems = 0;
                        foreach ($categories as $category) {
                            $totalItems += $category->menuItems()->count();
                        }
                    @endphp
                    <div class="stat-value">{{ $totalItems }}</div>
                    <div class="stat-label">Totaal menu items</div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.categories-container {
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

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    border-left: 4px solid;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left-color: #155724;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-left-color: #721c24;
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
    display: flex;
    gap: 2rem;
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
    flex: 1;
}

.filter-group label {
    font-weight: 500;
    color: #333;
    min-width: 100px;
}

.filter-group select {
    flex: 1;
    padding: 0.5rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: white;
}

.categories-table {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.categories-table table {
    width: 100%;
    border-collapse: collapse;
}

.categories-table thead {
    background: #f8f9fa;
}

.categories-table th {
    padding: 1rem;
    text-align: left;
    color: #333;
    font-weight: 600;
    border-bottom: 2px solid #e0e0e0;
}

.categories-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.categories-table tr:hover {
    background: #f8f9fa;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.category-name {
    font-weight: 500;
    color: #333;
}

.category-tag {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.category-tag.default {
    background: #e3f2fd;
    color: #1976d2;
}

.category-description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
}

.text-muted {
    color: #999;
    font-style: italic;
}

.items-count {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f8f9fa;
    border-radius: 20px;
    font-size: 0.875rem;
    color: #666;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
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

.stats-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
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
    font-size: 2rem;
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    line-height: 1;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .filters {
        flex-direction: column;
        gap: 1rem;
    }

    .filter-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .filter-group label {
        min-width: auto;
    }

    .categories-table {
        overflow-x: auto;
    }

    .categories-table table {
        min-width: 800px;
    }

    .stats-summary {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status_filter');
    const sortFilter = document.getElementById('sort_filter');
    const tableRows = document.querySelectorAll('.categories-table tbody tr');

    // Status filter
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;

            tableRows.forEach(row => {
                if (selectedStatus === '' || row.dataset.status === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Sort filter
    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            const selectedSort = this.value;
            const tbody = document.querySelector('.categories-table tbody');
            const rowsArray = Array.from(tableRows);

            rowsArray.sort((a, b) => {
                switch(selectedSort) {
                    case 'name':
                        return a.querySelector('.category-name').textContent
                            .localeCompare(b.querySelector('.category-name').textContent);

                    case 'sort_order':
                        return parseInt(a.dataset.sortOrder) - parseInt(b.dataset.sortOrder);

                    case 'newest':
                        return parseInt(b.dataset.createdAt) - parseInt(a.dataset.createdAt);

                    case 'oldest':
                        return parseInt(a.dataset.createdAt) - parseInt(b.dataset.createdAt);

                    default:
                        return 0;
                }
            });

            // Verwijder alle rijen en voeg ze gesorteerd toe
            rowsArray.forEach(row => tbody.appendChild(row));
        });
    }
});
</script>
@endsection
