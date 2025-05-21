<x-inc-layout>
    <div class="container mx-auto px-4 py-8 min-h-lvh">
        <div class="flex flex-col w-11/12 lg:w-10/12 mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-PrimaryBlack dark:text-primaryWhite">انتخابات عمومی</h1>
                <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90">مشاهده و مشارکت در انتخابات عمومی</p>
            </div>

            <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="relative" x-data="{ focused: false }">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none z-10">
                        </div>
                        <form
                            hx-get="{{ route('elections.feed') }}"
                            hx-target="#elections-grid"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup[target.value.length > 2] delay:500ms from:input"
                            class="w-full"
                        >
                            <input
                                type="text"
                                name="search"
                                placeholder="جست و جو"
                                class="block w-full py-1.5 pr-12 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 rtl:pl-10 dark:bg-PrimaryBlack dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 text-right"
                                @focus="focused = true"
                                @blur="focused = false"
                            >
                        </form>
                    </div>

                    <select
                        name="status"
                        onchange="window.location = '{{ route('elections.feed') }}?status=' + this.value"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-200 rounded-lg dark:bg-PrimaryBlack dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none"
                    >
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>همه وضعیت‌ها</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>باز</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>بسته</option>
                    </select>
                </div>
            </div>

            <div id="elections-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($elections as $election)
                    <div class="flex flex-col p-4 bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md hover:shadow-lg transition-all duration-300 rounded-2xl border border-gray-200/50 dark:border-Sidebar_background_hover/30">
                        <a href="{{ route('election.show', $election->id) }}" class="flex flex-col h-full">
                            <div class="font-bold text-xl text-PrimaryBlack dark:text-primaryWhite">
                                {{ $election->title }}
                            </div>

                            <div class="text-sm mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90 line-clamp-3">
                                {{ $election->description }}
                            </div>



                            <div class="mt-4 flex flex-col gap-2">
                                <div class="text-sm font-medium text-PrimaryBlack dark:text-primaryWhite">گزینه‌ها:</div>
                                @foreach($election->options->take(2) as $option)
                                    <div class="flex justify-between items-center p-2 bg-white/30 dark:bg-zinc-800/30 rounded-lg">
                                        <span class="text-sm text-SecondaryBlack dark:text-SecondaryWhite truncate">{{ $option->title }}</span>
                                        <span class="text-xs bg-white/70 dark:bg-zinc-700/70 px-2 py-1 rounded-full">
                                            {{ $option->votes->sum('vote') }} رای
                                        </span>
                                    </div>
                                @endforeach

                                @if($election->options->count() > 2)
                                    <div class="text-xs text-center text-primaryColor dark:text-primaryColor/90 mt-1">
                                        + {{ $election->options->count() - 2 }} گزینه دیگر
                                    </div>
                                @endif
                                <div class="flex flex-row justify-between mt-4 p-3 text-PrimaryBlack dark:text-primaryWhite bg-white/50 dark:bg-zinc-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                                    <div class="flex flex-col items-center">
                                        <i class="ri-chat-1-fill text-lg"></i>
                                        <div class="text-xs font-medium mt-1">
                                            {{ $election->comments()->count() }}
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center">
                                        <i class="ri-user-3-fill text-lg"></i>
                                        <div class="text-xs font-medium mt-1">
                                            {{ $election->userCount() }}
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center">
                                        <i class="ri-chat-poll-fill text-lg"></i>
                                        <div class="text-xs font-medium mt-1">
                                            {{ $election->getTotalVotes() }}
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center">
                                        <i class="ri-calendar-event-fill text-lg"></i>
                                        <div class="text-xs font-medium mt-1">
                                            {{ verta($election->created_at)->format('Y/m/d') }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-auto pt-4 flex justify-between items-center text-xs text-SecondaryBlack/80 dark:text-SecondaryWhite/80">
                                <div class="flex items-center">
                                    <i class="ri-user-line ml-1"></i>
                                    <span>{{ $election->user->username ?? 'کاربر' }}</span>
                                </div>

                                <div class="flex items-center">
                                    <span class="{{ $election->is_open ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $election->is_open ? 'باز' : 'بسته' }}
                                    </span>
                                    <i class="{{ $election->is_open ? 'ri-checkbox-circle-line mr-1 text-green-500' : 'ri-close-circle-line mr-1 text-red-500' }}"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center p-8 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <i class="ri-inbox-line text-5xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <h3 class="text-xl font-medium text-PrimaryBlack dark:text-primaryWhite">هیچ انتخاباتی یافت نشد</h3>
                        <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90 text-center">
                            در حال حاضر هیچ انتخابات عمومی وجود ندارد یا با معیارهای جستجوی شما مطابقت ندارد.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    صفحه <span class="font-medium text-gray-700 dark:text-gray-100">{{ $elections->currentPage() }} از {{ $elections->lastPage() }}</span>
                </div>

                <div class="flex items-center gap-x-4">
                    <a href="{{ $elections->previousPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $elections->onFirstPage()?"pointer-events-none opacity-40":""}}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>

                        <span>
                        صفحه قبل
                    </span>
                    </a>

                    <a href="{{ $elections->nextPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $elections->onLastPage()?"pointer-events-none opacity-40":""}}">
                    <span>
                        صفحه بعد
                    </span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-inc-layout>
