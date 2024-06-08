@props(['options', 'placeholder' => ''])

@php
$options = json_encode($options);
@endphp

<div x-data="{
        isOpen: false,
        selectedOptions: [],
        options: {{$options}},
        search: '',
        filteredOptions: {{$options}},
        selectAll: false,
        removeLastTag(event) {
            if (event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
                if (this.selectedOptions.length && !event.target.value.length) {
                    this.selectedOptions.pop();
                    this.updateSelectAllState();
                }
            }
        },
        filterOptions() {
            this.filteredOptions = this.options.filter(option => option.label.toLowerCase().includes(this.search.toLowerCase()));
            this.updateSelectAllState();
        },
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedOptions = Array.from(new Set(this.selectedOptions.concat(this.filteredOptions.map(option => option.id))));
            } else {
                this.selectedOptions = this.selectedOptions.filter(id => !this.filteredOptions.some(option => option.id === id));
            }
        },
        updateSelectAllState() {
            this.selectAll = this.filteredOptions.length > 0 && this.filteredOptions.every(option => this.selectedOptions.includes(option.id));
        }
    }" @click.away="isOpen = false" @keydown.window.backspace="removeLastTag" class="relative">
    <button type="button" @click="isOpen = !isOpen" class="flex items-center justify-between w-full px-4 py-2 min-h-11 text-left border-2 border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
        <span x-show="!selectedOptions.length">{{ __($placeholder) }}</span>
        <div>
            <template x-for="(option, index) in selectedOptions" :key="index">
                <span class="inline-block bg-zinc-200 dark:bg-zinc-800 rounded-full px-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300 mr-2">
                    <span x-text="options.find(o => o.id === option)?.label"></span>
                    <button @click.stop="selectedOptions.splice(index, 1); updateSelectAllState()" class="ml-2 focus:outline-none">
                        <svg class="w-4 h-2 fill-current text-zinc-500 dark:text-zinc-400" viewBox="0 0 24 24">
                            <path fill="none" d="M0 0h24v24H0z"></path>
                            <path d="M18.3,5.71L12,12l6.3,6.29a1,1,0,0,1-1.41,1.41L10.59,13.41,4.29,19.71a1,1,0,0,1-1.41-1.41L9.17,12,2.88,5.71A1,1,1,0,1,1,4.29,4.29L10.59,10.59,16.88,4.29A1,1,1,0,1,1,18.3,5.71Z"></path>
                        </svg>
                    </button>
                </span>
            </template>
        </div>
        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': isOpen, 'rotate-0': !isOpen}" class="inline size-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1 rotate-0">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="isOpen" x-transition class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-900 border-zinc-300 dark:border-zinc-700 dark:text-zinc-300 border rounded shadow-lg max-h-60 overflow-y-auto" @click.away="isOpen = false">
        <input x-model="search" @input="filterOptions" type="text" class="w-full px-4 py-2 border-b border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:outline-none" placeholder="{{__('Search...')}}">

        <template x-if="filteredOptions.length">
            <label class="flex items-center px-4 py-2">
                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll" class="form-checkbox h-5 w-5 text-indigo-600">
                <span class="pl-3"></span>
            </label>
        </template>

        <template x-if="!filteredOptions.length">
            <div class="px-4 py-2 text-zinc-700 dark:text-zinc-300">{{ __('No records found.') }}</div>
        </template>

        <template x-for="(option, index) in filteredOptions" :key="option.id">
            <label class="flex items-center px-4 py-2">
                <input type="checkbox" x-model="selectedOptions" @change="updateSelectAllState" :value="option.id" class="form-checkbox h-5 w-5 text-indigo-600">
                <span x-text="option.label" class="pl-3"></span>
            </label>
        </template>
    </div>
</div>
