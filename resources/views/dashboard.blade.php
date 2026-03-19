<x-app-layout title="Dashboard">

    {{-- Stats principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-stat-card
            label="Total Prestado"
            value="RD${{ number_format($stats['total_prestado'], 0) }}"
            icon="banknotes"
            color="blue"
        />
        <x-stat-card
            label="Saldo Pendiente"
            value="RD${{ number_format($stats['saldo_pendiente'], 0) }}"
            icon="chart-bar"
            color="gray"
        />
        <x-stat-card
            label="Clientes Activos"
            value="{{ $stats['clientes_total'] }}"
            icon="users"
            color="green"
        />
        <x-stat-card
            label="Préstamos Atrasados"
            value="{{ $stats['prestamos_atrasados'] + $stats['en_cobranza'] }}"
            icon="exclamation-triangle"
            color="red"
        />
    </div>

    {{-- Tablas recientes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

        {{-- Préstamos recientes --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Préstamos Recientes</h3>
                <a href="{{ route('loans.index') }}" class="text-sm font-medium" style="color: #3b82f6;">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($prestamos_recientes as $loan)
                    <a href="{{ route('loans.show', $loan) }}"
                       class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-white shrink-0"
                             style="background: #3b82f6;">
                            {{ strtoupper(substr($loan->client->nombre, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $loan->client->nombre }}</div>
                            <div class="text-xs text-gray-400">{{ $loan->fecha_inicio->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900">RD${{ number_format($loan->monto, 0) }}</div>
                            <x-loan-badge :estado="$loan->estado"/>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">No hay préstamos registrados</div>
                @endforelse
            </div>
        </div>

        {{-- Pagos recientes --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Pagos Recientes</h3>
                <a href="{{ route('payments.index') }}" class="text-sm font-medium" style="color: #3b82f6;">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($pagos_recientes as $pago)
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-white shrink-0"
                             style="background: #22c55e;">
                            {{ strtoupper(substr($pago->loan->client->nombre, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $pago->loan->client->nombre }}</div>
                            <div class="text-xs text-gray-400">{{ $pago->fecha_pago->format('d/m/Y') }} · {{ $pago->usuario->nombre }}</div>
                        </div>
                        <div class="text-sm font-semibold" style="color: #16a34a;">
                            +RD${{ number_format($pago->monto, 0) }}
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">No hay pagos registrados</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Resumen de Cobranza --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800 text-sm">Resumen de Cobranza</h3>
            @php
                $totalExpected = $stats['total_prestado'] > 0 ? $stats['total_prestado'] : 1;
                $collectionRate = min(100, round(($stats['total_cobrado'] / $totalExpected) * 100, 1));
            @endphp
            <span class="text-sm font-semibold" style="color: #3b82f6;">{{ $collectionRate }}% cobrado</span>
        </div>

        <div class="w-full bg-gray-100 rounded-full h-2.5 mb-4">
            <div class="h-2.5 rounded-full transition-all" style="width: {{ $collectionRate }}%; background: #3b82f6;"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
            <div class="text-center">
                <div class="text-xs text-gray-400 mb-1">Activos</div>
                <div class="text-lg font-bold text-gray-800">{{ $stats['prestamos_activos'] }}</div>
            </div>
            <div class="text-center">
                <div class="text-xs text-gray-400 mb-1">Atrasados</div>
                <div class="text-lg font-bold text-gray-800">{{ $stats['prestamos_atrasados'] }}</div>
            </div>
            <div class="text-center">
                <div class="text-xs text-gray-400 mb-1">Cobranza</div>
                <div class="text-lg font-bold text-gray-800">{{ $stats['en_cobranza'] }}</div>
            </div>
            <div class="text-center">
                <div class="text-xs text-gray-400 mb-1">Legal</div>
                <div class="text-lg font-bold text-gray-800">{{ $stats['en_legal'] }}</div>
            </div>
        </div>
    </div>

</x-app-layout>
