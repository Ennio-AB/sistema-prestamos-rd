@props(['route', 'icon' => 'circle'])

@php
    $active = request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route));
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
   style="{{ $active
        ? 'background: rgba(255,255,255,0.1); color: white;'
        : 'color: #9ca3af;' }}"
   @if(!$active)
   onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.color='white';"
   onmouseout="this.style.background=''; this.style.color='#9ca3af';"
   @endif>
    <x-icon :name="$icon" class="w-5 h-5 shrink-0"/>
    {{ $slot }}
</a>
