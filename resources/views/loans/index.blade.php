<x-app-layout title="Préstamos">

    {{-- Filtros + Botón --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <form method="GET" action="{{ route('loans.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar cliente..."
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-48">
            <select name="estado" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Todos los estados</option>
                @foreach(['activo','atrasado','en_cobranza','legal','cerrado'] as $e)
                    <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>
                        {{ ['activo'=>'Activo','atrasado'=>'Atrasado','en_cobranza'=>'En Cobranza','legal'=>'Legal','cerrado'=>'Cerrado'][$e] }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','estado']))
                <a href="{{ route('loans.index') }}" class="px-3 py-2 text-gray-400 hover:text-gray-600 text-sm">✕ Limpiar</a>
            @endif
        </form>
        <a href="{{ route('loans.create') }}"
           class="flex items-center gap-1.5 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors whitespace-nowrap"
           style="background: #3b82f6;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Préstamo
        </a>
    </div>

    {{-- Cards de préstamos --}}
    @if($loans->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background: #eff6ff;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" style="color: #3b82f6;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <p class="text-gray-500 text-sm">No hay préstamos registrados</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($loans as $loan)
                @php
                    $totalPagado = $loan->total_pagado;
                    $totalAPagar = $loan->total_a_pagar;
                    $progreso = $totalAPagar > 0 ? min(100, round(($totalPagado / $totalAPagar) * 100)) : 0;
                    $fechaFin = $loan->fecha_inicio->copy()->addMonths($loan->plazo);
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">

                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold text-white shrink-0"
                                 style="background: #3b82f6;">
                                {{ strtoupper(substr($loan->client->nombre, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 text-sm">{{ $loan->client->nombre }}</div>
                                <div class="text-xs text-gray-400">Préstamo #{{ $loan->id }}</div>
                            </div>
                        </div>
                        <x-loan-badge :estado="$loan->estado"/>
                    </div>

                    {{-- Info grid --}}
                    <div class="grid grid-cols-2 gap-2 mb-4 text-xs">
                        <div class="bg-gray-50 rounded-lg p-2.5">
                            <div class="text-gray-400 mb-0.5">Fecha Inicio</div>
                            <div class="font-semibold text-gray-700">{{ $loan->fecha_inicio->format('d/m/Y') }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2.5">
                            <div class="text-gray-400 mb-0.5">Fecha Fin</div>
                            <div class="font-semibold text-gray-700">{{ $fechaFin->format('d/m/Y') }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2.5">
                            <div class="text-gray-400 mb-0.5">Plazo</div>
                            <div class="font-semibold text-gray-700">{{ $loan->plazo }} {{ $loan->frecuencia }}s</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2.5">
                            <div class="text-gray-400 mb-0.5">Saldo</div>
                            <div class="font-semibold text-gray-700">RD${{ number_format($loan->saldo_pendiente, 0) }}</div>
                        </div>
                    </div>

                    {{-- Barra de progreso --}}
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                            <span>Progreso de Pago</span>
                            <span>{{ $progreso }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all" style="width: {{ $progreso }}%; background: #3b82f6;"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>RD${{ number_format($totalPagado, 0) }} pagado</span>
                            <span>RD${{ number_format($totalAPagar, 0) }} total</span>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex gap-2">
                        <a href="{{ route('loans.show', $loan) }}"
                           class="flex-1 text-center text-sm font-medium py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                            Ver Detalles
                        </a>
                        <a href="{{ route('payments.create', $loan) }}"
                           class="flex-1 text-center text-sm font-medium py-2 rounded-lg text-white transition-colors"
                           style="background: #3b82f6;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                            Registrar Pago
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($loans->hasPages())
            <div class="mt-5">{{ $loans->links() }}</div>
        @endif
    @endif

</x-app-layout>
