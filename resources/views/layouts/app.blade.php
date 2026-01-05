<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Food App')</title>

    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="container">
        @yield('content')
    </main>

    @livewireScripts

    <!-- Fixed Livewire URL override script -->
    <script>
        // Method 1: Patch fetch globally
        (function() {
            const originalFetch = window.fetch;

            window.fetch = function(resource, init) {
                // Only modify if resource is a string and contains livewire
                if (typeof resource === 'string' && resource.includes('/livewire/')) {
                    // Check if URL already has /public
                    if (!resource.startsWith('/public/') && !resource.includes('://')) {
                        resource = '/public' + resource;
                    }
                }
                return originalFetch.call(this, resource, init);
            };
        })();

        // Method 2: Patch XMLHttpRequest
        (function() {
            const originalOpen = XMLHttpRequest.prototype.open;

            XMLHttpRequest.prototype.open = function(method, url, async, user, password) {
                // Only modify if URL contains livewire
                if (url && typeof url === 'string' && url.includes('/livewire/')) {
                    // Check if URL already has /public
                    if (!url.startsWith('/public/') && !url.includes('://')) {
                        url = '/public' + url;
                    }
                }
                return originalOpen.call(this, method, url, async, user, password);
            };
        })();

        // Method 3: Livewire specific hook (more reliable)
        document.addEventListener('livewire:init', function() {
            console.log('Livewire initialized, patching URLs...');

            // Store original update method
            const originalUpdateMethod = Livewire.update;

            // Override Livewire.update method
            Livewire.update = function(componentId, updates, callbacks) {
                // This method handles all Livewire updates
                console.log('Livewire.update called for component:', componentId);

                // We can't directly modify the URL here, so we rely on the fetch/XMLHttpRequest patches above
                return originalUpdateMethod.call(this, componentId, updates, callbacks);
            };

            // Hook into request to see what's happening
            Livewire.hook('request', function(data) {
                console.log('Livewire request intercepted:', data);

                // Ensure URL has /public prefix
                if (data.uri && !data.uri.includes('/public/') && !data.uri.includes('://')) {
                    data.uri = '/public' + data.uri;
                    console.log('Fixed URI to:', data.uri);
                }

                return data;
            });

            // Debug hooks
            Livewire.hook('request.error', function(error, status, request) {
                console.error('Livewire request error:', error, status, request);
            });

            Livewire.hook('response', function({ status, response }) {
                console.log('Livewire response:', status, response);
            });
        });

        // Test function
        window.testLivewireEndpoint = function() {
            console.log('Testing Livewire endpoint...');

            fetch('/livewire/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Livewire': 'true'
                },
                body: JSON.stringify({
                    components: [],
                    updates: []
                })
            })
            .then(response => {
                console.log('Test fetch - Status:', response.status);
                console.log('Test fetch - URL:', response.url);
                return response.text();
            })
            .then(text => {
                console.log('Test fetch - Response (first 200 chars):', text.substring(0, 200));
            })
            .catch(error => {
                console.error('Test fetch - Error:', error);
            });
        };

        // Run test after page loads
        setTimeout(window.testLivewireEndpoint, 2000);
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
