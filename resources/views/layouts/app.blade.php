<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Food App')</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

    {{-- Page content --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- JS --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
