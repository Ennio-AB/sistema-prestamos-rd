<x-app-layout title="Nueva Acción Legal">

    <div class="max-w-xl">
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm">
            <div class="font-semibold text-red-800">{{ $loan->client->nombre }}</div>
            <div class="text-red-600">
                Préstamo #{{ $loan->id }} ·
                <x-loan-badge :estado="$loan->estado"/>
                · Saldo: RD${{ number_format($loan->saldo_pendiente, 2) }}
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('legal.store', $loan) }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo de acción *</label>
                        <select name="tipo"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($tipos as $valor => $label)
                                <option value="{{ $valor }}" {{ old('tipo') === $valor ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha *</label>
                        <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción *</label>
                        <textarea name="descripcion" rows="3"
                                  placeholder="Detalle de la acción realizada..."
                                  class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none
                                         {{ $errors->has('descripcion') ? 'border-red-400' : 'border-gray-300' }}">{{ old('descripcion') }}</textarea>
                        @error('descripcion')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Resultado / Respuesta del cliente</label>
                        <input type="text" name="resultado" value="{{ old('resultado') }}"
                               placeholder="¿Cuál fue la respuesta o resultado?"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                        Registrar Acción
                    </button>
                    <a href="{{ route('loans.show', $loan) }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
