@props(['route', 'icon' => 'circle'])

@php
    $active = request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route));
@endphp

<a href="{{ route($route) }}"
   class="{{ $active
        ? 'bg-blue-800 text-white'
        : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}
        flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
    <x-icon :name="$icon" class="w-5 h-5 shrink-0"/>
    {{ $slot }}
</a>
