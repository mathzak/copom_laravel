@props(['id', 'name', 'options', 'value' => '[]', 'placeholder' => ''])

@php
$options = json_encode($options);
$value = json_encode($value);
@endphp

<div x-data="{
        isOpen: false,
        selectedOptions: [],
        options: {{$options}},
        search: '',
        filteredOptions: {{$options}},
        selectAll: false,
        dropup: false,
        removeLastTag(event) {
            if (event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
                if (this.selectedOptions.length && !event.target.value.length) {
                    this.selectedOptions.pop();
                    this.updateHiddenInput();
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
                this.selectedOptions = Array.from(new Set(this.selectedOptions.concat(this.filteredOptions)));
            } else {
                this.selectedOptions = this.selectedOptions.filter(selected => !this.filteredOptions.some(option => option.id === selected.id));
            }
            this.updateHiddenInput();
        },
        updateSelectAllState() {
            this.selectAll = this.filteredOptions.length > 0 && this.filteredOptions.every(option => this.selectedOptions.some(selected => selected.id === option.id));
        },
        updateHiddenInput() {
            this.$refs.hiddenInput.value = JSON.stringify(this.selectedOptions);
        },
        toggleOption(option) {
            const index = this.selectedOptions.findIndex(selected => selected.id === option.id);
            if (index > -1) {
                this.selectedOptions.splice(index, 1);
            } else {
                this.selectedOptions.push(option);
            }
            this.updateHiddenInput();
            this.updateSelectAllState();
        },
        checkDropDirection() {
            this.$nextTick(() => {
                const dropdown = this.$refs.dropdown;
                const rect = dropdown.getBoundingClientRect();
                this.dropup = rect.bottom > window.innerHeight;
            });
        },
        initSelectedOptions() {
            const value = {{$value}};
            this.selectedOptions = this.options.filter(option => value.includes(option.id));
            this.updateHiddenInput();
            this.updateSelectAllState();
        }
    }" x-init="initSelectedOptions()" @click.away="isOpen = false" @keydown.window.backspace="removeLastTag" class="relative" @open-dropdown.window="checkDropDirection">
    <button type="button" @click="isOpen = !isOpen; checkDropDirection();" class="flex items-center justify-between w-full px-4 py-2 min-h-11 text-left border-2 border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
        <span x-show="!selectedOptions.length">{{ __($placeholder) }}</span>
        <div>
            <template x-for="(option, index) in selectedOptions" :key="index">
                <span class="inline-block bg-zinc-200 dark:bg-zinc-800 rounded-full px-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300 mr-2">
                    <span x-text="option.label"></span>
                    <button @click.prevent.stop="selectedOptions.splice(index, 1); updateHiddenInput(); updateSelectAllState();" class="ml-2 focus:outline-none">
                        @svg('gmdi-remove-circle-outline-o', 'size-4', ['style' => 'color:#FF2D20'])
                </span>
            </template>
        </div>
        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': isOpen, 'rotate-0': !isOpen}" class="inline size-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1 rotate-0">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="isOpen" x-transition class="absolute z-50 w-full bg-white dark:bg-zinc-900 border-zinc-300 dark:border-zinc-700 dark:text-zinc-300 border rounded shadow-lg max-h-60 overflow-y-auto" :class="{'bottom-12': dropup, 'mt-1': !dropup}" @click.away="isOpen = false" x-ref="dropdown">
        <input x-model="search" @input="filterOptions" type="text" class="w-full px-4 py-2 border-b border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:outline-none" placeholder="{{__('Search...')}}">

        <!-- Conditionally render the select all checkbox or no matches message -->
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
                <input type="checkbox" @change="toggleOption(option)" :checked="selectedOptions.some(selected => selected.id === option.id)" class="form-checkbox h-5 w-5 text-indigo-600">
                <span x-text="option.label" class="pl-3"></span>
            </label>
        </template>
    </div>
    <!-- Hidden input to store selected options as JSON -->
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" x-ref="hiddenInput" :value="JSON.stringify(selectedOptions)">
</div>
