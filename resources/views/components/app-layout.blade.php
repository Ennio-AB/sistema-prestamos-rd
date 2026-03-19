<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Loan Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-50">

<div class="flex h-full" x-data="{ sidebarOpen: false }">

    {{-- Overlay móvil --}}
    <div x-show="sidebarOpen" x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/60 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-60 transform transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0 lg:flex lg:flex-col"
           style="background-color: #1a1f2e;">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #3b82f6;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-white text-sm leading-tight">PrestamoRD</div>
                <div class="text-xs" style="color: #6b7280;">Sistema de Gestión</div>
            </div>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <x-nav-link route="dashboard" icon="squares-2x2">Dashboard</x-nav-link>
            <x-nav-link route="clients.index" icon="users">Clientes</x-nav-link>
            <x-nav-link route="loans.index" icon="document-text">Préstamos</x-nav-link>
            <x-nav-link route="payments.index" icon="credit-card">Pagos</x-nav-link>
            <x-nav-link route="settings" icon="cog-6-tooth">Configuración</x-nav-link>
        </nav>

        {{-- Usuario --}}
        <div class="px-3 py-4" style="border-top: 1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center gap-3 px-2 py-2 rounded-lg">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold text-white shrink-0"
                     style="background: #3b82f6;">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ auth()->user()->nombre }}</div>
                    <div class="text-xs capitalize" style="color: #6b7280;">{{ auth()->user()->rol }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar sesión" style="color: #6b7280;" class="hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Contenido principal --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 px-5 py-3.5 flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-400 hover:text-gray-600 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <h1 class="text-base font-semibold text-gray-800 flex-1">{{ $title ?? 'Dashboard' }}</h1>

            <a href="{{ route('clients.create') }}"
               class="hidden sm:flex items-center gap-1.5 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors"
               style="background: #3b82f6;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nuevo Cliente
            </a>
        </header>

        {{-- Alertas --}}
        @if(session('success') || session('error'))
            <div class="px-5 pt-4">
                @if(session('success'))
                    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Página --}}
        <main class="flex-1 overflow-y-auto p-5">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
