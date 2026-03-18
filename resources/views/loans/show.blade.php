<x-app-layout :title="'Préstamo #' . $loan->id">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('loans.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
            </a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('clients.show', $loan->client) }}" class="text-blue-600 text-sm hover:underline">
                {{ $loan->client->nombre }}
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600 text-sm">Préstamo #{{ $loan->id }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('payments.create', $loan) }}"
               class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <x-icon name="banknotes" class="w-4 h-4"/>
                Registrar Pago
            </a>
            <a href="{{ route('legal.create', $loan) }}"
               class="flex items-center gap-2 border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <x-icon name="scale" class="w-4 h-4"/>
                Acción Legal
            </a>
            <a href="{{ route('loans.contrato', $loan) }}" target="_blank"
               class="flex items-center gap-2 border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <x-icon name="document-text" class="w-4 h-4"/>
                Contrato
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Resumen del préstamo --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-2xl font-bold text-gray-900">RD${{ number_format($loan->monto, 2) }}</div>
                        <div class="text-sm text-gray-500">Monto prestado</div>
                    </div>
                    <x-loan-badge :estado="$loan->estado"/>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Interés</span>
                        <span class="font-medium">{{ $loan->interes }}% {{ $loan->tipo_interes }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Plazo</span>
                        <span class="font-medium">{{ $loan->plazo }} cuotas {{ $loan->frecuencia }}s</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Mora</span>
                        <span class="font-medium">{{ $loan->mora_porcentaje }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Inicio</span>
                        <span class="font-medium">{{ $loan->fecha_inicio->format('d/m/Y') }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total intereses</span>
                        <span class="font-medium">RD${{ number_format($loan->total_intereses, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total a pagar</span>
                        <span class="font-bold">RD${{ number_format($loan->total_a_pagar, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Pagado</span>
                        <span class="font-bold">RD${{ number_format($loan->total_pagado, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-blue-600">
                        <span>Saldo pendiente</span>
                        <span class="font-bold">RD${{ number_format($loan->saldo_pendiente, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Cambiar estado --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h4 class="font-semibold text-gray-700 mb-3 text-sm">Cambiar Estado</h4>
                <form method="POST" action="{{ route('loans.update', $loan) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="notas" value="{{ $loan->notas }}">
                    <input type="hidden" name="mora_porcentaje" value="{{ $loan->mora_porcentaje }}">
                    <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3">
                        @foreach(['activo','atrasado','en_cobranza','legal','cerrado'] as $e)
                            <option value="{{ $e }}" {{ $loan->estado === $e ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$e)) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                        Actualizar Estado
                    </button>
                </form>
            </div>
        </div>

        {{-- Cuotas y pagos --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Cuotas --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Cuotas ({{ $loan->installments->count() }})</h3>
                    @php $vencidas = $loan->cuotas_vencidas->count(); @endphp
                    @if($vencidas > 0)
                        <span class="flex items-center gap-1 text-xs text-red-600 bg-red-50 px-2.5 py-1 rounded-full">
                            <x-icon name="exclamation-triangle" class="w-3.5 h-3.5"/>
                            {{ $vencidas }} vencida{{ $vencidas > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2.5 font-medium text-gray-600">#</th>
                                <th class="text-left px-4 py-2.5 font-medium text-gray-600">Vencimiento</th>
                                <th class="text-right px-4 py-2.5 font-medium text-gray-600">Cuota</th>
                                <th class="text-right px-4 py-2.5 font-medium text-gray-600">Mora</th>
                                <th class="text-center px-4 py-2.5 font-medium text-gray-600">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($loan->installments as $cuota)
                                <tr class="{{ $cuota->pagado ? 'bg-green-50/30' : ($cuota->esta_vencida ? 'bg-red-50/30' : '') }}">
                                    <td class="px-4 py-2.5 text-gray-500">{{ $cuota->numero_cuota }}</td>
                                    <td class="px-4 py-2.5 text-gray-700">{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2.5 text-right font-medium">RD${{ number_format($cuota->monto, 2) }}</td>
                                    <td class="px-4 py-2.5 text-right text-red-600">
                                        {{ $cuota->mora > 0 ? 'RD$'.number_format($cuota->mora, 2) : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        @if($cuota->pagado)
                                            <span class="text-green-600 text-xs font-medium">✓ Pagada</span>
                                        @elseif($cuota->esta_vencida)
                                            <span class="text-red-600 text-xs font-medium">Vencida</span>
                                        @else
                                            <span class="text-gray-400 text-xs">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagos --}}
            @if($loan->payments->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Pagos Registrados</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-medium text-gray-600">Fecha</th>
                            <th class="text-right px-4 py-2.5 font-medium text-gray-600">Monto</th>
                            <th class="text-left px-4 py-2.5 font-medium text-gray-600 hidden sm:table-cell">Método</th>
                            <th class="text-left px-4 py-2.5 font-medium text-gray-600 hidden md:table-cell">Registrado por</th>
                            <th class="px-4 py-2.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($loan->payments as $pago)
                            <tr>
                                <td class="px-4 py-2.5 text-gray-700">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold text-green-600">RD${{ number_format($pago->monto, 2) }}</td>
                                <td class="px-4 py-2.5 text-gray-500 capitalize hidden sm:table-cell">{{ $pago->metodo }}</td>
                                <td class="px-4 py-2.5 text-gray-500 hidden md:table-cell">{{ $pago->usuario->nombre }}</td>
                                <td class="px-4 py-2.5">
                                    <form method="POST" action="{{ route('payments.destroy', $pago) }}"
                                          onsubmit="return confirm('¿Eliminar este pago?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 text-gray-300 hover:text-red-500 transition-colors">
                                            <x-icon name="trash" class="w-4 h-4"/>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Acciones legales --}}
            @if($loan->legalActions->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Historial Legal</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($loan->legalActions as $accion)
                        <div class="px-5 py-3 flex items-start gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-gray-700 capitalize">
                                        {{ str_replace('_',' ', $accion->tipo) }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $accion->fecha->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-0.5">{{ $accion->descripcion }}</p>
                                @if($accion->resultado)
                                    <p class="text-xs text-gray-400 mt-0.5">Resultado: {{ $accion->resultado }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('legal.destroy', $accion) }}"
                                  onsubmit="return confirm('¿Eliminar este registro?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 text-gray-300 hover:text-red-500 transition-colors">
                                    <x-icon name="trash" class="w-3.5 h-3.5"/>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</x-app-layout>
