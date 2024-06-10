@props(['type' => 'success'])

@php
$classes = $type === 'error' ? 'bg-red-700' : 'bg-green-700';
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="p-4 mb-4 text-sm text-white {{ $classes }} rounded-lg" role="alert">
    <button @click="show = false" class="absolute top-0 right-0 mt-2 mr-2 text-white">
        @svg('gmdi-close', 'size-4')
    </button>

    {{ $slot }}
</div>
