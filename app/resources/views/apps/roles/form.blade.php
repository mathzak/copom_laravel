<?php
$form = $data ?? false ? $data : new stdClass();
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            <a href="{{ route($parent_route) }}">{{ __('Roles') }}</a> > {{ __($data ?? false ? 'Edit' : 'Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                {{ __('Profile Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __("Update your account's profile information and email address.") }}
                            </p>
                        </header>

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $form->name ?? null)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $form->email ?? null)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                @if ($form instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $form->hasVerifiedEmail())
                                <div>
                                    <p class="text-sm mt-2 text-zinc-800 dark:text-zinc-200">
                                        {{ __('Your email address is unverified.') }}

                                        <button form="send-verification" class="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-zinc-800">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                    @endif
                                </div>
                                @endif
                            </div>

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
