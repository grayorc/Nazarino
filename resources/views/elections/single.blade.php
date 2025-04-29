<x-inc-layout>
    <div class="flex w-10/12 mx-auto min-h-lvh">
        @include('elections.layouts.sidebar')
        <div class="flex flex-col w-4/6 mx-auto my-16">
            @foreach($options as $option)
                <div class="flex flex-col p-3 bg-postBg  rounded-2xl
                    @if (!$loop->first)
                        mt-4
                    @endif
                ">
                    <a href="{{ route('option.show', ['election_id' => $election->id, 'option_id' => $option->id]) }}">
                        <div class="font-bold text-2xl">
                            {{ $option->title }}
                        </div>
                        <div>
                            {{ $option->description }}
                        </div>
                    </a>

                    <div class="flex gap-6">
                        <div class="flex flex-col items-center gap-4">
                            <div class="flex w-fit flex-row items-center gap-0 rounded-full border-black
                            bg-zinc-800/30
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up text-black">
                                        <path d="M9 18v-6H5l7-7 7 7h-4v6H9z"></path>
                                    </svg>
                                @endif
                                @endauth
                                @guest()
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up text-black">
                                            <path d="M9 18v-6H5l7-7 7 7h-4v6H9z"></path>
                                        </svg>
                                @endguest
                                </button>
                                <span class="min-w-8 p-1 text-center text-black">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down text-black">
                                    <path d="M15 6v6h4l-7 7-7-7h4V6h6z"></path>
                                </svg>
                                @endif
                                @endauth
                                @guest()
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down text-black">
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
                        <div class="flex flex-row gap-1">
                            <i class="ri-chat-1-fill"></i>
                            <!-- count comments -->
                            {{ $option->comments->count() }}
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
                parentContainer.style.backgroundColor = "#009e42";

                upvoteButton.classList.replace("text-black", "text-white");
                upvoteButton.classList.add("fill-white");
                downvoteButton.classList.replace("text-white", "text-black");
                downvoteButton.classList.remove("fill-white");
              } else if (response.vote_type === "DOWN") {
                parentContainer.style.backgroundColor = "#ea002a";

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
