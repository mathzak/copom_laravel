<section class="px-4 mx-auto max-w-7xl">
    <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
        @foreach ($items as $item)
        <a href="{{ $item['url'] }}" class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-800 dark:ring-zinc-800 dark:text-white dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                @svg($item['icon'], 'w-12 h-12', ['style' => 'color:#FF2D20'])
            </div>

            <div class="pt-3 sm:pt-5">
                <h2 class="text-xl font-semibold text-black dark:text-white">{{ $item['label'] }}</h2>
                <p class="mt-4 text-sm/relaxed">{{ $item['description'] }}</p>
            </div>

            <svg class="size-6 shrink-0 self-center stroke-[#FF2D20]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
            </svg>
        </a>
        @endforeach
    </div>
</section>
