<x-app-layout title="Dashboard">

    {{-- Stats principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-stat-card
            label="Total Prestado"
            value="RD${{ number_format($stats['total_prestado'], 2) }}"
            icon="banknotes"
            color="blue"
        />
        <x-stat-card
            label="Total Cobrado"
            value="RD${{ number_format($stats['total_cobrado'], 2) }}"
            icon="receipt-percent"
            color="green"
        />
        <x-stat-card
            label="Ganancia (Intereses)"
            value="RD${{ number_format($stats['ganancia'], 2) }}"
            icon="currency-dollar"
            color="blue"
            subtitle="Intereses generados"
        />
        <x-stat-card
            label="Saldo Pendiente"
            value="RD${{ number_format($stats['saldo_pendiente'], 2) }}"
            icon="chart-bar"
            color="gray"
        />
    </div>

    {{-- Stats de estado --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <a href="{{ route('loans.index', ['estado' => 'activo']) }}"
           class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['prestamos_activos'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Activos</div>
        </a>
        <a href="{{ route('loans.index', ['estado' => 'atrasado']) }}"
           class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['prestamos_atrasados'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Atrasados</div>
        </a>
        <a href="{{ route('loans.index', ['estado' => 'en_cobranza']) }}"
           class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['en_cobranza'] }}</div>
            <div class="text-xs text-gray-500 mt-1">En Cobranza</div>
        </a>
        <a href="{{ route('loans.index', ['estado' => 'legal']) }}"
           class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['en_legal'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Legal</div>
        </a>
        <a href="{{ route('clients.index') }}"
           class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['clientes_total'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Clientes</div>
        </a>
    </div>

    {{-- Tablas recientes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Préstamos recientes --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Préstamos Recientes</h3>
                <a href="{{ route('loans.index') }}" class="text-blue-600 text-sm hover:underline">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($prestamos_recientes as $loan)
                    <a href="{{ route('loans.show', $loan) }}"
                       class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors">
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
                    <div class="px-5 py-8 text-center text-gray-400 text-sm">No hay préstamos registrados</div>
                @endforelse
            </div>
        </div>

        {{-- Pagos recientes --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Pagos Recientes</h3>
                <a href="{{ route('payments.index') }}" class="text-blue-600 text-sm hover:underline">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($pagos_recientes as $pago)
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $pago->loan->client->nombre }}</div>
                            <div class="text-xs text-gray-400">{{ $pago->fecha_pago->format('d/m/Y') }} · {{ $pago->usuario->nombre }}</div>
                        </div>
                        <div class="text-sm font-semibold text-gray-800">RD${{ number_format($pago->monto, 2) }}</div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-gray-400 text-sm">No hay pagos registrados</div>
                @endforelse
            </div>
        </div>

    </div>

</x-app-layout>
