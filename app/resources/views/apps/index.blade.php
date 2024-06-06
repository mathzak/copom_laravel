<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Apps') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-blocks :items=$items />
    </div>
</x-app-layout>
