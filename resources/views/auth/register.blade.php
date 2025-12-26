@extends('layouts.app')

@section('content')
<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h1>Word lid van FoodDelivery</h1>
            <p>Registreer je account en begin met bestellen of je restaurant aanbieden</p>
        </div>

        <div class="register-body">
            <form class="register-form" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group full-width">
                    <div class="role-selection">
                        <label class="required">Ik registreer als:</label>
                        <div class="role-options">
                            <div class="role-option">
                                <input type="radio"
                                       id="role-user"
                                       name="role"
                                       value="user"
                                       {{ old('role') === 'user' ? 'checked' : 'checked' }}
                                       required>
                                <label for="role-user">
                                    <div class="role-icon">🍽️</div>
                                    <div class="role-title">Klant</div>
                                    <div class="role-description">Ik wil eten bestellen</div>
                                </label>
                            </div>

                            <div class="role-option">
                                <input type="radio"
                                       id="role-restaurant"
                                       name="role"
                                       value="restaurant"
                                       {{ old('role') === 'restaurant' ? 'checked' : '' }}
                                       required>
                                <label for="role-restaurant">
                                    <div class="role-icon">🏪</div>
                                    <div class="role-title">Restaurant</div>
                                    <div class="role-description">Ik wil mijn restaurant aanbieden</div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="required">Volledige naam</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autocomplete="name"
                           autofocus
                           placeholder="Jouw volledige naam">
                    @error('name')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="restaurant-name-field" style="display: none;">
                    <label for="restaurant_name" class="required">Restaurantnaam</label>
                    <input type="text"
                           id="restaurant_name"
                           name="restaurant_name"
                           value="{{ old('restaurant_name') }}"
                           placeholder="Naam van je restaurant">
                    @error('restaurant_name')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="required">Email adres</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="voorbeeld@email.nl">
                    @error('email')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Telefoonnummer (optioneel)</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="0612345678">
                    @error('phone')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <h3 style="color: #333; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #f0f0f0;">Adresgegevens</h3>
                </div>

                <div class="form-group">
                    <label for="address_line1">Straat en huisnummer</label>
                    <input type="text"
                           id="address_line1"
                           name="address_line1"
                           value="{{ old('address_line1') }}"
                           placeholder="Voorbeeldstraat 123">
                    @error('address_line1')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address_line2">Toevoeging (optioneel)</label>
                    <input type="text"
                           id="address_line2"
                           name="address_line2"
                           value="{{ old('address_line2') }}"
                           placeholder="Achter de deur">
                </div>

                <div class="form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text"
                           id="postcode"
                           name="postcode"
                           value="{{ old('postcode') }}"
                           placeholder="1234 AB">
                    @error('postcode')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">Plaats</label>
                    <input type="text"
                           id="city"
                           name="city"
                           value="{{ old('city') }}"
                           placeholder="Amsterdam">
                    @error('city')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="required">Wachtwoord</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="Minimaal 8 karakters">
                    @error('password')
                        <span style="color: #ff4d4d; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="required">Bevestig wachtwoord</label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="Herhaal je wachtwoord">
                </div>

                <button type="submit" class="register-btn">
                    Account Aanmaken
                </button>

                <div class="form-note">
                    Al een account? <a href="{{ route('login') }}">Log hier in</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleUser = document.getElementById('role-user');
    const roleRestaurant = document.getElementById('role-restaurant');
    const restaurantField = document.getElementById('restaurant-name-field');
    const restaurantInput = document.getElementById('restaurant_name');

    function toggleRestaurantField() {
        if (roleRestaurant.checked) {
            restaurantField.style.display = 'block';
            restaurantInput.required = true;
        } else {
            restaurantField.style.display = 'none';
            restaurantInput.required = false;
        }
    }

    roleUser.addEventListener('change', toggleRestaurantField);
    roleRestaurant.addEventListener('change', toggleRestaurantField);

    // Initial check
    toggleRestaurantField();
});
</script>
@endsection
