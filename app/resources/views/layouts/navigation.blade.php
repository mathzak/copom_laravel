<div class="justify-center w-full mx-auto bg-gray-100 dark:bg-zinc-900 border-b border-zinc-100 dark:border-zinc-700">
    <div x-data="{ open: false }" class="flex flex-col w-full px-4 py-2 mx-auto md:items-center md:justify-between md:flex-row max-w-7xl">
        <div class="flex flex-row items-center justify-between text-zinc-800 dark:text-zinc-200 ">
            <a class="inline-flex items-center gap-3 text-xl font-bold tracking-tight text-zinc-800 dark:text-zinc-200" href="/">
                <x-application-logo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200" />
                <span>SisCOPOM</span>
            </a>
            <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <nav :class="{'flex': open, 'hidden': !open}" class="flex-col items-center flex-grow gap-3 text-sm font-medium text-zinc-800 dark:text-zinc-200 px-0 md:flex md:justify-center md:flex-row md:ml-4 p-0 md:mt-0 hidden">
            <div class="relative lg:mx-auto" x-data="{ appsMenu: false }" x-on:click.away="appsMenu = false">
                <div class="relative">
                    <nav class="relative flex items-center justify-around w-full sm:h-10">
                        <div class="flex items-center justify-between flex-1">
                            <a class="flex flex-row items-center w-full pr-4 py-2 mt-2 text-sm text-left text-zinc-800 dark:text-zinc-200 md:w-auto md:inline md:mt-0 hover:text-blue-600 dark:hover:text-yellow-600 focus:outline-none focus:shadow-outline" href="{{ route('dashboard') }}">
                                {{ __('Dashboard') }}
                            </a>
                            <div class="flex items-center -mr-2" x-on:click="appsMenu = !appsMenu">
                                <button type="button" class="flex flex-row items-center w-full pr-4 py-2 mt-2 text-sm text-left text-zinc-800 dark:text-zinc-200 md:w-auto md:inline md:mt-0 hover:text-blue-600 dark:hover:text-yellow-600 focus:outline-none focus:shadow-outline" id="main-menu" aria-label="Main menu" aria-haspopup="true">
                                    <span> {{ __('Apps') }} </span>
                                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline size-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1 rotate-0">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center -mr-2" x-on:click="appsMenu = !appsMenu">
                                <button type="button" class="flex flex-row items-center w-full pr-4 py-2 mt-2 text-sm text-left text-zinc-800 dark:text-zinc-200 md:w-auto md:inline md:mt-0 hover:text-blue-600 dark:hover:text-yellow-600 focus:outline-none focus:shadow-outline" id="main-menu" aria-label="Main menu" aria-haspopup="true">
                                    <span> {{ __('Reports') }} </span>
                                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline size-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1 rotate-0">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <a class="flex flex-row items-center w-full pr-4 py-2 mt-2 text-sm text-left text-zinc-800 dark:text-zinc-200 md:w-auto md:inline md:mt-0 hover:text-blue-600 dark:hover:text-yellow-600 focus:outline-none focus:shadow-outline" href="{{ route('help') }}">
                                {{ __('Help') }}
                            </a>
                        </div>
                    </nav>
                </div>
                <div x-on:click="appsMenu = false" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" :class="{'translate-y-0 shadow-md duration-150': appsMenu, '-translate-y-full': ! appsMenu}" class="fixed inset-0 top-0 z-40 h-screen mx-auto overflow-y-auto transition origin-top transform -translate-y-full">
                    <div class="relative overflow-hidden bg-gray-100 dark:bg-zinc-800 shadow-xl lg:bg-transparent" role="menu" aria-orientation="vertical" aria-labelledby="main-menu">
                        <div class="bg-gray-100 dark:bg-zinc-800 border-zinc-100 dark:border-zinc-700 border">
                            <div class="grid mx-auto gap-y-2 sm:gap-4 px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-12 xl:py-16 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach (app('menu')->make('app') as $item)
                                <a href="{{ route($item['route']) }}">
                                    <div class="p-3 duration-200 rounded-xl flex items-start group hover:bg-gray-200 dark:hover:bg-zinc-900">
                                        <div class="flex-shrink-0 mb-0 mr-4">
                                            <div class="p-2 overflow-hidden border border-zinc-100 dark:border-zinc-700 rounded-3xl">
                                                @svg($item['icon'], 'object-cover h-full shadow-2xl size-16 lg:size-24 rounded-2xl aspect-square', ['style' => 'color:#FF2D20'])
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mt-0 text-base font-medium text-zinc-800 dark:text-zinc-200">
                                                {{ $item['label'] }}
                                            </p>
                                            <p class="mt-2 text-sm font-medium text-zinc-400 dark:text-zinc-500 text-pretty">
                                                {{ $item['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-shrink-0">
                <div @click.away="open = false" class="relative inline-flex items-center w-full" x-data="{ open: false }">
                    <button @click="open = !open" class="inline-flex items-center justify-between w-full p-1 text-lg font-medium text-center text-zinc-800 dark:text-zinc-200 transition duration-500 ease-in-out transform rounded-xl hover:bg-gray-200 dark:hover:bg-zinc-800 focus:outline-none">
                        <span>
                            <span class="flex-shrink-0 block group">
                                <div class="flex items-center">
                                    <div>
                                        <img class="inline-block object-cover rounded-full h-9 w-9" src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80" alt="">
                                    </div>
                                    <div class="ml-3 text-left">
                                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200 group-hover:text-blue-500 dark:group-hover:text-yellow-600">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs text-zinc-800 dark:text-zinc-200 group-hover:text-blue-500 dark:group-hover:text-yellow-600">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </span>
                        </span>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" class="inline size-5 ml-4 text-zinc-800 dark:text-zinc-200 transition-transform duration-200 transform rotate-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute top-0 z-50 w-full mx-auto mt-2 origin-top-right rounded-xl" style="display: none;">
                        <div class="px-2 py-2 bg-gray-200 dark:bg-zinc-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                            <ul>
                                <li>
                                    <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-zinc-900 dark:text-zinc-200 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-gray-100 dark:hover:bg-zinc-900 hover:scale-95 hover:text-blue-500 dark:hover:text-yellow-600" href="{{ route('profile.edit') }}">
                                        @svg('gmdi-account-circle-o', 'size-6 text-zinc-900 dark:text-zinc-200')
                                        <span class="ml-2"> {{ __('Profile') }} </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-zinc-900 dark:text-zinc-200 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-gray-100 dark:hover:bg-zinc-900 hover:scale-95 hover:text-blue-500 dark:hover:text-yellow-600" href="{{ route('profile.edit') }}">
                                        @svg('gmdi-message-o', 'size-6 text-zinc-900 dark:text-zinc-200')
                                        <span class="ml-2"> {{ __('Messages') }} </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-zinc-900 dark:text-zinc-200 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-gray-100 dark:hover:bg-zinc-900 hover:scale-95 hover:text-blue-500 dark:hover:text-yellow-600" href="{{ route('profile.edit') }}">
                                        @svg('gmdi-calendar-month-o', 'size-6 text-zinc-900 dark:text-zinc-200')
                                        <span class="ml-2"> {{ __('Schedule') }} </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-zinc-900 dark:text-zinc-200 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-gray-100 dark:hover:bg-zinc-900 hover:scale-95 hover:text-blue-500 dark:hover:text-yellow-600" href="{{ route('profile.edit') }}">
                                        @svg('gmdi-playlist-add-check-o', 'size-6 text-zinc-900 dark:text-zinc-200')
                                        <span class="ml-2"> {{ __('Requirements') }} </span>
                                    </a>
                                </li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <li>
                                        <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-zinc-900 dark:text-zinc-200 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-gray-100 dark:hover:bg-zinc-900 hover:scale-95 hover:text-blue-500 dark:hover:text-yellow-600" href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();">
                                            @svg('gmdi-logout-o', 'size-6 text-zinc-900 dark:text-zinc-200')
                                            <span class="ml-4"> {{ __('Log Out') }} </span>
                                        </a>
                                    </li>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
