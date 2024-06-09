<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">
                    <x-table striped :menu='$menu ?? []' :columns='$columns ?? []' :rows="$items ?? []" table-text-link-label="Name">
                        <x-slot name="tableTextLink">
                            <div>
                                <span x-text="row.name"></span>
                                <div x-text="row.created_at" class="text-xs"></div>
                                <div x-text="row.updated_at" class="text-xs"></div>
                                <template x-if="row.deleted_at">
                                    <div x-text="row.deleted_at" class="text-xs"></div>
                                </template>
                            </div>
                        </x-slot>
                        <x-slot name="tableActions">
                            <div class="flex flex-wrap space-x-6">
                                <a :href="`roles/edit/${row.id}`">
                                    @svg('gmdi-edit-o', 'size-6', ['style' => 'color:#2D20FF'])
                                </a>
                            </div>
                        </x-slot>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
