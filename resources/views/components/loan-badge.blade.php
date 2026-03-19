@props(['estado'])

@php
$styles = [
    'activo'      => 'background: #f0fdf4; color: #16a34a;',
    'atrasado'    => 'background: #fef2f2; color: #dc2626;',
    'en_cobranza' => 'background: #fff7ed; color: #ea580c;',
    'legal'       => 'background: #faf5ff; color: #9333ea;',
    'cerrado'     => 'background: #f9fafb; color: #6b7280;',
];
$labels = [
    'activo'      => 'Activo',
    'atrasado'    => 'Atrasado',
    'en_cobranza' => 'En Cobranza',
    'legal'       => 'Legal',
    'cerrado'     => 'Cerrado',
];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
      style="{{ $styles[$estado] ?? 'background: #f9fafb; color: #6b7280;' }}">
    {{ $labels[$estado] ?? $estado }}
</span>
