<x-app-layout title="Módulo Legal">

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-sm text-gray-500">Préstamos en seguimiento activo (atrasados, en cobranza o legal)</p>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($loans as $loan)
                <div class="px-5 py-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('clients.show', $loan->client) }}"
                                   class="font-semibold text-gray-900 hover:text-blue-600">
                                    {{ $loan->client->nombre }}
                                </a>
                                <x-loan-badge :estado="$loan->estado"/>
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                Préstamo #{{ $loan->id }} · RD${{ number_format($loan->monto, 2) }} ·
                                Saldo: <span class="text-red-600 font-medium">RD${{ number_format($loan->saldo_pendiente, 2) }}</span>
                            </div>

                            @if($loan->legalActions->isNotEmpty())
                                <div class="mt-2 space-y-1">
                                    @foreach($loan->legalActions->take(3) as $accion)
                                        <div class="text-xs text-gray-500 flex items-center gap-2">
                                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            <span class="capitalize font-medium">{{ str_replace('_',' ',$accion->tipo) }}</span>
                                            <span class="text-gray-400">{{ $accion->fecha->format('d/m/Y') }}</span>
                                            <span class="truncate">{{ $accion->descripcion }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('legal.create', $loan) }}"
                               class="flex items-center gap-1.5 text-xs bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                <x-icon name="plus" class="w-3.5 h-3.5"/>
                                Acción
                            </a>
                            <a href="{{ route('loans.show', $loan) }}"
                               class="text-xs text-gray-400 hover:text-blue-600 transition-colors">
                                <x-icon name="eye" class="w-4 h-4"/>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-14 text-center text-gray-400">
                    <x-icon name="scale" class="w-10 h-10 mx-auto mb-2 text-gray-300"/>
                    <p>No hay préstamos en seguimiento legal</p>
                </div>
            @endforelse
        </div>
        @if($loans->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $loans->links() }}</div>
        @endif
    </div>

</x-app-layout>
