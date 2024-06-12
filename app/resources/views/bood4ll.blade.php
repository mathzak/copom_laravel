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
                    <section>
                        <header>
                            @if ($component['label'] ?? false ? true : false)
                            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                {{ __($component['label'] ?? null) }}
                            </h2>
                            @endif

                            @if ($component['description'] ?? false ? true : false)
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
                            <div class="flex w-full">
                                @foreach ($fields as $field)

                                @php
                                $data = $component['data'] ?? [];
                                @endphp

                                <div class="{{ $field['class'] }} w-full">
                                    <x-input-label for="{{ $field['name'] }}" :value="__($field['label'])" />
                                    @if ($field['type'] == 'calendar')
                                    <x-calendar id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="text" class="mt-1 block w-full" :value="old($field['name'], $data[$field['name']] ?? null)" />
                                    @elseif ($field['type'] == 'input')
                                    <x-text-input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="text" mask="{{ $field['mask'] ?? null }}" class="mt-1 block w-full" :value="old($field['name'], $data[$field['name']] ?? null)" />
                                    @elseif ($field['type'] == 'select')
                                    <x-select id="{{ $field['name'] }}" name="{{ $field['name'] }}" :options="$field['options']" :multiple="$field['multiple'] ?? false" :value="old($field['name'], $data[$field['name']] ?? [])" />
                                    @elseif ($field['type'] == 'toggle')
                                    <x-toggle id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="mt-1 block w-full" :checked="old($field['name'], $data[$field['name']] ?? false)" />
                                    @endif
                                    <x-input-error class="mt-2" :messages="$errors->get($field['name'])" />
                                </div>
                                @endforeach
                            </div>
                            @endforeach

                            @if ($component['action'] ?? false ? true : false)
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
                            <x-table striped :menu="$menu ?? []" :columns="$columns ?? []" :rows="$items ?? []" table-text-link-label="Name">
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
                    </section>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
