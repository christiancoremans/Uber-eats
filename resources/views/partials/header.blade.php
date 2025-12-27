<header class="header">
    <nav class="nav">
        <a href="{{ url('/') }}" class="logo">FoodDelivery</a>

        <ul class="nav-links">
            @auth
                {{-- Add Menu button for restaurants --}}
                @if(Auth::user()->role === 'restaurant')
                    <li>
                        <a href="{{ route('restaurant.menu.dashboard') }}" style="background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px;">
                            🍽️ Menu Beheren
                        </a>
                    </li>
                @endif

                {{-- User is logged in --}}
                <li class="nav-item dropdown" style="position: relative;">
                    <a href="#" class="dropdown-toggle" style="display: flex; align-items: center; gap: 5px;">
                        <span>{{ Auth::user()->name }}</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu" style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; min-width: 150px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: none; z-index: 1000;">
                        <li style="border-bottom: 1px solid #eee;">
                            <a href="{{ route('profile') }}" style="display: block; padding: 10px 15px; color: #333; text-decoration: none;">Profiel</a>
                        </li>
                        @if(Auth::user()->role === 'restaurant')
                            <li style="border-bottom: 1px solid #eee;">
                                <a href="{{ route('restaurant.menu.dashboard') }}" style="display: block; padding: 10px 15px; color: #333; text-decoration: none;">Menu Dashboard</a>
                            </li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 10px 15px; color: #ff4d4d; cursor: pointer; font-size: 1rem;">
                                    Uitloggen
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                {{-- User is not logged in --}}
                <li><a href="{{ route('login') }}">Inloggen</a></li>
                <li><a href="{{ route('register') }}" style="background: white; color: #ff4d4d; padding: 5px 15px; border-radius: 20px;">Registreren</a></li>
            @endauth
        </ul>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const isVisible = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isVisible ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    }
});
</script>
