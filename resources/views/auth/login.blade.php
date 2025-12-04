<form action="{{ url('/login') }}" method="POST">
    @csrf
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label>
            <input type="checkbox" name="remember"> Onthoud mij
        </label>
    </div>
    <button type="submit">Login</button>
</form>
