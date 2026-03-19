@props(['label', 'value', 'icon', 'color' => 'blue', 'subtitle' => null])

@php
$iconColors = [
    'blue'   => 'background: #eff6ff; color: #3b82f6;',
    'green'  => 'background: #f0fdf4; color: #22c55e;',
    'yellow' => 'background: #fefce8; color: #eab308;',
    'red'    => 'background: #fef2f2; color: #ef4444;',
    'gray'   => 'background: #f9fafb; color: #6b7280;',
    'purple' => 'background: #faf5ff; color: #a855f7;',
];
$iconStyle = $iconColors[$color] ?? $iconColors['blue'];
@endphp

<div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm text-gray-500 font-medium">{{ $label }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-gray-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="{{ $iconStyle }}">
            <x-icon :name="$icon" class="w-5 h-5"/>
        </div>
    </div>
</div>
