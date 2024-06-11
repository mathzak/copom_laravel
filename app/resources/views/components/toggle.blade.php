@props(['id', 'name', 'checked' => false])

<div x-data="{ checked: {{ $checked ? 'true' : 'false' }} }">
    <button type="button" @click="checked = !checked; $refs.hiddenInput.value = checked ? 1 : 0" :class="checked ? 'bg-green-500' : 'bg-red-500'" class="text-white font-bold mt-1 rounded w-full h-10 flex items-center justify-center">
        <x-gmdi-check-o x-show="checked" class="size-6" />
        <x-gmdi-clear-o x-show="!checked" class="size-6" />
    </button>
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" x-ref="hiddenInput" :value="checked ? 1 : 0">
</div>
