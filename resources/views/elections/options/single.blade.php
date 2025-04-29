<x-inc-layout>
    <div class="flex w-10/12 mx-auto min-h-lvh">
        @include('elections.layouts.sidebar')
        <div class="flex flex-col w-4/6 mx-auto my-16">
            <div class="flex flex-col p-3 bg-postBg  rounded-2xl mt-4">
                <div class="font-bold text-2xl">
                    {{ $option->title }}
                    </div>
                    <div>
                        {{ $option->description }}
                    </div>

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
        <div class="mt-8">
            <div class="font-bold text-2xl mb-4">نظرات</div>

            @if($election->has_comment)
                <form class="mb-6"
                    hx-post="/comments"
                    hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                    hx-trigger="submit"
                    hx-swap="none">
                   <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                       <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                           <label for="comment" class="sr-only">نظر شما</label>
                           <textarea id="comment" name="comment" rows="4" class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="نظر خود را بنویسید..." required ></textarea>
                           <input type="hidden" name="option_id" value="{{ $option->id }}">
                           <input type="hidden" name="election_id" value="{{ $election->id }}">
                       </div>
                       <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600 border-gray-200">
                           <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                               ارسال نظر
                           </button>
                           <div class="flex ps-0 space-x-1 rtl:space-x-reverse sm:ps-2">
                               <button type="button" class="inline-flex justify-center items-center p-2 text-gray-500 rounded-sm cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                   <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 20">
                                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M1 6v8a5 5 0 1 0 10 0V4.5a3.5 3.5 0 1 0-7 0V13a2 2 0 0 0 4 0V6"/>
                                    </svg>
                                   <span class="sr-only">Attach file</span>
                               </button>
                               <button type="button" class="inline-flex justify-center items-center p-2 text-gray-500 rounded-sm cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                   <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                        <path d="M8 0a7.992 7.992 0 0 0-6.583 12.535 1 1 0 0 0 .12.183l.12.146c.112.145.227.285.326.4l5.245 6.374a1 1 0 0 0 1.545-.003l5.092-6.205c.206-.222.4-.455.578-.7l.127-.155a.934.934 0 0 0 .122-.192A8.001 8.001 0 0 0 8 0Zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                                    </svg>
                                   <span class="sr-only">Set location</span>
                               </button>
                               <button type="button" class="inline-flex justify-center items-center p-2 text-gray-500 rounded-sm cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                   <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                        <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                                    </svg>
                                   <span class="sr-only">Upload image</span>
                               </button>
                           </div>
                       </div>
                   </div>
                </form>
                <script>
                    document.body.addEventListener('htmx:afterRequest', function(event) {
                        console.log('htmx:afterRequest fired', event.detail);

                        const xhr = event.detail.xhr;
                        if (!xhr) {
                            console.warn('No xhr object in event.detail');
                            return;
                        }

                        if (
                            xhr.status === 200
                        ) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                console.log('Parsed response:', response);
                                if (response.status === 'failed') {
                                    if (typeof ToastMagic === 'function' || typeof ToastMagic === 'object') {
                                        const toastInstance = new ToastMagic();
                                        toastInstance.error("خطا!","نمیتوانید در این نظرسنجی، دیدگاه ثبت کنید");
                                    }
                                }
                                if(response.status === 'success' && response.comment) {
                                    if (typeof ToastMagic === 'function' || typeof ToastMagic === 'object') {
                                        const toastInstance = new ToastMagic();
                                        toastInstance.success("نظر شما ثبت گردید!");
                                    } else {
                                        console.warn('ToastMagic is not defined');
                                    }

                                    const comment = response.comment;

                                    const commentTemplate = `
          <article class="p-6 mb-6 text-base bg-white rounded-lg" id="comment-${comment.id}">
            <footer class="flex justify-between items-center mb-2">
              <div class="flex items-center">
                <p class="inline-flex items-center mr-3 text-sm text-gray-900 font-semibold">
                  ${comment.user_name}
                </p>
                <p class="text-sm text-gray-600">
                  <time>${comment.created_at}</time>
                </p>
              </div>
              <button class="text-gray-400 hover:text-gray-600"
                      hx-delete="/comments/${comment.id}"
                      hx-target="#comment-${comment.id}"
                      hx-swap="outerHTML"
                      hx-confirm="آیا از حذف این نظر مطمئن هستید؟"
                      hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>
                <i class="ri-delete-bin-line"></i>
              </button>
            </footer>
            <p class="text-gray-500">${comment.body}</p>
          </article>
        `;

                                    const commentsList = document.getElementById('comments-list');
                                    if (commentsList) {
                                        commentsList.insertAdjacentHTML('afterbegin', commentTemplate);
                                        console.log('Comment added to #comments-list');
                                    } else {
                                        console.warn('#comments-list element not found in DOM');
                                    }
                                } else {
                                    console.warn('Response status not success or comment missing');
                                }
                            } catch (error) {
                                console.error('Error parsing JSON or inserting comment:', error);
                            }
                        }
                    });
                </script>
            @else
                <span class="text-gray-600"> امکان ثبت دیدگاه وجود ندارد</span>
            @endif

            <div id="comments-list">
                @foreach($comments as $comment)
                    <article class="p-6 mb-6 text-base bg-white rounded-lg" id="comment-{{ $comment->id }}">
                        <footer class="flex justify-between items-center mb-2">
                            <div class="flex items-center">
                                <p class="inline-flex items-center mr-3 text-sm text-gray-900 font-semibold">
                                    {{ $comment->user->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <time>{{ $comment->created_at->diffForHumans() }}</time>
                                </p>
                            </div>
                            @if(auth()->id() === $comment->user_id)
                                <button class="text-gray-400 hover:text-gray-600"
                                    hx-delete="/comments/{{ $comment->id }}"
                                    hx-target="#comment-{{ $comment->id }}"
                                    hx-swap="outerHTML"
                                    hx-confirm="آیا از حذف این نظر مطمئن هستید؟"
                                    hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            @endif
                        </footer>
                        <p class="text-gray-500">{{ $comment->body }}</p>
                    </article>
                @endforeach
            </div>
        </div>
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
