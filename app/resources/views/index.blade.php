<script>
    window.appRoutes = {
        routeEdit: "{{ route($edit, '__id__') }}"
    };
</script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __($label) }}
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
                    <x-table striped :menu='$menu ?? []' :columns='$columns ?? []' :rows="$items ?? []" table-text-link-label="Name">
                        <x-slot name="tableTextLink">
                            <div>
                                @foreach ($name as $item)
                                <template x-if="row.{{ $item['field'] }}">
                                    <div x-text="row.{{ $item['field'] }}" class="{{ $item['class'] }}"></div>
                                </template>
                                @endforeach
                            </div>
                        </x-slot>
                        <x-slot name="tableActions">
                            <div x-data="{ rowId: row.id, editUrl: '' }" x-init="editUrl = window.appRoutes.routeEdit.replace('__id__', rowId)">
                                <a :href="editUrl">
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
