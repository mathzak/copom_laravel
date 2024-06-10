<?php
$form = $data ?? false ? $data : [];
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            <a href="{{ route($index) }}">{{ __($label) }}</a> > {{ __($data ?? false ? 'Edit' : 'Add') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
            <x-message type="success">
                {{ session('status') }}
            </x-message>
            @endif

            @if (session('error'))
            <x-message type="error">
                {{ session('error') }}
            </x-message>
            @endif

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

                            @foreach ($formFields as $_formFields)
                            <div class="flex">
                                @foreach ($_formFields as $_formField)
                                <div class="{{ $_formField['class'] }}">
                                    <x-input-label for="{{ $_formField['name'] }}" :value="__($_formField['label'])" />
                                    @if ($_formField['type'] == 'input')
                                    <x-text-input id="{{ $_formField['name'] }}" name="{{ $_formField['name'] }}" type="text" class="mt-1 block w-full" :value="old($_formField['name'], $form[$_formField['name']] ?? null)" />
                                    @elseif ($_formField['type'] == 'toggle')
                                    <x-toggle id="{{ $_formField['name'] }}" name="{{ $_formField['name'] }}" class="mt-1 block w-full" :checked="old($_formField['name'], $form[$_formField['name']] ?? false)" />
                                    @elseif ($_formField['type'] == 'multiselect')
                                    <x-multiselect id="{{ $_formField['name'] }}" name="{{ $_formField['name'] }}" :options="$_formField['options']" :value="old($_formField['name'], $form[$_formField['name']] ?? [])" />
                                    @endif
                                    <x-input-error class="mt-2" :messages="$errors->get($_formField['name'])" />
                                </div>
                                @endforeach
                            </div>
                            @endforeach

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
