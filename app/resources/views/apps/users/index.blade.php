<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">
                    <x-table striped :columns='$columns' :rows="$items ?? []" table-text-link-label="Name">
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
                        <x-slot name="tableHeader">
                            <div class="flex flex-wrap space-x-4">
                                <a :href="`users/create`">
                                    @svg('heroicon-s-plus-circle', 'size-12', ['style' => 'color:#2DFF20'])
                                </a>
                            </div>
                        </x-slot>
                        <x-slot name="tableActions">
                            <div class="flex flex-wrap space-x-4">
                                <a :href="`users/edit/${row.id}`">
                                    @svg('heroicon-o-pencil-square', 'size-6', ['style' => 'color:#2D20FF'])
                                </a>
                                <a :href="`users/destroy/${row.id}`">
                                    @svg('heroicon-o-x-circle', 'size-6', ['style' => 'color:#FF2D20'])
                                </a>
                            </div>
                        </x-slot>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
