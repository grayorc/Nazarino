<x-inc-layout>
    <div class="flex flex-col md:flex-row w-11/12 lg:w-10/12 mx-auto min-h-lvh">
        @include('elections.layouts.sidebar')
        <div class="flex flex-col w-full md:w-4/6 mx-auto my-4 md:my-16">
            @foreach($options as $option)
                <div class="flex flex-col p-4 bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md hover:shadow-lg transition-all duration-300 rounded-2xl border border-gray-200/50 dark:border-Sidebar_background_hover/30
                    @if (!$loop->first)
                        mt-4
                    @endif
                ">
                    <a href="{{ route('option.show', ['election' => $election->id, 'option' => $option->id]) }}">
                        <div class="font-bold text-xl md:text-2xl text-PrimaryBlack dark:text-primaryWhite">
                            {{ $option->title }}
                        </div>
                        <div class="text-sm md:text-base text-SecondaryBlack/90 dark:text-SecondaryWhite/90 mt-1">
                            {{ $option->description }}
                        </div>
                    </a>

                    <div class="flex flex-wrap gap-4 md:gap-6 mt-3">
                        <div class="flex flex-col items-center gap-4">
                            <div class="flex w-fit flex-row items-center gap-0 rounded-full border border-gray-300 dark:border-gray-700 shadow-sm
                            bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm
                            "
                            @auth()
                                @if($option->user_vote != null)
                                    style="
                                    background-color:@if($option->user_vote === 1)
                                    #009e42
                                    @elseif($option->user_vote === -1)
                                    #ea002a
                                    @endif "
                                @endif
                            @endauth
                            >
                                <button class="rounded-full p-1 hover:bg-zinc-800/30"
                                hx-post="/vote"
                                hx-target="#vote-count-{{ $option->id }}"
                                hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                hx-swap="innerHTML"
                                hx-trigger="click"
                                hx-vals='{"option_id": {{ $option->id }}, "vote_type": "UP"}'>
                                @auth()
                                @if($option->user_vote === 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up text-white fill-white"><path d="M9 18v-6H5l7-7 7 7h-4v6H9z"></path></svg>
                                @elseif($option->user_vote === -1 || $option->user_vote == null)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up text-black dark:text-primaryWhite">
                                        <path d="M9 18v-6H5l7-7 7 7h-4v6H9z"></path>
                                    </svg>
                                @endif
                                @endauth
                                @guest()
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up text-black dark:text-primaryWhite">
                                            <path d="M9 18v-6H5l7-7 7 7h-4v6H9z"></path>
                                        </svg>
                                @endguest
                                </button>
                                <span class="min-w-10 p-1 text-center font-medium text-PrimaryBlack dark:text-primaryWhite">
                                    <number-flow class="font-mono" id="vote-count-{{ $option->id }}">{{ $option->votes->sum('vote') }}</number-flow>
                                </span>
                                <button class="rounded-full p-1 hover:bg-zinc-800/30"
                                hx-post="/vote"
                                hx-target="#vote-count-{{ $option->id }}"
                                hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                hx-swap="innerHTML"
                                hx-trigger="click"
                                hx-vals='{"option_id": {{ $option->id }}, "vote_type": "DOWN"}'>
                                @auth()
                                @if($option->user_vote === -1)
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down text-white fill-white"><path d="M15 6v6h4l-7 7-7-7h4V6h6z"></path></svg>
                                @elseif($option->user_vote === 1 || $option->user_vote == null)
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down text-black dark:text-primaryWhite">
                                    <path d="M15 6v6h4l-7 7-7-7h4V6h6z"></path>
                                </svg>
                                @endif
                                @endauth
                                @guest()
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down text-black dark:text-primaryWhite">
                                        <path d="M15 6v6h4l-7 7-7-7h4V6h6z"></path>
                                    </svg>
                                @endguest
                            </div>
                        </div>
{{--                        <div class="flex flex-row gap-1">--}}
{{--                            <button hx-post="/vote" hx-target="#vote-count-{{ $option->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' hx-swap="innerHTML" hx-trigger="click" hx-vals='{"option_id": {{ $option->id }}, "vote_type": "UP"}'>--}}
{{--                                <i class="ri-arrow-up-circle-line ri-xl"></i>--}}
{{--                            </button>--}}
{{--                            <div class="my-auto" id="vote-count-{{ $option->id }}">--}}
{{--                                {{ $option->votes->sum('vote') }}--}}
{{--                            </div>--}}
{{--                            <button hx-post="/vote" hx-target="#vote-count-{{ $option->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' hx-swap="innerHTML" hx-trigger="click" hx-vals='{"option_id": {{ $option->id }}, "vote_type": "DOWN"}'>--}}
{{--                                <i class="ri-arrow-down-circle-line ri-xl"></i>--}}
{{--                            </button>--}}
{{--                        </div>--}}
                        <div class="flex flex-row gap-1.5 items-center text-SecondaryBlack/80 dark:text-SecondaryWhite/80 bg-white/50 dark:bg-zinc-800/50 px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-700">
                            <i class="ri-chat-1-fill"></i>
                            <!-- count comments -->
                            <span class="text-sm md:text-base">{{ $option->comments?->count() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        document.body.addEventListener("htmx:afterRequest", function (event) {
            const response = JSON.parse(event.detail.xhr.responseText);

            const voteCountElement = document.querySelector(`#vote-count-${response.option_id}`);
            const parentContainer = voteCountElement.closest('.flex.w-fit.flex-row.items-center');

            voteCountElement.textContent = response.vote;
            const upvoteButton = voteCountElement.closest('.flex').querySelector('button:nth-child(1) svg');
            const downvoteButton = voteCountElement.closest('.flex').querySelector('button:nth-child(3) svg');

            if (response.vote_type === "UP") {
                parentContainer.style.backgroundColor = "rgba(0, 158, 66, 0.9)";

                upvoteButton.classList.replace("text-black", "text-white");
                upvoteButton.classList.add("fill-white");
                downvoteButton.classList.replace("text-white", "text-black");
                downvoteButton.classList.remove("fill-white");
              } else if (response.vote_type === "DOWN") {
                parentContainer.style.backgroundColor = "rgba(234, 0, 42, 0.9)";

                downvoteButton.classList.replace("text-black", "text-white");
                downvoteButton.classList.add("fill-white");
                upvoteButton.classList.replace("text-white", "text-black");
                upvoteButton.classList.remove("fill-white");
              } else if (response.vote_type === "NONE") {
                parentContainer.style.backgroundColor = "";

                upvoteButton.classList.replace("text-white", "text-black");
                upvoteButton.classList.remove("fill-white");
                downvoteButton.classList.replace("text-white", "text-black");
                downvoteButton.classList.remove("fill-white");
              }
        });
    </script>

</x-inc-layout>
