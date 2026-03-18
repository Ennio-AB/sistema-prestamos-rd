<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistema de Préstamos' }} — PrestamoRD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">

<div class="flex h-full" x-data="{ sidebarOpen: false }">

    {{-- Overlay móvil --}}
    <div x-show="sidebarOpen" x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-900 text-white transform transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0 lg:flex lg:flex-col">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-800">
            <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-lg">P</div>
            <div>
                <div class="font-semibold text-sm leading-tight">PrestamoRD</div>
                <div class="text-blue-300 text-xs">Sistema de Gestión</div>
            </div>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

            <x-nav-link route="dashboard" icon="chart-bar">Dashboard</x-nav-link>

            <div class="pt-3 pb-1 px-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Operaciones</div>
            <x-nav-link route="clients.index" icon="users">Clientes</x-nav-link>
            <x-nav-link route="loans.index" icon="currency-dollar">Préstamos</x-nav-link>
            <x-nav-link route="payments.index" icon="receipt-percent">Pagos</x-nav-link>

            <div class="pt-3 pb-1 px-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Gestión</div>
            <x-nav-link route="legal.index" icon="scale">Legal</x-nav-link>

        </nav>

        {{-- Usuario logueado --}}
        <div class="px-4 py-4 border-t border-blue-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ auth()->user()->nombre }}</div>
                    <div class="text-xs text-blue-300 capitalize">{{ auth()->user()->rol }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar sesión"
                            class="text-blue-300 hover:text-white transition-colors">
                        <x-icon name="arrow-right-on-rectangle" class="w-5 h-5"/>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Contenido principal --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-4 lg:px-6">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 hover:text-gray-700 lg:hidden">
                <x-icon name="bars-3" class="w-6 h-6"/>
            </button>

            <h1 class="text-lg font-semibold text-gray-800 flex-1">
                {{ $title ?? 'Dashboard' }}
            </h1>

            <a href="{{ route('clients.create') }}"
               class="hidden sm:flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <x-icon name="plus" class="w-4 h-4"/>
                Nuevo Cliente
            </a>
        </header>

        {{-- Alertas --}}
        <div class="px-4 lg:px-6">
            @if(session('success'))
                <div class="mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                    <x-icon name="check-circle" class="w-5 h-5 text-green-500 shrink-0"/>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
                    <x-icon name="x-circle" class="w-5 h-5 text-red-500 shrink-0"/>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Página --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

<script>
// Alpine.js mínimo para el sidebar
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Alpine === 'undefined') {
        const data = {};
        document.querySelectorAll('[x-data]').forEach(el => {
            el._sidebarOpen = false;
        });
    }
});
</script>
</body>
</html>
