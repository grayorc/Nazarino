<x-inc-layout>
    <div class="flex w-10/12 mx-auto min-h-lvh">
        <div class="flex flex-col p-3 bg-postBg w-1/4 my-16 rounded-2xl mx-auto">
            <div class="font-bold text-2xl">
                {{ $election->title }}
            </div>
            <div>
                {{ $election->description }}
            </div>
            <div class="flex flex-col p-3  bg-gray-500 rounded-lg">
                <div class="mb-1 text-sm font-medium dark:text-white">گزینه 1</div>
                <div class=" bg-gray-200 rounded-full dark:bg-gray-700 w-11/12">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 45%"> 45%</div>
                </div>
                <div class="mb-1 text-sm font-medium dark:text-white">گزینه 2</div>
                <div class=" bg-gray-200 rounded-full dark:bg-gray-700 w-11/12 ">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 20%"> 20%</div>
                </div>
                <div class="mb-1 text-sm font-medium dark:text-white">گزینه 3</div>
                <div class=" bg-gray-200 rounded-full dark:bg-gray-700 w-11/12 ">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 10%"> 10%</div>
                </div>
                <div class="mb-1 text-sm font-medium dark:text-white">گزینه 4</div>
                <div class=" bg-gray-200 rounded-full dark:bg-gray-700 w-11/12 ">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 15%"> 15%</div>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-4/6 mx-auto my-16">
            @foreach($options as $option)
                <div class="flex flex-col p-3 bg-postBg  rounded-2xl
                    @if (!$loop->first)
                        mt-4
                    @endif
                ">
                    <div class="font-bold text-2xl">
                        {{ $option->title }}
                    </div>
                    <div>
                        {{ $option->description }}
                    </div>

                    <div class="flex gap-6">
                        <div class="flex flex-col items-center gap-4">
                            <div class="flex w-fit flex-row items-center gap-0 rounded-full border-black bg-zinc-800/30">
                                <button class="rounded-full p-1 hover:bg-zinc-800/30"
                                hx-post="/vote"
                                hx-target="#vote-count-{{ $option->id }}"
                                hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                hx-swap="innerHTML"
                                hx-trigger="click"
                                hx-vals='{"option_id": {{ $option->id }}, "vote_type": "UP"}'>
                                @if(optional($option->user_vote)->vote === 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up text-white fill-white"><path d="M9 18v-6H5l7-7 7 7h-4v6H9z"></path></svg>
                                @elseif(optional($option->user_vote)->vote === -1 || $option->user_vote == null)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up text-black">
                                        <path d="M9 18v-6H5l7-7 7 7h-4v6H9z"></path>
                                    </svg>
                                @endif
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
                                @if(optional($option->user_vote)->vote === -1)
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down text-white fill-white"><path d="M15 6v6h4l-7 7-7-7h4V6h6z"></path></svg>
                                @elseif(optional($option->user_vote)->vote === 1 || $option->user_vote == null)
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down text-black">
                                    <path d="M15 6v6h4l-7 7-7-7h4V6h6z"></path>
                                </svg>
                                @endif
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
{{--                            {{ $option->comments->count() }}--}}
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
    <script>

    </script>
</x-inc-layout>
