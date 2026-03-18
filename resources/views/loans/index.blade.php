<x-app-layout title="Préstamos">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <form method="GET" action="{{ route('loans.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar cliente..."
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-52">
            <select name="estado" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los estados</option>
                @foreach(['activo','atrasado','en_cobranza','legal','cerrado'] as $e)
                    <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_',' ',$e)) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','estado']))
                <a href="{{ route('loans.index') }}" class="px-3 py-2 text-gray-400 hover:text-gray-600 text-sm">✕ Limpiar</a>
            @endif
        </form>
        <a href="{{ route('loans.create') }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
            <x-icon name="plus" class="w-4 h-4"/>
            Nuevo Préstamo
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Cliente</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Monto</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Interés</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Plazo</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Estado</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Inicio</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($loans as $loan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-gray-900">{{ $loan->client->nombre }}</div>
                            <div class="text-xs text-gray-400">{{ $loan->client->cedula_formateada }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-right font-semibold text-gray-900">
                            RD${{ number_format($loan->monto, 0) }}
                        </td>
                        <td class="px-4 py-3.5 text-center text-gray-600 hidden md:table-cell">
                            {{ $loan->interes }}% {{ $loan->tipo_interes }}
                        </td>
                        <td class="px-4 py-3.5 text-center text-gray-600 hidden lg:table-cell">
                            {{ $loan->plazo }} {{ $loan->frecuencia }}s
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <x-loan-badge :estado="$loan->estado"/>
                        </td>
                        <td class="px-4 py-3.5 text-gray-500 hidden sm:table-cell">
                            {{ $loan->fecha_inicio->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3.5">
                            <a href="{{ route('loans.show', $loan) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors inline-block" title="Ver">
                                <x-icon name="eye" class="w-4 h-4"/>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            <x-icon name="currency-dollar" class="w-10 h-10 mx-auto mb-2 text-gray-300"/>
                            <p>No hay préstamos registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($loans->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $loans->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
