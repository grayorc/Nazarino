<div class="flex flex-col p-3 bg-postBg w-1/4 my-16 rounded-2xl mx-auto">
    <div class="font-bold text-2xl">
        {{ $election->title }}
    </div>
    <div>
        {{ $election->description }}
    </div>
    <!-- 3 stats comments count how many votes for election and date that election created -->
     <!-- persian numbers -->
    <div class="flex flex-col p-3 text-black">
        <div class="flex flex-row gap-4 m-auto text-3xl">
            <div class="flex flex-col items-center m-auto">
                <i class="ri-chat-1-fill"></i>
                <div class="text-sm font-medium">
                    {{ $election->comments->count() }}
                </div>
            </div>
            <div class="flex flex-col items-center m-auto">
                <i class="ri-user-3-fill"></i>
                <div class="text-sm font-medium">
                    {{ $election->users_count }}
                </div>
            </div>
            <div class="flex flex-col items-center m-auto">
                <i class="ri-chat-poll-fill"></i>
                <div class="text-sm font-medium">
                    {{ $election->votes->count() }}
                </div>
            </div>
            <div class="flex flex-col items-center m-auto ">
                <i class="ri-calendar-event-fill"></i>
                <div class="text-sm font-medium">
                    {{ verta($election->created_at)->format('Y/m/d') }}
                </div>
            </div>
        </div>
    </div>
</div>
