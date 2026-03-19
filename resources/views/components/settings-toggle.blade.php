@props(['label', 'description' => null, 'enabled' => false])

<div class="flex items-center justify-between py-4 border-b border-gray-50 last:border-0">
    <div>
        <div class="text-sm font-medium text-gray-800">{{ $label }}</div>
        @if($description)
            <div class="text-xs text-gray-400 mt-0.5">{{ $description }}</div>
        @endif
    </div>
    <button type="button"
            onclick="this.classList.toggle('active'); var bg=this.classList.contains('active')?'#3b82f6':'#e5e7eb'; this.style.background=bg; this.querySelector('span').style.transform=this.classList.contains('active')?'translateX(18px)':'';"
            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors shrink-0"
            style="background: {{ $enabled ? '#3b82f6' : '#e5e7eb' }}; {{ $enabled ? '' : '' }}"
            {{ $enabled ? 'data-active=true' : '' }}>
        <span class="inline-block w-4 h-4 transform rounded-full bg-white shadow-sm transition-transform"
              style="margin-left: 2px; transform: {{ $enabled ? 'translateX(18px)' : '' }};"></span>
    </button>
</div>
