<x-app-layout title="Registrar Pago">

    <div class="max-w-xl">
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm">
            <div class="font-semibold text-blue-800">{{ $loan->client->nombre }}</div>
            <div class="text-blue-600">
                Préstamo #{{ $loan->id }} · RD${{ number_format($loan->monto, 2) }} ·
                Saldo pendiente: <strong>RD${{ number_format($loan->saldo_pendiente, 2) }}</strong>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('payments.store', $loan) }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cuota a pagar (opcional)</label>
                        <select name="installment_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">— Pago libre / sin cuota específica —</option>
                            @foreach($loan->installments as $cuota)
                                <option value="{{ $cuota->id }}"
                                        {{ $cuota->pagado ? 'disabled' : '' }}
                                        style="{{ $cuota->pagado ? 'color:#aaa' : '' }}">
                                    #{{ $cuota->numero_cuota }} · {{ $cuota->fecha_vencimiento->format('d/m/Y') }} ·
                                    RD${{ number_format($cuota->monto, 2) }}
                                    {{ $cuota->pagado ? '(✓ Pagada)' : ($cuota->esta_vencida ? '⚠ Vencida' : '') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Monto (RD$) *</label>
                        <input type="number" name="monto" value="{{ old('monto') }}"
                               step="0.01" min="1"
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('monto') ? 'border-red-400' : 'border-gray-300' }}">
                        @error('monto')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de pago *</label>
                        <input type="date" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Método *</label>
                        <select name="metodo"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['efectivo','transferencia','cheque','otro'] as $m)
                                <option value="{{ $m }}" {{ old('metodo','efectivo') === $m ? 'selected' : '' }}>
                                    {{ ucfirst($m) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Referencia</label>
                        <input type="text" name="referencia" value="{{ old('referencia') }}"
                               placeholder="Comprobante, transferencia..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas</label>
                        <textarea name="notas" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notas') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                        Registrar Pago
                    </button>
                    <a href="{{ route('loans.show', $loan) }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
