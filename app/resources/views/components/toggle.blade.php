@props(['id', 'name', 'checked' => false])

<div x-data="{ checked: {{ $checked ? 'true' : 'false' }} }">
    <button type="button" @click="checked = !checked; $refs.hiddenInput.value = checked ? 1 : 0" :class="checked ? 'bg-green-500' : 'bg-red-500'" class="text-white font-bold py-2 px-4 rounded w-full h-10 mt-1">
        <span x-text="checked ? 'On' : 'Off'"></span>
    </button>
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" x-ref="hiddenInput" :value="checked ? 1 : 0">
</div>
