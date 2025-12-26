@extends('layouts.app')

@section('title', 'Mijn Profiel')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <h1>Mijn Profiel</h1>
        <p>Beheer je accountgegevens en voorkeuren</p>
    </div>

    <div class="profile-content">
        <div class="profile-sidebar">
            <div class="user-info">
                <div class="avatar">
                    <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <h3>{{ Auth::user()->name }}</h3>
                <p>{{ Auth::user()->email }}</p>
                @if(Auth::user()->phone)
                    <p>{{ Auth::user()->phone }}</p>
                @endif
            </div>

            <nav class="profile-nav">
                <a href="#" class="active">Profielgegevens</a>
                @if(Auth::user()->role === 'user')
                    <a href="#">Mijn Bestellingen</a>
                    <a href="#">Mijn Adressen</a>
                @elseif(Auth::user()->role === 'restaurant')
                    <a href="#">Restaurant Dashboard</a>
                    <a href="#">Menu Beheren</a>
                    <a href="#">Bestellingen</a>
                @endif
                <a href="#">Instellingen</a>
            </nav>
        </div>

        <div class="profile-main">
            <div class="profile-card">
                <h2>Persoonlijke Gegevens</h2>
                <form method="POST" action="#">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Naam</label>
                            <input type="text" id="name" value="{{ Auth::user()->name }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" value="{{ Auth::user()->email }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefoon</label>
                            <input type="text" id="phone" value="{{ Auth::user()->phone ?? 'Niet ingesteld' }}" disabled>
                        </div>

                        @if(Auth::user()->role === 'restaurant')
                        <div class="form-group">
                            <label for="restaurant_name">Restaurantnaam</label>
                            <input type="text" id="restaurant_name" value="{{ Auth::user()->restaurant_name ?? 'Niet ingesteld' }}" disabled>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            <div class="profile-card">
                <h2>Account Type</h2>
                <div class="account-type">
                    <div class="type-badge {{ Auth::user()->role }}">
                        @if(Auth::user()->role === 'user')
                            👤 Klant Account
                        @elseif(Auth::user()->role === 'restaurant')
                            🏪 Restaurant Account
                        @endif
                    </div>
                    <p class="type-description">
                        @if(Auth::user()->role === 'user')
                            Je kunt eten bestellen van restaurants in jouw omgeving.
                        @elseif(Auth::user()->role === 'restaurant')
                            Je kunt je restaurant en menu beheren en bestellingen ontvangen.
                        @endif
                    </p>
                </div>
            </div>

            <div class="profile-actions">
                <a href="#" class="btn-secondary">Wachtwoord wijzigen</a>
                <a href="#" class="btn-secondary">Account verwijderen</a>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.profile-header {
    text-align: center;
    margin-bottom: 3rem;
}

.profile-header h1 {
    color: #333;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.profile-header p {
    color: #666;
    font-size: 1.1rem;
}

.profile-content {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 2rem;
}

.user-info {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ff4d4d, #ff8a00);
    border-radius: 50%;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    font-weight: bold;
}

.user-info h3 {
    margin: 0.5rem 0;
    color: #333;
}

.user-info p {
    color: #666;
    margin: 0.25rem 0;
    font-size: 0.9rem;
}

.profile-nav {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.profile-nav a {
    display: block;
    padding: 1rem 1.5rem;
    color: #333;
    text-decoration: none;
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.profile-nav a:hover {
    background: #f8f9fa;
    border-left-color: #ff4d4d;
}

.profile-nav a.active {
    background: #fff5f5;
    border-left-color: #ff4d4d;
    color: #ff4d4d;
    font-weight: 500;
}

.profile-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
}

.profile-card h2 {
    color: #333;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f0f0;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #666;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    background: #f8f9fa;
    color: #333;
}

.account-type {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.type-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.type-badge.user {
    background: #e3f2fd;
    color: #1976d2;
}

.type-badge.restaurant {
    background: #fff3e0;
    color: #f57c00;
}

.type-description {
    color: #666;
    margin: 0;
}

.profile-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.btn-secondary {
    padding: 0.75rem 1.5rem;
    background: #f8f9fa;
    color: #666;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}

@media (max-width: 768px) {
    .profile-content {
        grid-template-columns: 1fr;
    }

    .profile-nav {
        display: flex;
        overflow-x: auto;
    }

    .profile-nav a {
        white-space: nowrap;
        border-left: none;
        border-bottom: 3px solid transparent;
    }

    .profile-nav a.active {
        border-left: none;
        border-bottom-color: #ff4d4d;
    }
}
</style>
@endsection
