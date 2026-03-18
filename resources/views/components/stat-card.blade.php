@props(['label', 'value', 'icon', 'color' => 'blue', 'subtitle' => null])

@php
$colors = [
    'blue'   => 'bg-blue-50 text-blue-600 ring-blue-100',
    'green'  => 'bg-green-50 text-green-600 ring-green-100',
    'yellow' => 'bg-yellow-50 text-yellow-600 ring-yellow-100',
    'red'    => 'bg-red-50 text-red-600 ring-red-100',
    'gray'   => 'bg-gray-50 text-gray-600 ring-gray-100',
];
$iconClass = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4 shadow-sm">
    <div class="p-2.5 rounded-lg ring-1 {{ $iconClass }}">
        <x-icon :name="$icon" class="w-6 h-6"/>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm text-gray-500">{{ $label }}</p>
        <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $value }}</p>
        @if($subtitle)
            <p class="text-xs text-gray-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
</div>
