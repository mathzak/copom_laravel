@props([
'rows' => [],
'columns' => [],
'striped' => false,
'actionText' => 'Action',
'tableTextLinkLabel' => 'Link',
])

<?php
$links = collect($rows)->all()['links'];
$rows = collect($rows)->all()['data'];
?>

<div x-data="{
		columns: {{ collect($columns) }},
		rows: {{ collect($rows) }},
		isStriped: Boolean({{ $striped }})
	}" x-cloak wire:key="{{ md5(collect($rows)) }}">
    <div class="overflow-x-auto rounded-lg shadow overflow-y-auto relative">
        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-gray-100/50 dark:bg-zinc-900/50 table-striped relative">
            <thead>
                <tr class="text-left">
                    @isset($tableColumns)
                    {{ $tableColumns }}
                    @else
                    @isset($tableTextLink)
                    <th class="bg-gray-200 dark:bg-zinc-950 sticky top-0 border-b border-gray-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate">
                        {{ $tableTextLinkLabel }}
                    </th>
                    @endisset

                    <template x-for="column in columns">
                        <th :class="`${column.columnClasses}`" class="bg-gray-200 dark:bg-zinc-950 sticky top-0 border-b  border-gray-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate" x-text="column.name"></th>
                    </template>

                    @isset($tableActions)
                    <th class="bg-gray-200 dark:bg-zinc-950 sticky top-0 border-b border-gray-100 dark:border-zinc-900 px-6 py-3 text-zinc-800 dark:text-zinc-200 font-bold tracking-wider uppercase text-xs truncate">{{ $actionText }}</th>
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
                    <tr :class="{'bg-gray-200/70 dark:bg-zinc-900/70': isStriped === true && ((rowIndex+1) % 2 === 0) }">
                        @isset($tableRows)
                        {{ ($tableRows) }}
                        @else
                        @isset($tableTextLink)
                        <td class="text-zinc-800 dark:text-zinc-200 px-6 py-3 border-t border-gray-100 dark:border-zinc-900 whitespace-nowrap">
                            {{ $tableTextLink }}
                        </td>
                        @endisset

                        <template x-for="(column, columnIndex) in columns" :key="'column-' + columnIndex">
                            <td :class="`${column.rowClasses}`" class="text-zinc-800 dark:text-zinc-200 px-6 py-3 border-t border-gray-100 dark:border-zinc-900 whitespace-nowrap">
                                <div x-text="`${row[column.field]}`" class="truncate"></div>
                            </td>
                        </template>

                        @isset($tableActions)
                        <td class="text-gray-600 px-6 py-3 border-t border-gray-100 dark:border-zinc-900 whitespace-nowrap">
                            {{ $tableActions }}
                        </td>
                        @endisset
                        @endisset
                    </tr>
                </template>
            </tbody>
        </table>

        <nav class="relative z-0 inline-flex gap-2 -space-x-px justify-center w-full pt-4" aria-label="Pagination">
            @foreach($links as $link)
            @if($link['active'] == true)
            <a href="{{ $link['url'] }}" class="relative inline-flex items-center px-4 py-2 text-sm font-light text-zinc-600 dark:text-zinc-500 hover:text-zinc-950 dark:hover:text-zinc-200 bg-gray-200 dark:bg-zinc-900 hover:bg-gray-300 dark:hover:bg-zinc-950 rounded-lg " aria-label="Go to page 2">
                {!! $link['label'] !!}
            </a>
            @else
            <span class="relative z-10 inline-flex items-center px-4 py-2 text-sm font-light text-zinc-400 dark:text-zinc-500 rounded-lg bg-gray-100 dark:bg-zinc-700" aria-current="page">
                {!! $link['label'] !!}
            </span>
            @endif
            @endforeach
        </nav>
    </div>
</div>
