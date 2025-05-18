<x-inc-layout>
    <div class="container mx-auto px-4 py-8 min-h-lvh">
        <div class="flex flex-col w-11/12 lg:w-10/12 mx-auto">
            <!-- Profile Header Section -->
            <div class="bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md rounded-2xl border border-gray-200/50 dark:border-Sidebar_background_hover/30 p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Profile Image -->
                    <div class="relative w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image->path) }}" alt="{{ $user->username }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-primaryColor text-white text-3xl font-bold">
                                {{ strtoupper(substr($user->username, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="flex flex-col text-center md:text-right">
                        <h1 class="text-2xl md:text-3xl font-bold text-PrimaryBlack dark:text-primaryWhite">{{ $user->getDisplayNameAttribute() }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->username.'@' }}</p>

                        <!-- Stats Row -->
                        <div class="flex justify-center md:justify-start gap-6 mt-4">
                            <div class="text-center">
                                <span class="block text-lg font-bold text-PrimaryBlack dark:text-primaryWhite">{{ $user->totalElections() }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">نظرسنجی‌ها</span>
                            </div>
                            <a href="{{ route('users.followers', $user->username) }}" class="text-center">
                                <span class="block text-lg font-bold text-PrimaryBlack dark:text-primaryWhite" id="followers-count">{{ $followersCount }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">دنبال‌کنندگان</span>
                            </a>
                            <a href="{{ route('users.followings', $user->username) }}" class="text-center">
                                <span class="block text-lg font-bold text-PrimaryBlack dark:text-primaryWhite">{{ $followingsCount }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">دنبال‌شده‌ها</span>
                            </a>
                        </div>
                    </div>

                    <!-- Follow Button -->
                    <div class="md:mr-auto mt-4 md:mt-0" id="follow-button-container">
                        @auth
                            @if(Auth::id() !== $user->id)
                                <button
                                    id="follow-button-{{ $user->id }}"
                                    hx-post="{{ route('users.follow', $user->username) }}"
                                    hx-headers='{"x-csrf-token": "{{ csrf_token() }}"}'
                                    hx-trigger="click"
                                    hx-target="#follow-button-{{ $user->id }}"
                                    hx-swap="none"
                                    class="{{ $isFollowing ? 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200' : 'bg-primaryColor hover:bg-primaryColor/90 text-white' }} px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-2"
                                >
                                    <span class="flex items-center gap-2">
                                        @if($isFollowing)
                                            <i class="ri-user-unfollow-line"></i>
                                            لغو دنبال کردن
                                        @else
                                            <i class="ri-user-follow-line"></i>
                                            دنبال کردن
                                        @endif
                                    </span>
                                </button>

                                <script>
                                    document.body.addEventListener('htmx:afterRequest', function(event) {
                                        if (event.detail.elt.id === 'follow-button-{{ $user->id }}') {
                                            const response = JSON.parse(event.detail.xhr.responseText);
                                            const button = document.getElementById('follow-button-{{ $user->id }}');
                                            const followersCountEl = document.getElementById('followers-count');

                                            // Update button appearance
                                            if (response.isFollowing) {
                                                button.innerHTML = '<span class="flex items-center gap-2"><i class="ri-user-unfollow-line"></i>لغو دنبال کردن</span>';
                                                button.classList.remove('bg-primaryColor', 'hover:bg-primaryColor/90', 'text-white');
                                                button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-800', 'dark:text-gray-200');
                                            } else {
                                                button.innerHTML = '<span class="flex items-center gap-2"><i class="ri-user-follow-line"></i>دنبال کردن</span>';
                                                button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-800', 'dark:text-gray-200');
                                                button.classList.add('bg-primaryColor', 'hover:bg-primaryColor/90', 'text-white');
                                            }

                                            // Update followers count
                                            if (followersCountEl) {
                                                followersCountEl.textContent = response.followersCount;
                                            }
                                        }
                                    });
                                </script>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="bg-primaryColor hover:bg-primaryColor/90 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-2">
                                <i class="ri-user-follow-line"></i>
                                دنبال کردن
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Elections Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-PrimaryBlack dark:text-primaryWhite mb-4">نظرسنجی‌های {{ $user->username }}</h2>

                @if($elections->isEmpty())
                    <div class="flex flex-col items-center justify-center p-8 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <i class="ri-inbox-line text-5xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <h3 class="text-xl font-medium text-PrimaryBlack dark:text-primaryWhite">هیچ نظرسنجی عمومی یافت نشد</h3>
                        <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90 text-center">
                            این کاربر هنوز هیچ نظرسنجی عمومی ایجاد نکرده است.
                        </p>
                    </div>
                @else
                    <!-- Elections Grid -->
                    <div id="elections-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($elections as $election)
                            <div class="flex flex-col p-4 bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md hover:shadow-lg transition-all duration-300 rounded-2xl border border-gray-200/50 dark:border-Sidebar_background_hover/30">
                                <a href="{{ route('election.show', $election->id) }}" class="flex flex-col h-full">
                                    <div class="font-bold text-xl text-PrimaryBlack dark:text-primaryWhite">
                                        {{ $election->title }}
                                    </div>

                                    <div class="text-sm mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90 line-clamp-3">
                                        {{ $election->description }}
                                    </div>

                                    <!-- Options Preview -->
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

                                    <!-- Status -->
                                    <div class="mt-auto pt-4 flex justify-end items-center text-xs text-SecondaryBlack/80 dark:text-SecondaryWhite/80">
                                        <div class="flex items-center">
                                            <span class="{{ $election->is_open ? 'text-green-500' : 'text-red-500' }}">
                                                {{ $election->is_open ? 'باز' : 'بسته' }}
                                            </span>
                                            <i class="{{ $election->is_open ? 'ri-checkbox-circle-line mr-1 text-green-500' : 'ri-close-circle-line mr-1 text-red-500' }}"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
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
                @endif
            </div>
        </div>
    </div>
</x-inc-layout>
