<?php
$form = $data ?? false ? $data : [];
// dd($form);
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            <a href="{{ route($index) }}">{{ __($label) }}</a> > {{ __($data ?? false ? 'Edit' : 'Add') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                {{ __($descriptionLabel) }}
                            </h2>

                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __($descriptionText) }}
                            </p>
                        </header>

                        <form method="post" action="{{ $formAction }}" class="mt-6 space-y-6">
                            @csrf
                            @method($formMethod)

                            @foreach ($formFields as $fields)
                            <div class="flex">
                                @foreach ($fields as $field)
                                <div class="{{ $field['class'] }}">
                                    <x-input-label for="{{ $field['name'] }}" :value="__($field['label'])" />
                                    @if ($field['type'] == 'input')
                                    <x-text-input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="text" class="mt-1 block w-full" :value="old('description', $form[$field['name']] ?? null)" />
                                    @elseif ($field['type'] == 'toggle')
                                    <x-toggle id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="mt-1 block w-full" :checked="old('active', $form[$field['name']] ?? false)" />
                                    @elseif ($field['type'] == 'multiselect')
                                    <x-multiselect id="{{ $field['name'] }}" name="{{ $field['name'] }}" :options="$field['options']" :value="old('name', $form[$field['name']] ?? [])"></x-multiselect>
                                    @endif
                                    <x-input-error class="mt-2" :messages="$errors->get($field['name'])" />
                                </div>
                                @endforeach
                            </div>
                            @endforeach

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
