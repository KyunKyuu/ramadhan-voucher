<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white/10 backdrop-blur-md text-white py-4">
            <div class="container mx-auto px-4">
                <div class="flex justify-center items-center gap-3">
                    @if(config('app.logo') && file_exists(public_path(config('app.logo'))))
                        <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                    @endif
                    <h1 class="text-2xl font-bold text-center">{{ config('app.name') }}</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto px-4 py-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white/10 backdrop-blur-md text-white py-4 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semoga berkah.</p>
            <p class="mt-1 text-xs">Dibuat oleh <span class="font-semibold">Teguh Iqbal</span></p>
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
