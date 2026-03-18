@props(['estado'])

@php
$styles = [
    'activo'      => 'bg-green-100 text-green-800',
    'atrasado'    => 'bg-yellow-100 text-yellow-800',
    'en_cobranza' => 'bg-orange-100 text-orange-800',
    'legal'       => 'bg-red-100 text-red-800',
    'cerrado'     => 'bg-gray-100 text-gray-600',
];
$labels = [
    'activo'      => 'Activo',
    'atrasado'    => 'Atrasado',
    'en_cobranza' => 'En Cobranza',
    'legal'       => 'Legal',
    'cerrado'     => 'Cerrado',
];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $styles[$estado] ?? 'bg-gray-100 text-gray-600' }}">
    {{ $labels[$estado] ?? $estado }}
</span>
