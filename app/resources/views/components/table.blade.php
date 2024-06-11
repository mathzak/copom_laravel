@props([
'rows' => [],
'menu' => [],
'columns' => [],
'striped' => false,
'actionText' => 'Action',
])

<?php
$source = collect($rows)->all();
$links = $source['links'] ?? [];
$rows = $source['data'] ?? [];
?>

<script>
    function app() {
        return {
            searchValue: '',
            columns: @js($columns),
            rows: @js($rows),
            isStriped: @js($striped),

            initializeSearchValue() {
                const params = new URLSearchParams(window.location.search);
                this.searchValue = params.get('search') || '';
            },

            updateUrl() {
                const params = new URLSearchParams(window.location.search);
                if (this.searchValue) {
                    params.set('search', this.searchValue);
                } else {
                    params.delete('search');
                }

                window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                this.updateFormAction();
            },

            debouncedUpdateUrl: debounce(function() {
                this.updateUrl();
                this.submitSearchForm();
            }, 500),

            clearSearchOnEsc(event) {
                if (event.key === 'Escape') {
                    this.searchValue = '';
                    this.updateUrl();
                    this.submitSearchForm();
                }
            },

            updateFormAction() {
                const form = document.getElementById('searchForm');
                form.action = `${window.location.pathname}?${new URLSearchParams(window.location.search).toString()}`;
            },

            submitSearchForm() {
                setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 0);
            },

            formSubmit(item) {
                const attributes = JSON.parse(item);

                const menuForm = document.createElement('form');
                menuForm.method = 'post';
                menuForm.action = attributes.url;
                document.body.appendChild(menuForm);

                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                menuForm.appendChild(token);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = attributes.method;
                menuForm.appendChild(method);

                const itemCheckboxes = document.querySelectorAll('.item-checkbox');

                let checkboxesValues = []

                if (attributes.dataDeleted === true) {
                    checkboxesValues = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked && checkbox.getAttribute('data-deleted') === 'true');
                } else if (attributes.dataDeleted === false) {
                    checkboxesValues = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked && checkbox.getAttribute('data-deleted') === 'false');
                } else {
                    checkboxesValues = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked);
                }

                checkboxesValues.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'values[]';
                    input.value = checkbox.value;
                    menuForm.appendChild(input);
                });

                if (attributes.method != 'get') {
                    if (checkboxesValues.length > 0) {
                        window.dispatchEvent(new CustomEvent('open-modal', {
                            detail: 'confirm_action'
                        }));

                        document.getElementById('confirm-button').addEventListener('click', function() {
                            window.dispatchEvent(new CustomEvent('confirm-action', {
                                detail: true
                            }));
                        });

                        window.addEventListener('confirm-action', function(event) {
                            if (event.detail === true) {
                                window.dispatchEvent(new CustomEvent('close-modal', {
                                    detail: 'confirm_action'
                                }));

                                menuForm.submit();
                            }
                        });
                    } else {
                        window.dispatchEvent(new CustomEvent('open-modal', {
                            detail: 'no_items_selected'
                        }));
                    }
                } else {
                    menuForm.submit();
                }
            },
        }
    }

    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        countSelectedCheckboxes();
    }

    function updateSelectAll() {
        const selectAllCheckbox = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
        selectAllCheckbox.checked = allChecked;
        countSelectedCheckboxes();
    }

    function countSelectedCheckboxes() {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedDeletedCount = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked && checkbox.getAttribute('data-deleted') === 'true').length;
        const selectedNotDeletedCount = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked && checkbox.getAttribute('data-deleted') === 'false').length;

        document.querySelectorAll('.deletedCount').forEach(element => element.innerText = selectedDeletedCount);
        document.querySelectorAll('.notDeletedCount').forEach(element => element.innerText = selectedNotDeletedCount);
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(this, args);
            }, wait);
        };
    }
</script>

