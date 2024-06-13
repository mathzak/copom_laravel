<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            <a href="{{ route($index) }}">{{ __($label ?? null) }}</a>
            @if ($subLabel ?? false)
            > {{ __($subLabel ?? null) }}
            @endif
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

            @foreach ($components as $component)
            <div class="bg-white dark:bg-zinc-800 overflow-visible shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">
                    <header>
                        @if ($component['label'] ?? false)
                        <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __($component['label'] ?? null) }}
                        </h2>
                        @endif

                        @if ($component['description'] ?? false)
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __($component['description'] ?? null) }}
                        </p>
                        @endif
                    </header>

                    @if ($component['type'] === 'form')
                    <form method="post" action="{{ $component['action'] ?? null }}" class="mt-6 space-y-6">
                        @csrf
                        @method($component['method'] ?? 'get')

                        @foreach ($component['fields'] ?? [] as $fields)

                        @php
                        $cols = collect($fields)->map(function ($item) {
                        if (!isset($item['span'])) {
                        $item['span'] = 1;
                        }
                        return $item;
                        });
                        @endphp

                        <div class="grid gap-4 grid-cols-{{ $cols->sum('span') }}">
                            @foreach ($fields as $field)

                            @php
                            $field = collect($field)->toArray();
                            $value = $component['data'] ?? [] ? $component['data'][$field['name']] : null;
                            @endphp

                            <div class="col-span-{{ $field['span'] ?? 1 }}">
                                <x-input-label for="{{ $field['name'] }}" class="w-full" :value="__($field['label'])" />
                                @if ($field['type'] == 'calendar')
                                <x-calendar id="{{ $field['name'] }}" class="w-full" name="{{ $field['name'] }}" type="text" :value="old($field['name'], $value ?? null)" />
                                @elseif ($field['type'] == 'input')
                                <x-text-input id="{{ $field['name'] }}" class="w-full" name="{{ $field['name'] }}" type="text" mask="{{ $field['mask'] ?? null }}" :value="old($field['name'], $value ?? null)" />
                                @elseif ($field['type'] == 'select')
                                <x-select id="{{ $field['name'] }}" class="w-full" name="{{ $field['name'] }}" :options="$field['options']" :multiple="$field['multiple'] ?? false" :value="old($field['name'], $value ?? [])" />
                                @elseif ($field['type'] == 'toggle')
                                <x-toggle id="{{ $field['name'] }}" class="w-full" name="{{ $field['name'] }}" :checked="old($field['name'], $value ?? false)" />
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get($field['name'])" />
                            </div>
                            @endforeach
                        </div>
                        @endforeach

                        @if ($component['action'] ?? false)
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                        @endif
                    </form>
                    @endif

                    @if ($component['type'] === 'index')
                    <script>
                        window.appRoutes = {
                            routeEdit: "{{ $edit ?? null ? route($edit, '__id__') : null }}"
                        };
                    </script>

                    @php
                    $menu = $component['menu'] ?? [];
                    $columns = $component['columns'] ?? [];
                    $items = $component['data'] ?? [];
                    $name = $component['nameColumn'] ?? [];
                    $action = $component['action'] ?? null;
                    @endphp

                    <div class="py-6">
                        <x-table striped :menu="$menu" :columns="$columns" :rows="$items" table-text-link-label="Name">
                            <x-slot name="tableTextLink">
                                <div>
                                    @foreach ($name ?? [] as $item)
                                    <template x-if="row.{{ $item['field'] }}">
                                        <div x-text="row.{{ $item['field'] }}" class="{{ $item['class'] }}"></div>
                                    </template>
                                    @endforeach
                                </div>
                            </x-slot>
                            <x-slot name="tableActions">
                                <div x-data="{ rowId: row.id, editUrl: '' }" x-init="editUrl = `{{ route($action, '__id__') }}`.replace('__id__', rowId)">
                                    <a :href="editUrl">
                                        @svg('gmdi-edit-o', 'size-6', ['style' => 'color:#2D20FF'])
                                    </a>
                                </div>
                            </x-slot>
                        </x-table>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
