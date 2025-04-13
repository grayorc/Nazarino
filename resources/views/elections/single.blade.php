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
                        <div class="flex flex-row gap-1">
                            <!-- show notify err when click on button if user is not logged in -->
                            @if(!auth()->check())
                            <button onclick="notify('You must be logged in to vote', 'error')">
                                <i class="ri-arrow-up-circle-line ri-xl"></i>
                            </button>
                            @else
                            <button hx-post="/vote" hx-target="#vote-count-{{ $option->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' hx-swap="innerHTML" hx-trigger="click" hx-vals='{"option_id": {{ $option->id }}, "vote_type": "UP"}'>
                                <i class="ri-arrow-up-circle-line ri-xl"></i>
                            </button>
                            @endif
                            <div class="my-auto" id="vote-count-{{ $option->id }}">
                                {{ $option->votes->sum('vote') }}
                            </div>
                            <button hx-post="/vote" hx-target="#vote-count-{{ $option->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' hx-swap="innerHTML" hx-trigger="click" hx-vals='{"option_id": {{ $option->id }}, "vote_type": "DOWN"}'>
                                <i class="ri-arrow-down-circle-line ri-xl"></i>
                            </button>
                        </div>
                        <div class="flex flex-row gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6" viewBox="0 0 24 24" fill="rgba(0,0,0,1)"><path fill="none" d="M0 0h24v24H0z"></path><path d="M10 3H14C18.4183 3 22 6.58172 22 11C22 15.4183 18.4183 19 14 19V22.5C9 20.5 2 17.5 2 11C2 6.58172 5.58172 3 10 3Z"></path></svg>                        <div class="my-auto">
                                {{ $option->votes->sum('vote') }}
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
    <script>

    </script>
</x-inc-layout>
