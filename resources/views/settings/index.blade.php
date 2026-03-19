<x-app-layout title="Configuración">

    <div class="max-w-2xl space-y-5">

        {{-- Configuración General --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Configuración General</h3>
                <p class="text-xs text-gray-400 mt-0.5">Preferencias del sistema</p>
            </div>
            <div class="px-5 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Moneda</label>
                    <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option selected>Peso Dominicano (RD$)</option>
                        <option>Dólar Americano (USD)</option>
                        <option>Euro (EUR)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Formato de Fecha</label>
                    <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option selected>DD/MM/YYYY</option>
                        <option>MM/DD/YYYY</option>
                        <option>YYYY-MM-DD</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Configuración de Préstamos --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Configuración de Préstamos</h3>
                <p class="text-xs text-gray-400 mt-0.5">Valores predeterminados para nuevos préstamos</p>
            </div>
            <div class="px-5 py-4 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tasa de Interés (%)</label>
                        <input type="number" value="5" step="0.5" min="0"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Plazo Predeterminado (meses)</label>
                        <input type="number" value="12" min="1"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tasa de Mora (%)</label>
                    <input type="number" value="2" step="0.5" min="0"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Notificaciones --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Notificaciones</h3>
                <p class="text-xs text-gray-400 mt-0.5">Gestionar alertas y recordatorios</p>
            </div>
            <div class="px-5 py-1">
                <x-settings-toggle label="Notificaciones por Email" description="Recibir actualizaciones por correo" :enabled="true"/>
                <x-settings-toggle label="Recordatorios de Pago" description="Alertar antes de fechas de vencimiento" :enabled="true"/>
                <x-settings-toggle label="Alertas de Mora" description="Notificar cuando un préstamo se atrasa" :enabled="false"/>
            </div>
        </div>

        {{-- Seguridad --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Seguridad</h3>
                <p class="text-xs text-gray-400 mt-0.5">Proteger tu cuenta</p>
            </div>
            <div class="px-5 py-1">
                <x-settings-toggle label="Autenticación de Dos Factores" description="Añadir una capa extra de seguridad" :enabled="false"/>
                <x-settings-toggle label="Cierre Automático de Sesión" description="Cerrar sesión tras 30 min de inactividad" :enabled="true"/>
            </div>
        </div>

        {{-- Guardar --}}
        <div class="flex justify-end">
            <button type="button"
                    class="px-6 py-2.5 text-white text-sm font-medium rounded-lg transition-colors"
                    style="background: #3b82f6;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                Guardar Cambios
            </button>
        </div>

    </div>

</x-app-layout>
