<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Ramadhan Berkah</title>
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
                    <span class="text-xl">☪</span>
                    <h1 class="text-base font-bold">Ramadhan Berkah</h1>
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('admin.analytics') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.analytics') ? 'bg-teal-600' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-medium">Analytics</span>
                </a>

                <!-- Data Section -->
                <div class="pt-4">
                    <p class="px-4 text-xs font-semibold text-teal-300 uppercase tracking-wider mb-2">Data</p>
                    
                    <a href="{{ route('admin.claims.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.claims.*') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Claims</span>
                    </a>

                    <a href="{{ route('admin.redeems.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.redeems.*') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Redemptions</span>
                    </a>
                </div>

                <!-- Management Section -->
                <div class="pt-4">
                    <p class="px-4 text-xs font-semibold text-teal-300 uppercase tracking-wider mb-2">Management</p>
                    
                    <a href="{{ route('admin.pics.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.pics.*') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="font-medium">PICs</span>
                    </a>

                    <a href="{{ route('admin.merchants.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.merchants.*') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-medium">Merchants</span>
                    </a>

                    <a href="{{ route('admin.offers.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.offers.*') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="font-medium">Offers</span>
                    </a>
                </div>

                <!-- Vouchers Section -->
                <div class="pt-4">
                    <p class="px-4 text-xs font-semibold text-teal-300 uppercase tracking-wider mb-2">Vouchers</p>
                    
                    <a href="{{ route('admin.vouchers.generate') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.vouchers.generate') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="font-medium">Generate</span>
                    </a>

                    <a href="{{ route('admin.vouchers.assign') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.vouchers.assign') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span class="font-medium">Assign</span>
                    </a>

                    <a href="{{ route('admin.vouchers.print') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.vouchers.print*') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span class="font-medium">Print</span>
                    </a>
                </div>

                <!-- Export Section -->
                <div class="pt-4">
                    <a href="{{ route('admin.exports.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-lg hover:bg-teal-600 transition {{ request()->routeIs('admin.exports.*') ? 'bg-teal-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium">Exports</span>
                    </a>
                </div>
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
