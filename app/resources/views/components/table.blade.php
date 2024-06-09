@props([
'rows' => [],
'menu' => [],
'columns' => [],
'striped' => false,
'actionText' => 'Action',
])

<?php
$links = collect($rows)->all()['links'] ?? [];
$rows = collect($rows)->all()['data'] ?? [];
?>

<script>
    function app() {
        return {
            searchValue: '',

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
                this.submitForm();
            }, 500),

            clearSearchOnEsc(event) {
                if (event.key === 'Escape') {
                    this.searchValue = '';
                    this.updateUrl();
                    this.submitForm();
                }
            },

            updateFormAction() {
                const form = document.getElementById('searchForm');
                form.action = `${window.location.pathname}?${new URLSearchParams(window.location.search).toString()}`;
            },

            submitForm() {
                setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 0);
            },
        }
    }

    function toggleSelectAll(source) {
        checkboxes = document.querySelectorAll('.item-checkbox');
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

    let selectedCheckboxes = 0;

    function countSelectedCheckboxes() {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedCount = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked).length;
        selectedCheckboxes = selectedCount;
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

<div x-data="{
		columns: {{ collect($columns) }},
		rows: {{ collect($rows) }},
		isStriped: Boolean({{ $striped }})
	}" x-cloak wire:key="{{ md5(collect($rows)) }}">
    <div class="flex justify-between pb-4">
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
                                <form method="{{ $item['method'] ?? 'get' }}" action="{{ $item['url'] }}">
                                    @if ($item['method'] ?? false)
                                    @csrf
                                    @endif
                                    <li>
                                        <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-zinc-900 dark:text-zinc-200 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-zinc-200 dark:hover:bg-zinc-900 hover:scale-95 hover:text-blue-500 dark:hover:text-yellow-600" href="{{ $item['url'] }}" onclick="event.preventDefault();this.closest('form').submit();">
                                            @svg($item['icon'], 'size-6 text-zinc-900 dark:text-zinc-200')
                                            <span class="ml-4"> {{ $item['label'] }} </span>
                                        </a>
                                    </li>
                                </form>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div x-data="app" x-init="initializeSearchValue">
            <form id="searchForm" method="GET" action="">
                <x-text-input autofocus id="search" name="search" type="text" class="w-[300px]" :value="request()->search" x-model="searchValue" @input="debouncedUpdateUrl" @keydown.escape="clearSearchOnEsc" placeholder="{{ __('Search...') }}" />
            </form>
        </div>
    </div>
    <div class="overflow-x-auto rounded-lg shadow overflow-y-auto relative">
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
                        <th :class="`${column.columnClasses}`" class="bg-zinc-200 dark:bg-zinc-950 sticky top-0 border-b  border-zinc-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate" x-text="column.name"></th>
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
                        <td colspan="100%" class="text-center px-4 py-1 text-sm">
                            {{ __('No records found.') }}
                        </td>
                    </tr>
                    @endisset
                </template>

                <template x-for="(row, rowIndex) in rows" :key="'row-' +rowIndex">
                    <tr :class="{'bg-zinc-200/70 dark:bg-zinc-900/70': isStriped === true && ((rowIndex+1) % 2 === 0) }">
                        <td class="text-zinc-800 dark:text-zinc-200 px-6 py-3 border-t border-zinc-100 dark:border-zinc-900 whitespace-nowrap">
                            <input type="checkbox" class="item-checkbox" onclick="updateSelectAll()">
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
                                <x-gmdi-check-circle-o x-show="boolean && value == true" class="size-6 text-green-700" />
                                <x-gmdi-remove-circle-outline-o x-show="boolean && value == false" class="size-6 text-red-700" />
                            </td>
                        </template>

                        @isset($tableActions)
                        <td class="text-zinc-600 px-6 py-3 border-t border-zinc-100 dark:border-zinc-900 whitespace-nowrap">
                            {{ $tableActions }}
                        </td>
                        @endisset
                        @endisset
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <nav class="relative z-0 inline-flex gap-2 -space-x-px justify-center w-full py-4" aria-label="Pagination">
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
    </nav>
</div>
