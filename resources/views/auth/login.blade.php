@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>Welkom terug</h1>
            <p>Log in om verder te gaan met bestellen</p>
        </div>

        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="required">Email adres</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
                       autofocus
                       placeholder="voorbeeld@email.nl">
                @error('email')
                    <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="required">Wachtwoord</label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="Je wachtwoord">
                @error('password')
                    <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="remember-me">
                <input type="checkbox"
                       name="remember"
                       id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Onthoud mij</label>
            </div>

            <button type="submit" class="login-btn">
                Inloggen
            </button>

            @if (Route::has('password.request'))
                <div class="login-footer">
                    <a href="{{ route('password.request') }}">Wachtwoord vergeten?</a>
                    <span style="margin: 0 0.5rem">•</span>
                    <a href="{{ route('register') }}">Nog geen account? Registreer hier</a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
