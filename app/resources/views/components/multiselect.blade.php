@props(['options'])

@php
$options = json_encode($options);
@endphp

<div x-data="{ isOpen: false, selectedOptions: [], options: {{$options}}, search: '', filteredOptions: {{$options}} }" @click.away="isOpen = false" @keydown.window.backspace="removeLastTag" class="relative">
    <button @click="isOpen = !isOpen" class="flex items-center justify-between w-full px-4 h-11 text-left border-2 border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
        <span x-show="!selectedOptions.length">Select Options</span>
        <div>
            <template x-for="(option, index) in selectedOptions" :key="index">
                <span class="inline-block bg-zinc-200 dark:bg-zinc-800 rounded-full py-1 px-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300 mr-2 ">
                    <span x-text="option"></span>
                    <button @click.stop="selectedOptions.splice(index, 1)" class="ml-2 focus:outline-none">
                        <svg class="w-4 h-2 fill-current text-zinc-500 dark:text-zinc-400" viewBox="0 0 24 24">
                            <path fill="none" d="M0 0h24v24H0z"></path>
                            <path d="M18.3,5.71L12,12l6.3,6.29a1,1,0,0,1-1.41,1.41L10.59,13.41,4.29,19.71a1,1,0,0,1-1.41-1.41L9.17,12,2.88,5.71A1,1,0,1,1,4.29,4.29L10.59,10.59,16.88,4.29A1,1,0,1,1,18.3,5.71Z"></path>
                        </svg>
                    </button>
                </span>
            </template>
        </div>
        <svg class="w-4 h-4" viewBox="0 0 20 20" :class="{ 'transform rotate-180': isOpen }">
            <path fill="currentColor" d="M10 12.586L5.707 8.293a1 1 0 011.414-1.414L10 10.758l3.879-3.879a1 1 111.414 1.414l-4.293 4.293a1 1 01-1.414 0z"></path>
        </svg>
    </button>
    <div x-show="isOpen" class="absolute z-10 w-full mt-1 border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 border rounded shadow-lg max-h-60 overflow-y-auto">
        <input x-model="search" @input="filterOptions" type="text" class="w-full px-4 py-2 border-b border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:outline-none" placeholder="Search...">
        <template x-for="(option, index) in filteredOptions" :key="index">
            <label class="flex items-center px-4 py-2">
                <input type="checkbox" x-model="selectedOptions" :value="option" class="form-checkbox h-5 w-5 text-indigo-600">
                <span x-text="option" class="pl-3"></span>
            </label>
        </template>
    </div>
    <div class="mt-2">

    </div>
</div>

<script>
    function removeLastTag(event) {
        if (event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
            if (this.selectedOptions.length && !event.target.value.length) {
                this.selectedOptions.pop();
            }
        }
    }

    function filterOptions() {
        this.filteredOptions = this.options.filter(option => option.toLowerCase().includes(this.search.toLowerCase()));
    }
</script>