<div x-data="app()" x-cloak wire:key="{{ md5(collect($rows)) }}">
    <x-modal name="no_items_selected" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                {{ __('Warning') }}
            </h2>

            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('No items selected.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>

    <x-modal name="confirm_action" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                {{ __('Confirmation') }}
            </h2>

            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Do you confirm this action?') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>

                <x-primary-button id="confirm-button" class="ms-3">
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </div>
    </x-modal>

    <div class="flex justify-between">
        <div>
            <div class="flex flex-shrink-0 w-30">
                <div @click.away="open = false" class="relative inline-flex items-center w-full" x-data="{ open: false }">
                    <button @click="open = !open" class="inline-flex items-center justify-between w-full p-1 text-lg font-medium text-center text-zinc-800 dark:text-zinc-200 transition duration-500 ease-in-out transform rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-800 focus:outline-none">
                        <p class="text-xs text-zinc-800 dark:text-zinc-200 group-hover:text-blue-500 dark:group-hover:text-yellow-600">
                            @svg('gmdi-more-vert-o', 'size-8')
                        </p>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute top-0 z-50 w-full mx-auto mt-2 origin-top-right rounded-xl min-w-max" style="display: none;">
                        <div class="px-2 py-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                            <ul>
                                @foreach ($menu as $item)
                                @if($item['visible'] ?? true === true)
                                <li>
                                    <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-zinc-900 dark:text-zinc-200 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-zinc-200 dark:hover:bg-zinc-900 hover:scale-95 hover:text-blue-500 dark:hover:text-yellow-600" href="#" onclick="event.preventDefault(); app().formSubmit('{{ json_encode($item) }}');">
                                        @svg($item['icon'], 'size-6 text-zinc-900 dark:text-zinc-200')
                                        <span class="ml-4 mr-2"> {{ $item['label'] }} </span>
                                        @if($item['dataDeleted'] === true)
                                        <span class="text-xs text-white deletedCount ml-auto bg-blue-500 rounded-full px-2">0</span>
                                        @elseif($item['dataDeleted'] === false)
                                        <span class="text-xs text-white notDeletedCount ml-auto bg-blue-500 rounded-full px-2">0</span>
                                        @endif
                                    </a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div x-init="initializeSearchValue">
            <form id="searchForm" method="GET" action="">
                <x-text-input autofocus id="search" name="search" type="text" class="w-[300px]" :value="request()->search" x-model="searchValue" @input="debouncedUpdateUrl" @keydown.escape="clearSearchOnEsc" placeholder="{{ __('Search...') }}" />
            </form>
        </div>
    </div>
    <div class="overflow-x-auto rounded-lg shadow overflow-y-auto relative">
        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-zinc-100/50 dark:bg-zinc-900/50 table-striped relative">
            <thead>
                <tr class="text-left">
                    <th class="bg-zinc-200 dark:bg-zinc-950 sticky top-0 border-b border-zinc-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate">
                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)">
                    </th>
                    @isset($tableColumns)
                    {{ __($tableColumns) }}
                    @else
                    @isset($tableTextLink)
                    <th class="bg-zinc-200 dark:bg-zinc-950 sticky top-0 border-b border-zinc-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate">
                        {{ __($tableTextLinkLabel) }}
                    </th>
                    @endisset

                    <template x-for="column in columns">
                        <th :class="`${column.columnClasses}`" class="bg-zinc-200 dark:bg-zinc-950 sticky top-0 border-b border-zinc-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate" x-text="column.name"></th>
                    </template>

                    @isset($tableActions)
                    <th class="bg-zinc-200 dark:bg-zinc-950 sticky top-0 border-b border-zinc-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate">
                        {{ __($actionText) }}
                    </th>
                    @endisset
                    @endisset
                </tr>
            </thead>
            <tbody>
                <template x-if="rows.length === 0">
                    @isset($empty)
                    {{ $empty }}
                    @else
                    <tr>
                        <td colspan="100%" class="text-center text-zinc-800 dark:text-zinc-200 px-4 py-1 text-sm">
                            {{ __('No records found.') }}
                        </td>
                    </tr>
                    @endisset
                </template>

                <template x-for="(row, rowIndex) in rows" :key="'row-' + rowIndex">
                    <tr :class="{'bg-zinc-200/70 dark:bg-zinc-900/70': isStriped === true && ((rowIndex + 1) % 2 === 0), 'line-through italic opacity-50': row.deleted_at ? true : false }">
                        <td class="text-zinc-800 dark:text-zinc-200 px-6 py-3 border-t border-zinc-100 dark:border-zinc-900 whitespace-nowrap">
                            <input type="checkbox" class="item-checkbox" :value="row.id" :data-deleted="row.deleted_at ? 'true' : 'false'" onclick="updateSelectAll(); countSelectedCheckboxes();">
                        </td>
                        @isset($tableRows)
                        {{ ($tableRows) }}
                        @else
                        @isset($tableTextLink)
                        <td class="text-zinc-800 dark:text-zinc-200 px-6 py-3 border-t border-zinc-100 dark:border-zinc-900 whitespace-nowrap">
                            {{ $tableTextLink }}
                        </td>
                        @endisset

                        <template x-for="(column, columnIndex) in columns" :key="'column-' + columnIndex">
                            <td x-data="{boolean: column.boolean, value: row[column.field]}" :class="`${column.rowClasses}`" class="text-zinc-800 dark:text-zinc-200 px-6 py-3 border-t border-zinc-100 dark:border-zinc-900 whitespace-nowrap">
                                <div x-show="!boolean" x-text="`${row[column.field]}`" class="truncate"></div>
                                <x-gmdi-check-o x-show="boolean && value == true" class="size-6 text-green-700" />
                                <x-gmdi-clear-o x-show="boolean && value == false" class="size-6 text-red-700" />
                            </td>
                        </template>

                        @isset($tableActions)
                        <td class="text-zinc-600 px-6 py-3 border-t border-zinc-100 dark:border-zinc-900 whitespace-nowrap">
                            <div x-data="{deleted: row.deleted_at ? true : false}">
                                <x-gmdi-block-o x-show="deleted == true" class="size-6 text-red-700" />
                                <div x-show="deleted == false">
                                    {{ $tableActions }}
                                </div>
                            </div>
                            @endisset
                            @endisset
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <nav class="relative z-0 inline-flex gap-2 -space-x-px justify-center w-full py-4" aria-label="Pagination">
        @if($source['prev_page_url'] && !$links)
        <a href="{{ $source['prev_page_url'] }}" class="relative inline-flex items-center px-4 py-2 text-sm font-light text-zinc-600 dark:text-zinc-500 hover:text-zinc-950 dark:hover:text-zinc-200 bg-zinc-200 dark:bg-zinc-900 hover:bg-zinc-300 dark:hover:bg-zinc-950 rounded-lg " aria-label="Go to page 2">
            {!! __('pagination.previous') !!}
        </a>
        @endif

        @foreach($links as $link)
        @if($link['url'] && $link['active'] === false)
        <a href="{{ $link['url'] }}" class="relative inline-flex items-center px-4 py-2 text-sm font-light text-zinc-600 dark:text-zinc-500 hover:text-zinc-950 dark:hover:text-zinc-200 bg-zinc-200 dark:bg-zinc-900 hover:bg-zinc-300 dark:hover:bg-zinc-950 rounded-lg " aria-label="Go to page 2">
            {!! $link['label'] !!}
        </a>
        @endif
        @if($link['active'] === true)
        <span class="relative z-10 inline-flex items-center px-4 py-2 text-sm font-light text-zinc-600/60 dark:text-zinc-500/60 bg-zinc-200/60 dark:bg-zinc-900/60 rounded-lg" aria-current="page">
            {!! $link['label'] !!}
        </span>
        @endif
        @endforeach

        @if($source['next_page_url'] && !$links)
        <a href="{{ $source['next_page_url'] }}" class="relative inline-flex items-center px-4 py-2 text-sm font-light text-zinc-600 dark:text-zinc-500 hover:text-zinc-950 dark:hover:text-zinc-200 bg-zinc-200 dark:bg-zinc-900 hover:bg-zinc-300 dark:hover:bg-zinc-950 rounded-lg " aria-label="Go to page 2">
            {!! __('pagination.next') !!}
        </a>
        @endif
    </nav>
</div>
