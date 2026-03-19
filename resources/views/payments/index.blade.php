<x-app-layout title="Pagos">

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <x-stat-card
            label="Total Cobrado"
            value="RD${{ number_format($stats['total_monto'], 0) }}"
            icon="banknotes"
            color="blue"
            subtitle="{{ $stats['total_count'] }} transacciones"
        />
        <x-stat-card
            label="Este Mes"
            value="RD${{ number_format($stats['mes_monto'], 0) }}"
            icon="calendar"
            color="green"
            subtitle="{{ $stats['mes_count'] }} pagos"
        />
        <x-stat-card
            label="Saldo Pendiente"
            value="RD${{ number_format($stats['saldo_pendiente'], 0) }}"
            icon="clock"
            color="gray"
            subtitle="En préstamos activos"
        />
    </div>

    {{-- Filtros --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <form method="GET" action="{{ route('payments.index') }}" class="flex flex-wrap gap-2">
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <button type="submit" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50">
                Filtrar
            </button>
            @if(request()->hasAny(['fecha_desde','fecha_hasta']))
                <a href="{{ route('payments.index') }}" class="px-3 py-2 text-gray-400 hover:text-gray-600 text-sm">✕ Limpiar</a>
            @endif
        </form>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead style="background: #f9fafb; border-bottom: 1px solid #f3f4f6;">
                <tr>
                    <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">ID</th>
                    <th class="text-left px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Cliente</th>
                    <th class="text-left px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide hidden sm:table-cell">Préstamo</th>
                    <th class="text-right px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Monto</th>
                    <th class="text-left px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide hidden md:table-cell">Fecha</th>
                    <th class="text-left px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide hidden lg:table-cell">Método</th>
                    <th class="text-left px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide hidden lg:table-cell">Registrado por</th>
                    <th class="text-center px-4 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Estado</th>
                    <th class="px-4 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $pago)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-mono text-gray-400">#{{ str_pad($pago->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <a href="{{ route('loans.show', $pago->loan) }}"
                               class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                {{ $pago->loan->client->nombre }}
                            </a>
                        </td>
                        <td class="px-4 py-3.5 hidden sm:table-cell">
                            <span class="text-xs font-mono text-gray-400">#{{ str_pad($pago->loan_id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <span class="font-semibold text-sm" style="color: #16a34a;">
                                +RD${{ number_format($pago->monto, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-gray-500 hidden md:table-cell">
                            {{ $pago->fecha_pago->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3.5 hidden lg:table-cell">
                            <span class="capitalize text-gray-600">{{ $pago->metodo }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-gray-500 text-xs hidden lg:table-cell">
                            {{ $pago->usuario->nombre }}
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  style="background: #1f2937; color: white;">
                                Completado
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <form method="POST" action="{{ route('payments.destroy', $pago) }}"
                                  onsubmit="return confirm('¿Eliminar este pago?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-3" style="background: #f9fafb;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                </svg>
                            </div>
                            <p class="text-gray-400 text-sm">No hay pagos registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($payments->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
        @endif
    </div>

</x-app-layout>
