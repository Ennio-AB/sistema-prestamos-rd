<x-app-layout title="Nuevo Préstamo">

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('loans.store') }}" class="space-y-5" id="loan-form">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente *</label>
                        <select name="client_id"
                                class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                       {{ $errors->has('client_id') ? 'border-red-400' : 'border-gray-300' }}">
                            <option value="">— Seleccionar cliente —</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', request('client_id')) == $client->id ? 'selected' : '' }}>
                                    {{ $client->nombre }} ({{ $client->cedula_formateada }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Monto (RD$) *</label>
                        <input type="number" name="monto" value="{{ old('monto') }}"
                               step="0.01" min="100" placeholder="10000.00"
                               oninput="calcularCuota()"
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('monto') ? 'border-red-400' : 'border-gray-300' }}">
                        @error('monto')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de inicio *</label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('fecha_inicio')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Interés (%) *</label>
                        <input type="number" name="interes" value="{{ old('interes', 5) }}"
                               step="0.01" min="0" max="100" oninput="calcularCuota()"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('interes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo de interés *</label>
                        <select name="tipo_interes" onchange="calcularCuota()"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="mensual" {{ old('tipo_interes') === 'mensual' ? 'selected' : '' }}>Mensual</option>
                            <option value="anual" {{ old('tipo_interes') === 'anual' ? 'selected' : '' }}>Anual</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Plazo (cuotas) *</label>
                        <input type="number" name="plazo" value="{{ old('plazo', 12) }}"
                               min="1" max="360" oninput="calcularCuota()"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('plazo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Frecuencia de pago *</label>
                        <select name="frecuencia"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['diario','semanal','quincenal','mensual'] as $f)
                                <option value="{{ $f }}" {{ old('frecuencia', 'mensual') === $f ? 'selected' : '' }}>
                                    {{ ucfirst($f) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">% Mora *</label>
                        <input type="number" name="mora_porcentaje" value="{{ old('mora_porcentaje', 5) }}"
                               step="0.01" min="0" max="50"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas</label>
                        <textarea name="notas" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notas') }}</textarea>
                    </div>
                </div>

                {{-- Resumen del cálculo --}}
                <div id="resumen" class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm hidden">
                    <div class="font-semibold text-blue-800 mb-2">Resumen del Préstamo</div>
                    <div class="grid grid-cols-2 gap-y-1 text-blue-700">
                        <span>Total intereses:</span><span id="r-intereses" class="font-semibold text-right">—</span>
                        <span>Total a pagar:</span><span id="r-total" class="font-semibold text-right">—</span>
                        <span>Cuota estimada:</span><span id="r-cuota" class="font-bold text-lg text-right text-blue-900">—</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                        Crear Préstamo y Generar Cuotas
                    </button>
                    <a href="{{ route('loans.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function calcularCuota() {
        const monto = parseFloat(document.querySelector('[name=monto]').value) || 0;
        const interes = parseFloat(document.querySelector('[name=interes]').value) || 0;
        const tipo = document.querySelector('[name=tipo_interes]').value;
        const plazo = parseInt(document.querySelector('[name=plazo]').value) || 0;

        if (monto <= 0 || plazo <= 0) {
            document.getElementById('resumen').classList.add('hidden');
            return;
        }

        const tasaMensual = tipo === 'anual' ? interes / 12 : interes;
        const totalIntereses = monto * (tasaMensual / 100) * plazo;
        const totalPagar = monto + totalIntereses;
        const cuota = totalPagar / plazo;

        const fmt = n => 'RD$' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        document.getElementById('r-intereses').textContent = fmt(totalIntereses);
        document.getElementById('r-total').textContent = fmt(totalPagar);
        document.getElementById('r-cuota').textContent = fmt(cuota);
        document.getElementById('resumen').classList.remove('hidden');
    }
    document.addEventListener('DOMContentLoaded', calcularCuota);
    </script>

</x-app-layout>
