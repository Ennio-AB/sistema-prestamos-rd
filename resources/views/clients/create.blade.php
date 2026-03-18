<x-app-layout title="Nuevo Cliente">

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('clients.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre completo *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('nombre') ? 'border-red-400' : 'border-gray-300' }}">
                        @error('nombre')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cédula (11 dígitos) *</label>
                        <input type="text" name="cedula" value="{{ old('cedula') }}"
                               maxlength="11" placeholder="00100000000"
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('cedula') ? 'border-red-400' : 'border-gray-300' }}">
                        @error('cedula')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               placeholder="809-000-0000"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Lugar de trabajo</label>
                        <input type="text" name="trabajo" value="{{ old('trabajo') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Referencias</label>
                        <textarea name="referencias" rows="3"
                                  placeholder="Nombre y teléfono de referencias personales o familiares..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('referencias') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                        Guardar Cliente
                    </button>
                    <a href="{{ route('clients.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
