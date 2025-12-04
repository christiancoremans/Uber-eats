<form action="{{ url('/register') }}" method="POST">
    @csrf

    <div>
        <label for="role">Ik registreer als:</label>
        <select name="role" id="role" required>
            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Klant</option>
            <option value="restaurant" {{ old('role') === 'restaurant' ? 'selected' : '' }}>Restaurant</option>
        </select>
    </div>

    <div>
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
    </div>

    <div id="restaurant-name-field" style="display: none;">
        <label for="restaurant_name">Restaurantnaam:</label>
        <input type="text" id="restaurant_name" name="restaurant_name" value="{{ old('restaurant_name') }}">
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label for="phone">Telefoon (optioneel):</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
    </div>

    <div>
        <label for="address_line1">Adres regel 1:</label>
        <input type="text" id="address_line1" name="address_line1" value="{{ old('address_line1') }}">
    </div>

    <div>
        <label for="address_line2">Adres regel 2 (optioneel):</label>
        <input type="text" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
    </div>

    <div>
        <label for="postcode">Postcode:</label>
        <input type="text" id="postcode" name="postcode" value="{{ old('postcode') }}">
    </div>

    <div>
        <label for="city">Plaats:</label>
        <input type="text" id="city" name="city" value="{{ old('city') }}">
    </div>

    <div>
        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Bevestig wachtwoord:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>

    <div>
        <button type="submit">Register</button>
    </div>
</form>

<script>
    // kleine clientside UX: toon restaurant_name alleen als role=restaurant
    const roleEl = document.getElementById('role');
    const restField = document.getElementById('restaurant-name-field');

    function toggleRestaurantField() {
        if (roleEl.value === 'restaurant') {
            restField.style.display = '';
            document.getElementById('restaurant_name').required = true;
        } else {
            restField.style.display = 'none';
            document.getElementById('restaurant_name').required = false;
        }
    }

    roleEl.addEventListener('change', toggleRestaurantField);
    toggleRestaurantField();
</script>
