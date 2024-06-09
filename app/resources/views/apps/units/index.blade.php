<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Units') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">
                    <x-table striped :menu='$menu ?? []' :columns='$columns' :rows="$items ?? []" table-text-link-label="Name">
                        <x-slot name="tableHeader">
                            <a :href="`units/create`">
                                @svg('gmdi-add-circle-o', 'size-12', ['style' => 'color:#2DAA20'])
                            </a>
                        </x-slot>
                        <x-slot name="tableTextLink">
                            <div>
                                <span x-text="row.shortpath"></span>
                                <div x-text="row.created_at" class="text-xs"></div>
                            </div>
                        </x-slot>
                        <x-slot name="tableActions">
                            <div class="flex flex-wrap space-x-6">
                                <a :href="`units/edit/${row.id}`">
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
