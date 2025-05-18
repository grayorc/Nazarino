<x-inc-layout>
    <div class="container mx-auto px-4 py-8 min-h-lvh">
        <div class="flex flex-col w-11/12 lg:w-10/12 mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('users.profile', $user->username) }}" class="text-gray-500 dark:text-gray-400 hover:text-primaryColor dark:hover:text-primaryColor">
                        <i class="ri-arrow-right-line text-xl"></i>
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-PrimaryBlack dark:text-primaryWhite">دنبال‌کنندگان {{ $user->username }}</h1>
                </div>
                <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90">افرادی که {{ $user->username }} را دنبال می‌کنند</p>
            </div>

            <!-- Followers List -->
            @if($followers->isEmpty())
                <div class="flex flex-col items-center justify-center p-8 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <i class="ri-user-follow-line text-5xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <h3 class="text-xl font-medium text-PrimaryBlack dark:text-primaryWhite">هیچ دنبال‌کننده‌ای یافت نشد</h3>
                    <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90 text-center">
                        این کاربر هنوز دنبال‌کننده‌ای ندارد.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($followers as $follower)
                        <div class="bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md rounded-xl border border-gray-200/50 dark:border-Sidebar_background_hover/30 p-4">
                            <div class="flex items-center gap-4">
                                <!-- User Avatar -->
                                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white dark:border-gray-700">
                                    @if($follower->image)
                                        <img src="{{ asset('storage/' . $follower->image->path) }}" alt="{{ $follower->username }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-primaryColor text-white text-xl font-bold">
                                            {{ strtoupper(substr($follower->username, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- User Info -->
                                <div class="flex-1">
                                    <a href="{{ route('users.profile', $follower->username) }}" class="font-bold text-PrimaryBlack dark:text-primaryWhite hover:text-primaryColor dark:hover:text-primaryColor/90">
                                        {{ $follower->getDisplayNameAttribute() }}
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $follower->username.'@' }}</p>
                                </div>

                                <!-- Follow Button -->
                                <div id="follow-button-container-{{ $follower->id }}">
                                    @php
                                        $isFollowing = Auth::check() ?
                                            \App\Models\Follower::where('user_id', $follower->id)
                                                ->where('follower_id', Auth::id())
                                                ->exists() : false;
                                        $followersCount = $follower->countFollowers();
                                    @endphp

                                    @auth
                                        @if(Auth::id() !== $follower->id)
                                            <button
                                                id="follow-button-{{ $follower->id }}"
                                                hx-post="{{ route('users.follow', $follower->username) }}"
                                                hx-headers='{"x-csrf-token": "{{ csrf_token() }}"}'
                                                hx-trigger="click"
                                                hx-target="#follow-button-{{ $follower->id }}"
                                                hx-swap="none"
                                                class="{{ $isFollowing ? 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200' : 'bg-primaryColor hover:bg-primaryColor/90 text-white' }} px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm"
                                            >
                                                <span class="flex items-center gap-2">
                                                    @if($isFollowing)
                                                        <i class="ri-user-unfollow-line"></i>
                                                        لغو دنبال
                                                    @else
                                                        <i class="ri-user-follow-line"></i>
                                                        دنبال کردن
                                                    @endif
                                                </span>
                                            </button>

                                            <script>
                                                document.body.addEventListener('htmx:afterRequest', function(event) {
                                                    if (event.detail.elt.id === 'follow-button-{{ $follower->id }}') {
                                                        const response = JSON.parse(event.detail.xhr.responseText);
                                                        const button = document.getElementById('follow-button-{{ $follower->id }}');

                                                        // Update button appearance
                                                        if (response.isFollowing) {
                                                            button.innerHTML = '<span class="flex items-center gap-2"><i class="ri-user-unfollow-line"></i>لغو دنبال</span>';
                                                            button.classList.remove('bg-primaryColor', 'hover:bg-primaryColor/90', 'text-white');
                                                            button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-800', 'dark:text-gray-200');
                                                        } else {
                                                            button.innerHTML = '<span class="flex items-center gap-2"><i class="ri-user-follow-line"></i>دنبال کردن</span>';
                                                            button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-800', 'dark:text-gray-200');
                                                            button.classList.add('bg-primaryColor', 'hover:bg-primaryColor/90', 'text-white');
                                                        }
                                                    }
                                                });
                                            </script>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="bg-primaryColor hover:bg-primaryColor/90 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                                            <i class="ri-user-follow-line"></i>
                                            دنبال کردن
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        صفحه <span class="font-medium text-gray-700 dark:text-gray-100">{{ $followers->currentPage() }} از {{ $followers->lastPage() }}</span>
                    </div>

                    <div class="flex items-center gap-x-4">
                        <a href="{{ $followers->previousPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $followers->onFirstPage()?"pointer-events-none opacity-40":""}}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                            </svg>

                            <span>
                            صفحه قبل
                        </span>
                        </a>

                        <a href="{{ $followers->nextPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $followers->onLastPage()?"pointer-events-none opacity-40":""}}">
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
</x-inc-layout>
