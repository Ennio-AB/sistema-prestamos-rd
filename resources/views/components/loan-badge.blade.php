@props(['estado'])

@php
$styles = [
    'activo'      => 'bg-gray-100 text-gray-700',
    'atrasado'    => 'bg-gray-100 text-gray-700',
    'en_cobranza' => 'bg-gray-100 text-gray-700',
    'legal'       => 'bg-gray-100 text-gray-700',
    'cerrado'     => 'bg-gray-100 text-gray-500',
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
