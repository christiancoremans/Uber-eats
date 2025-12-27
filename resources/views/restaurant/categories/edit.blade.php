@extends('layouts.app')

@section('title', 'Categorie Bewerken')

@section('content')
<div class="edit-category-container">
    <div class="page-header">
        <div>
            <h1>Categorie Bewerken</h1>
            <p>Bewerk de categorie: {{ $category->name }}</p>
        </div>
        <a href="{{ route('restaurant.categories.index') }}" class="btn-secondary">
            ← Terug
        </a>
    </div>

    <div class="form-container">
        <form action="{{ route('restaurant.categories.update', $category) }}" method="POST" class="category-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="required">Naam</label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Beschrijving (optioneel)</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sort_order">Sorteervolgorde</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}">
                    @error('sort_order')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_active">Status</label>
                    <select id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>Actief</option>
                        <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>Inactief</option>
                    </select>
                    @error('is_active')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    💾 Opslaan
                </button>
                <a href="{{ route('restaurant.categories.index') }}" class="btn-cancel">
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</div>

<style>
/* Use same styles as create.blade.php */
.edit-category-container {
    max-width: 800px;
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

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
}

.form-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
}

.category-form {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
    font-weight: 500;
}

.form-group label.required::after {
    content: ' *';
    color: #ff4d4d;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #ff4d4d;
    box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.error-message {
    display: block;
    color: #ff4d4d;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.btn-submit {
    background: linear-gradient(90deg, #ff4d4d, #ff8a00);
    color: white;
    padding: 0.875rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
}

.btn-cancel {
    background: #f8f9fa;
    color: #666;
    padding: 0.875rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #e9ecef;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
