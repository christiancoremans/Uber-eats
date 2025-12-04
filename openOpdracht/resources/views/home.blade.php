
<div class="container mt-5">
    <h1>Welcome to OpenOpdracht</h1>
    <p class="lead">Your platform for open assignments and collaboration.</p>

    <div class="mt-3">
        @guest
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        @else
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>

            <a href="{{ route('register') }}" class="btn btn-secondary ms-2">Register</a>
        @endguest
    </div>
</div>

