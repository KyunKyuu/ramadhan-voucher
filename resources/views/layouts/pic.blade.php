<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PIC Panel') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Mobile: sidebar hidden by default */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }
            
            #sidebar.sidebar-open {
                transform: translateX(0);
            }
        }
        
        /* Desktop: sidebar always visible */
        @media (min-width: 769px) {
            #sidebar {
                transform: translateX(0) !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-transition fixed md:static inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-teal-700 to-teal-900 text-white flex flex-col">
            <!-- Logo/Brand -->
            <div class="flex items-center justify-between h-14 px-4 bg-teal-800 border-b border-teal-600 flex-shrink-0">
                <div class="flex items-center space-x-2">
                    @if(config('app.logo') && file_exists(public_path(config('app.logo'))))
                        <img src="{{ asset(config('app.logo')) }}" alt="Logo" class="h-8 w-auto">
                    @else
                        <span class="text-xl">☪</span>
                    @endif
                    <h1 class="text-base font-bold">{{ config('app.name') }}</h1>
                </div>
                <button id="closeSidebar" class="md:hidden text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- User Info -->
            <div class="px-4 py-3 border-b border-teal-600 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-xs">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-teal-200">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
                <a href="{{ route('pic.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('pic.dashboard') ? 'bg-teal-600' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="px-4 py-4 border-t border-teal-600 flex-shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-red-600 transition w-full text-left">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-4 md:px-6">
                    <div class="flex items-center space-x-4">
                        <!-- Hamburger Menu Button -->
                        <button id="menuButton" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="hidden md:inline text-sm text-gray-600">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-4 md:mx-6 mt-4">
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 md:mx-6 mt-4">
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
                
                <!-- Footer -->
                <footer class="mt-auto pt-6 pb-2">
                    <p class="text-center text-xs text-gray-500">
                        Dibuat oleh <span class="font-semibold text-gray-700">Teguh Iqbal</span> © {{ date('Y') }}
                    </p>
                </footer>
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const menuButton = document.getElementById('menuButton');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('sidebar-open');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebarFunc() {
            sidebar.classList.remove('sidebar-open');
            sidebarOverlay.classList.add('hidden');
        }

        // Event listeners
        menuButton.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', closeSidebarFunc);
        sidebarOverlay.addEventListener('click', closeSidebarFunc);

        // Close sidebar when clicking a link on mobile
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    closeSidebarFunc();
                }
            });
        });

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                closeSidebarFunc();
            }
        });
    </script>
</body>
</html>
