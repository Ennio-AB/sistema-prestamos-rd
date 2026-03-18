<x-app-layout :title="$client->nombre">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600 text-sm">{{ $client->nombre }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('clients.edit', $client) }}"
               class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                <x-icon name="pencil" class="w-4 h-4"/>
                Editar
            </a>
            <a href="{{ route('loans.create', ['client_id' => $client->id]) }}"
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <x-icon name="plus" class="w-4 h-4"/>
                Nuevo Préstamo
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info del cliente --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold text-lg">
                    {{ strtoupper(substr($client->nombre, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">{{ $client->nombre }}</h2>
                    <p class="text-xs text-gray-400">{{ $client->cedula_formateada }}</p>
                </div>
            </div>
            <hr class="border-gray-100">
            @if($client->telefono)
                <div class="flex gap-2 text-sm">
                    <span class="text-gray-400 w-24 shrink-0">Teléfono</span>
                    <span class="text-gray-700 font-medium">{{ $client->telefono }}</span>
                </div>
            @endif
            @if($client->email)
                <div class="flex gap-2 text-sm">
                    <span class="text-gray-400 w-24 shrink-0">Email</span>
                    <span class="text-gray-700 font-medium">{{ $client->email }}</span>
                </div>
            @endif
            @if($client->direccion)
                <div class="flex gap-2 text-sm">
                    <span class="text-gray-400 w-24 shrink-0">Dirección</span>
                    <span class="text-gray-700">{{ $client->direccion }}</span>
                </div>
            @endif
            @if($client->trabajo)
                <div class="flex gap-2 text-sm">
                    <span class="text-gray-400 w-24 shrink-0">Trabajo</span>
                    <span class="text-gray-700">{{ $client->trabajo }}</span>
                </div>
            @endif
            @if($client->referencias)
                <div class="flex gap-2 text-sm">
                    <span class="text-gray-400 w-24 shrink-0">Referencias</span>
                    <span class="text-gray-700 text-xs">{{ $client->referencias }}</span>
                </div>
            @endif
        </div>

        {{-- Historial de préstamos --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Préstamos ({{ $client->loans->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($client->loans as $loan)
                    <a href="{{ route('loans.show', $loan) }}"
                       class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900">RD${{ number_format($loan->monto, 2) }}</span>
                                <x-loan-badge :estado="$loan->estado"/>
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $loan->fecha_inicio->format('d/m/Y') }} · {{ $loan->plazo }} cuotas {{ $loan->frecuencia }}s · {{ $loan->interes }}% {{ $loan->tipo_interes }}
                            </div>
                        </div>
                        <div class="text-right text-xs text-gray-400">
                            <div>Total: RD${{ number_format($loan->total_a_pagar, 2) }}</div>
                            <div class="text-green-600">Pagado: RD${{ number_format($loan->total_pagado, 2) }}</div>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">
                        <x-icon name="currency-dollar" class="w-8 h-8 mx-auto mb-2 text-gray-300"/>
                        No tiene préstamos registrados
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</x-app-layout>
