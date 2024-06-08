<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">
                    <x-table striped :columns='$columns' :rows="$items ?? []" table-text-link-label="Names">
                        <x-slot name="tableHeader">
                            <a :href="`users/create`">
                                @svg('gmdi-add-circle-o', 'size-12', ['style' => 'color:#2DAA20'])
                            </a>
                        </x-slot>
                        <x-slot name="tableTextLink">
                            <div class="flex space-x-3 items-center">
                                <div class="w-10">
                                    <img :src="`https://avatars.dicebear.com/api/initials/${row.name}.svg`" alt="avatar" class="rounded-full object-fit" loading="lazy">
                                </div>
                                <div>
                                    <span x-text="row.name"></span>
                                    <div x-text="row.email" class="text-xs"></div>
                                </div>
                            </div>
                        </x-slot>
                        <x-slot name="tableActions">
                            <div class="flex flex-wrap space-x-6">
                                <a :href="`users/edit/${row.id}`">
                                    @svg('gmdi-edit-o', 'size-6', ['style' => 'color:#2D20FF'])
                                </a>
                                <a :href="`users/destroy/${row.id}`" x-on:click.prevent="$dispatch('open-modal', 'confirm-dialog')">
                                    @svg('gmdi-remove-circle-outline', 'size-6', ['style' => 'color:#FF2D20'])
                                </a>

                                <x-modal name="confirm-dialog" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                    <form method="post" :action="`users/destroy/${row.id}`" class=" p-6">
                                        @csrf
                                        @method('delete')

                                        <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ __('Attention!') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                            {{ __('Are you sure you want to delete this item?') }}
                                        </p>

                                        <x-input-error :messages="$errors->userDeletion->get('action')" class="mt-2" />

                                        <div class="mt-6 flex justify-end">
                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                {{ __('Cancel') }}
                                            </x-secondary-button>

                                            <x-danger-button class="ms-3">
                                                {{ __('Delete') }}
                                            </x-danger-button>
                                        </div>
                                    </form>
                                </x-modal>
                            </div>
                        </x-slot>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
