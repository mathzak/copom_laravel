@props([
'rows' => [],
'columns' => [],
'striped' => false,
'actionText' => 'Action',
])

<?php
$links = collect($rows)->all()['links'];
$rows = collect($rows)->all()['data'];
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
            }
        }
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
            @isset($tableHeader)
            {{ $tableHeader }}
            @endisset
        </div>
        <div x-data="app" x-init="initializeSearchValue">
            <form id="searchForm" method="GET" action="">
                <x-text-input autofocus id="search" name="search" type="text" class="w-[300px]" :value="request()->search" x-model="searchValue" @input="debouncedUpdateUrl" @keydown.escape="clearSearchOnEsc" placeholder="{{ __('Search...') }}" />
            </form>
        </div>
    </div>
    <div class="overflow-x-auto rounded-lg shadow overflow-y-auto relative">
        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-zinc-100/50 dark:bg-zinc-900/50 table-striped relative">
            <thead>
                <tr class="text-left">
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
                                <x-gmdi-disabled-by-default-o x-show="boolean && value == false" class="size-6 text-red-700" />
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
