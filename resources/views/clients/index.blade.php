<x-app-layout title="Clientes">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <form method="GET" action="{{ route('clients.index') }}" class="flex gap-2 flex-1 max-w-md">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre, cédula o teléfono..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                Buscar
            </button>
            @if(request('buscar'))
                <a href="{{ route('clients.index') }}" class="px-3 py-2 text-gray-400 hover:text-gray-600">✕</a>
            @endif
        </form>
        <a href="{{ route('clients.create') }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <x-icon name="plus" class="w-4 h-4"/>
            Nuevo Cliente
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Nombre</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Cédula</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Teléfono</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Préstamos</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($clients as $client)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-gray-900">{{ $client->nombre }}</div>
                            <div class="text-xs text-gray-400 md:hidden">{{ $client->cedula_formateada }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-gray-600 hidden md:table-cell">{{ $client->cedula_formateada }}</td>
                        <td class="px-4 py-3.5 text-gray-600 hidden sm:table-cell">{{ $client->telefono ?? '—' }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                                {{ $client->loans_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($client->activo)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Activo</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('clients.show', $client) }}"
                                   class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors" title="Ver detalle">
                                    <x-icon name="eye" class="w-4 h-4"/>
                                </a>
                                <a href="{{ route('clients.edit', $client) }}"
                                   class="p-1.5 text-gray-400 hover:text-yellow-600 transition-colors" title="Editar">
                                    <x-icon name="pencil" class="w-4 h-4"/>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <x-icon name="users" class="w-10 h-10 mx-auto mb-2 text-gray-300"/>
                            <p>No hay clientes registrados</p>
                            <a href="{{ route('clients.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">
                                Crear primer cliente
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($clients->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $clients->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
