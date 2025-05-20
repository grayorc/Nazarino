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
</div>
