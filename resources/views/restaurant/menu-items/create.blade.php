@extends('layouts.app')

@section('title', 'Nieuw Menu Item')

@section('content')
<div class="create-menu-item-container">
    <div class="page-header">
        <div>
            <h1>Nieuw Menu Item</h1>
            <p>Voeg een nieuw item toe aan je menu</p>
        </div>
        <a href="{{ route('restaurant.menu-items.index') }}" class="btn-secondary">
            ← Terug
        </a>
    </div>

    <div class="form-container">
        <form action="{{ route('restaurant.menu-items.store') }}" method="POST" class="menu-item-form" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id" class="required">Categorie</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Selecteer een categorie</option>

                        {{-- Default categories --}}
                        @if($defaultCategories->isNotEmpty())
                            <optgroup label="Standaard categorieën">
                                @foreach($defaultCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif

                        {{-- Custom categories --}}
                        @if($customCategories->isNotEmpty())
                            <optgroup label="Eigen categorieën">
                                @foreach($customCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    @error('category_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name" class="required">Naam</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Bijv. Kapsalon Kip">
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">Beschrijving (optioneel)</label>
                <textarea id="description" name="description" rows="3"
                          placeholder="Beschrijving van het gerecht">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="ingredients">Ingrediënten (optioneel)</label>
                <textarea id="ingredients" name="ingredients" rows="3"
                          placeholder="Lijst van ingrediënten, gescheiden door komma's">{{ old('ingredients') }}</textarea>
                @error('ingredients')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price" class="required">Prijs (€)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" required
                           step="0.01" min="0" placeholder="9.99">
                    @error('price')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="size">Grootte (optioneel)</label>
                    <select id="size" name="size">
                        <option value="">Selecteer grootte</option>
                        <option value="small" {{ old('size') == 'small' ? 'selected' : '' }}>Klein</option>
                        <option value="medium" {{ old('size') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="large" {{ old('size') == 'large' ? 'selected' : '' }}>Groot</option>
                    </select>
                    @error('size')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="preparation_time">Bereidingstijd (minuten)</label>
                    <input type="number" id="preparation_time" name="preparation_time"
                           value="{{ old('preparation_time') }}" min="0" placeholder="15">
                    @error('preparation_time')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sort_order">Sorteervolgorde</label>
                    <input type="number" id="sort_order" name="sort_order"
                           value="{{ old('sort_order', 0) }}" placeholder="0">
                    @error('sort_order')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="image">Foto (optioneel)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="hidden" name="in_stock" value="0">
                            <input type="checkbox" name="in_stock" value="1" {{ old('in_stock', 1) ? 'checked' : '' }}>
                            Op voorraad
                        </label>
                        <label class="checkbox-label">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            Uitgelicht
                        </label>
                        <label class="checkbox-label">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            Actief
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    ➕ Menu Item Aanmaken
                </button>
                <a href="{{ route('restaurant.menu-items.index') }}" class="btn-cancel">
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.create-menu-item-container {
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

.menu-item-form {
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

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    color: #666;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
    width: 18px;
    height: 18px;
    accent-color: #ff4d4d;
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

    .create-menu-item-container {
        padding: 1rem;
    }
}
</style>
@endsection
