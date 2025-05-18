<x-min-layout>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack pt-16">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1 pb-32 dark:bg-PrimaryBlack">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-PrimaryBlack dark:text-primaryWhite">اعلان‌ها</h1>
                        <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90">مدیریت اعلان‌های دریافتی</p>
                    </div>
                    <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="flex">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primaryColor dark:hover:text-primaryColor flex items-center gap-1">
                            <i class="ri-check-double-line text-lg"></i>
                            خواندن همه
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notifications Tabs -->
            <div
                x-data="{
                    tabSelected: 'all',
                    tabId: $id('tabs'),
                    tabButtonClicked(tabButton, tabName){
                        this.tabSelected = tabName;
                        this.tabRepositionMarker(tabButton);
                    },
                    tabRepositionMarker(tabButton){
                        this.$refs.tabMarker.style.width = tabButton.offsetWidth + 'px';
                        this.$refs.tabMarker.style.height = '2px';
                        this.$refs.tabMarker.style.left = tabButton.offsetLeft + 'px';
                        this.$refs.tabMarker.style.bottom = '0px';
                    }
                }"
                x-init="tabRepositionMarker($refs.tabButtons.querySelector('[data-tab=all]'));"
                class="bg-white dark:bg-PrimaryBlack rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700"
            >
                <div class="border-b border-gray-200 dark:border-gray-700 relative">
                    <nav x-ref="tabButtons" class="flex flex-wrap" aria-label="Tabs">
                        <button
                            data-tab="all"
                            @click="tabButtonClicked($el, 'all')"
                            :class="{'text-primaryColor font-bold': tabSelected === 'all', 'text-gray-500 dark:text-gray-400': tabSelected !== 'all'}"
                            class="px-4 py-4 text-center font-medium text-sm w-1/3 transition-colors duration-200 relative z-10"
                        >
                            <i class="ri-notification-3-line ml-1"></i>همه
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-gray-100 bg-gray-500 rounded-full mr-2">{{ $notifications->total() }}</span>
                        </button>
                        <button
                            data-tab="unread"
                            @click="tabButtonClicked($el, 'unread')"
                            :class="{'text-primaryColor font-bold': tabSelected === 'unread', 'text-gray-500 dark:text-gray-400': tabSelected !== 'unread'}"
                            class="px-4 py-4 text-center font-medium text-sm w-1/3 transition-colors duration-200 relative z-10"
                        >
                            <i class="ri-mail-unread-line ml-1"></i>خوانده نشده
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full mr-2">{{ auth()->user()->unreadNotifications->count() }}</span>
                        </button>
                        <button
                            data-tab="read"
                            @click="tabButtonClicked($el, 'read')"
                            :class="{'text-primaryColor font-bold': tabSelected === 'read', 'text-gray-500 dark:text-gray-400': tabSelected !== 'read'}"
                            class="px-4 py-4 text-center font-medium text-sm w-1/3 transition-colors duration-200 relative z-10"
                        >
                            <i class="ri-mail-check-line ml-1"></i>خوانده شده
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-gray-100 bg-green-600 rounded-full mr-2">{{ $notifications->total() - auth()->user()->unreadNotifications->count() }}</span>
                        </button>
                        <div x-ref="tabMarker" class="absolute bg-primaryColor duration-300 ease-out transition-all" x-cloak></div>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-0">
                    <!-- All Notifications Tab -->
                    <div x-show="tabSelected === 'all'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        @if($notifications->isEmpty())
                    <div class="flex flex-col items-center justify-center p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                            <i class="ri-notification-3-line text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-PrimaryBlack dark:text-primaryWhite mb-2">هیچ اعلانی وجود ندارد</h3>
                        <p class="text-gray-500 dark:text-gray-400">در حال حاضر هیچ اعلانی برای نمایش وجود ندارد.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            <div class="p-4 md:p-6 {{ $notification->read_at ? 'bg-white dark:bg-PrimaryBlack' : 'bg-blue-50/30 dark:bg-blue-900/10' }} transition-all duration-300">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        @if(isset($notification->data['election_id']))
                                            <div class="w-10 h-10 rounded-full bg-primaryColor/10 dark:bg-primaryColor/20 flex items-center justify-center">
                                                <i class="ri-bar-chart-2-line text-xl text-primaryColor"></i>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <i class="ri-notification-3-line text-xl text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="text-md font-bold text-PrimaryBlack dark:text-primaryWhite">
                                                {{ $notification->data['title'] ?? 'اعلان سیستمی' }}
                                            </h3>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>

                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                            {{ $notification->data['message'] ?? 'بدون پیام' }}
                                        </p>
                                        <div class="flex items-center justify-between">
                                            @if(isset($notification->data['url']))
                                                <a href="{{ $notification->data['url'] }}" class="text-sm text-primaryColor hover:text-primaryColor/80 flex items-center gap-1">
                                                    <i class="ri-external-link-line"></i>
                                                    مشاهده
                                                </a>
                                            @else
                                                <span></span>
                                            @endif

                                        @if(!$notification->read_at && isset($notification->data['invite_id']))
                                                    <div class="flex gap-2">
                                                        <form action="{{ route('invites.accept', $notification->data['invite_id']) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md transition-colors duration-300 flex items-center gap-1">
                                                                <i class="ri-check-line"></i>
                                                                قبول
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('invites.reject', $notification->data['invite_id']) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition-colors duration-300 flex items-center gap-1">
                                                                <i class="ri-close-line"></i>
                                                                رد
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif(!$notification->read_at)
                                                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-gray-500 dark:text-gray-400 hover:text-primaryColor dark:hover:text-primaryColor">
                                                        علامت‌گذاری به عنوان خوانده شده
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-4 py-5 border-t border-gray-200 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
                    </div>

                    <!-- Unread Notifications Tab -->
                    <div x-show="tabSelected === 'unread'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        @if(auth()->user()->unreadNotifications->isEmpty())
                            <div class="flex flex-col items-center justify-center p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-mail-unread-line text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <h3 class="text-lg font-medium text-PrimaryBlack dark:text-primaryWhite mb-2">هیچ اعلان خوانده نشده‌ای وجود ندارد</h3>
                                <p class="text-gray-500 dark:text-gray-400">تمام اعلان‌های شما خوانده شده است.</p>
                            </div>
                        @else
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach(auth()->user()->unreadNotifications as $notification)
                                    <div class="p-4 md:p-6 bg-blue-50/30 dark:bg-blue-900/10 transition-all duration-300">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0">
                                                @if(isset($notification->data['election_id']))
                                                    <div class="w-10 h-10 rounded-full bg-primaryColor/10 dark:bg-primaryColor/20 flex items-center justify-center">
                                                        <i class="ri-bar-chart-2-line text-xl text-primaryColor"></i>
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                        <i class="ri-notification-3-line text-xl text-gray-500 dark:text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <h3 class="text-md font-bold text-PrimaryBlack dark:text-primaryWhite">
                                                        {{ $notification->data['title'] ?? 'اعلان سیستمی' }}
                                                    </h3>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                                    {{ $notification->data['message'] ?? 'بدون پیام' }}
                                                </p>
                                                <div class="flex items-center justify-between">
                                                    @if(isset($notification->data['url']))
                                                        <a href="{{ $notification->data['url'] }}" class="text-sm text-primaryColor hover:text-primaryColor/80 flex items-center gap-1">
                                                            <i class="ri-external-link-line"></i>
                                                            مشاهده
                                                        </a>
                                                    @else
                                                        <span></span>
                                                    @endif

                                                    @if(!$notification->read_at && isset($notification->data['invite_id']))
                                                        <div class="flex gap-2">
                                                            <form action="{{ route('invites.accept', $notification->data['invite_id']) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md transition-colors duration-300 flex items-center gap-1">
                                                                    <i class="ri-check-line"></i>
                                                                    قبول
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('invites.reject', $notification->data['invite_id']) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition-colors duration-300 flex items-center gap-1">
                                                                    <i class="ri-close-line"></i>
                                                                    رد
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="text-xs text-gray-500 dark:text-gray-400 hover:text-primaryColor dark:hover:text-primaryColor">
                                                                علامت‌گذاری به عنوان خوانده شده
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Read Notifications Tab -->
                    <div x-show="tabSelected === 'read'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        @php
                            $readNotifications = auth()->user()->notifications()->whereNotNull('read_at')->paginate(10);
                        @endphp

                        @if($readNotifications->isEmpty())
                            <div class="flex flex-col items-center justify-center p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-mail-check-line text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <h3 class="text-lg font-medium text-PrimaryBlack dark:text-primaryWhite mb-2">هیچ اعلان خوانده شده‌ای وجود ندارد</h3>
                                <p class="text-gray-500 dark:text-gray-400">شما هنوز هیچ اعلانی را نخوانده‌اید.</p>
                            </div>
                        @else
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($readNotifications as $notification)
                                    <div class="p-4 md:p-6 bg-white dark:bg-PrimaryBlack transition-all duration-300">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0">
                                                @if(isset($notification->data['election_id']))
                                                    <div class="w-10 h-10 rounded-full bg-primaryColor/10 dark:bg-primaryColor/20 flex items-center justify-center">
                                                        <i class="ri-bar-chart-2-line text-xl text-primaryColor"></i>
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                        <i class="ri-notification-3-line text-xl text-gray-500 dark:text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <h3 class="text-md font-bold text-PrimaryBlack dark:text-primaryWhite">
                                                        {{ $notification->data['title'] ?? 'اعلان سیستمی' }}
                                                    </h3>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                                    {{ $notification->data['message'] ?? 'بدون پیام' }}
                                                </p>
                                                <div class="flex items-center justify-between">
                                                    @if(isset($notification->data['url']))
                                                        <a href="{{ $notification->data['url'] }}" class="text-sm text-primaryColor hover:text-primaryColor/80 flex items-center gap-1">
                                                            <i class="ri-external-link-line"></i>
                                                            مشاهده
                                                        </a>
                                                    @else
                                                        <span></span>
                                                    @endif
                                                    <span class="text-xs text-green-500">
                                                        <i class="ri-check-double-line"></i>
                                                        خوانده شده در {{ $notification->read_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination for Read Notifications -->
                            <div class="px-4 py-5 border-t border-gray-200 dark:border-gray-700">
                                {{ $readNotifications->links() }}
                            </div>
                        @endif
                    </div>
            </div>
        </section>
    </div>
</x-min-layout>
