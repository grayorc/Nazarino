<div class="flex flex-col p-4 bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md hover:shadow-lg transition-all duration-300 rounded-2xl border border-gray-200/50 dark:border-Sidebar_background_hover/30 w-full md:w-1/4 my-4 md:my-16 mx-auto">
    @if($election->image)
    <div class="flex justify-center mb-4">
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden border-4 border-primaryWhite dark:border-PrimaryBlack shadow-md bg-white dark:bg-gray-800">
            <img src="{{ asset('storage/' . $election->image->path) }}" alt="{{ $election->title }}" class="w-full h-full object-cover">
        </div>
    </div>
    @endif
    <div class="font-bold text-xl md:text-2xl text-PrimaryBlack dark:text-primaryWhite">
        {{ $election->title }}
    </div>
    <div class="text-sm md:text-base mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90">
        {{ $election->description }}
    </div>
    <!-- 3 stats comments count how many votes for election and date that election created -->
     <!-- persian numbers -->
    <div class="flex flex-col p-3 md:p-4 mt-3 text-PrimaryBlack dark:text-primaryWhite bg-white/50 dark:bg-zinc-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex flex-row gap-3 md:gap-5 m-auto text-2xl md:text-3xl">
            <div class="flex flex-col items-center m-auto">
                <i class="ri-chat-1-fill"></i>
                <div class="text-xs md:text-sm font-medium mt-1">
                    {{ $election->comments()->count() }}
                </div>
            </div>
            <div class="flex flex-col items-center m-auto">
                <i class="ri-user-3-fill"></i>
                <div class="text-xs md:text-sm font-medium mt-1">
                    {{ $election->userCount() }}
                </div>
            </div>
            <div class="flex flex-col items-center m-auto">
                <i class="ri-chat-poll-fill"></i>
                <div class="text-xs md:text-sm font-medium mt-1">
                    {{ $election->getTotalVotes() }}
                </div>
            </div>
            <div class="flex flex-col items-center m-auto">
                <i class="ri-calendar-event-fill"></i>
                <div class="text-xs md:text-sm font-medium mt-1">
                    {{ verta($election->created_at)->format('Y/m/d') }}
                </div>
            </div>
        </div>
    </div>

    {{-- User Hover Card Component --}}
    <div x-data="{
        hoverCardHovered: false,
        hoverCardDelay: 600,
        hoverCardLeaveDelay: 500,
        hoverCardTimout: null,
        hoverCardLeaveTimeout: null,
        hoverCardEnter () {
            clearTimeout(this.hoverCardLeaveTimeout);
            if(this.hoverCardHovered) return;
            clearTimeout(this.hoverCardTimout);
            this.hoverCardTimout = setTimeout(() => {
                this.hoverCardHovered = true;
            }, this.hoverCardDelay);
        },
        hoverCardLeave () {
            clearTimeout(this.hoverCardTimout);
            if(!this.hoverCardHovered) return;
            clearTimeout(this.hoverCardLeaveTimeout);
            this.hoverCardLeaveTimeout = setTimeout(() => {
                this.hoverCardHovered = false;
            }, this.hoverCardLeaveDelay);
        }
    }" class="relative mt-4 text-center" @mouseover="hoverCardEnter()" @mouseleave="hoverCardLeave()">
        <div class="flex items-center justify-center gap-2">
            <span class="text-sm text-SecondaryBlack/80 dark:text-SecondaryWhite/80">ایجاد شده توسط:</span>
            <a href="{{ route('users.profile', $election->user->username) }}" class="hover:underline text-primaryColor dark:text-primaryWhite font-medium">{{ '@' . $election->user->username }}</a>
        </div>

        <div x-show="hoverCardHovered" class="absolute top-full mt-2 w-[300px] z-30 -translate-x-1/2 left-1/2" x-cloak>
            <div x-show="hoverCardHovered" class="w-full h-auto bg-white dark:bg-gray-800 space-x-3 p-4 flex items-start rounded-md shadow-md border border-neutral-200/70 dark:border-gray-700"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                {{-- User Profile Image --}}
                <div class="rounded-full w-14 h-14 overflow-hidden border-2 border-primaryWhite dark:border-gray-700 shadow-sm bg-white dark:bg-gray-800">
                    @if($election->user->image)
                        <img src="{{ asset('storage/' . $election->user->image->path) }}" alt="{{ $election->user->display_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-primaryColor text-white text-xl font-bold">
                            {{ strtoupper(substr($election->user->username, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- User Information --}}
                <div class="relative mr-3">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="font-bold text-PrimaryBlack dark:text-primaryWhite">{{ $election->user->getDisplayNameAttribute() }}</p>
                        @if($election->user->hasRole('admin'))
                            <span class="px-2 py-0.5 text-xs bg-primaryColor/10 text-primaryColor rounded-full">ادمین</span>
                        @endif
                    </div>
                    <p class="text-sm text-SecondaryBlack/80 dark:text-SecondaryWhite/80 mb-2">{{ $election->user->username.'@' }}</p>

                    {{-- Stats - Matching the user profile stats --}}
                    <div class="flex items-center gap-4 mt-2 text-xs">
                        <div class="text-center">
                            <span class="block font-bold text-PrimaryBlack dark:text-primaryWhite">{{ $election->user->totalElections() }}</span>
                            <span class="text-gray-600 dark:text-gray-400">نظرسنجی‌ها</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-bold text-PrimaryBlack dark:text-primaryWhite">{{ App\Models\Follower::where('user_id', $election->user->id)->count() }}</span>
                            <span class="text-gray-600 dark:text-gray-400">دنبال‌کنندگان</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-bold text-PrimaryBlack dark:text-primaryWhite">{{ App\Models\Follower::where('follower_id', $election->user->id)->count() }}</span>
                            <span class="text-gray-600 dark:text-gray-400">دنبال‌شده‌ها</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
