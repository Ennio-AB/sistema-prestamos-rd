<x-app-layout title="Pagos">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <form method="GET" action="{{ route('payments.index') }}" class="flex flex-wrap gap-2">
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">
                Filtrar
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Cliente</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Monto</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Método</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Registrado por</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Fecha</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $pago)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('loans.show', $pago->loan) }}"
                               class="font-medium text-blue-600 hover:underline">
                                {{ $pago->loan->client->nombre }}
                            </a>
                            <div class="text-xs text-gray-400">Préstamo #{{ $pago->loan_id }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-right font-semibold text-green-600">
                            RD${{ number_format($pago->monto, 2) }}
                        </td>
                        <td class="px-4 py-3.5 text-gray-600 capitalize hidden sm:table-cell">{{ $pago->metodo }}</td>
                        <td class="px-4 py-3.5 text-gray-500 hidden md:table-cell">{{ $pago->usuario->nombre }}</td>
                        <td class="px-4 py-3.5 text-gray-600">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                        <td class="px-4 py-3.5">
                            <form method="POST" action="{{ route('payments.destroy', $pago) }}"
                                  onsubmit="return confirm('¿Eliminar este pago?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 text-gray-300 hover:text-red-500 transition-colors">
                                    <x-icon name="trash" class="w-4 h-4"/>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <x-icon name="receipt-percent" class="w-10 h-10 mx-auto mb-2 text-gray-300"/>
                            <p>No hay pagos registrados</p>
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
