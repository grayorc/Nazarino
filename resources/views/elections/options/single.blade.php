<x-inc-layout>
    <div class="flex flex-col md:flex-row w-11/12 lg:w-10/12 mx-auto min-h-lvh">
        @include('elections.layouts.sidebar')
        <div class="flex flex-col w-full md:w-4/6 mx-auto my-4 md:my-16">
            <div class="flex flex-col p-4 bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md hover:shadow-lg transition-all duration-300 rounded-2xl border border-gray-200/50 dark:border-Sidebar_background_hover/30 mt-4">
                <div class="font-bold text-xl md:text-2xl text-PrimaryBlack dark:text-primaryWhite">
                    {{ $option->title }}
                </div>
                <div class="text-sm md:text-base mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90">
                    {{ $option->description }}
                </div>

                <div class="flex flex-wrap gap-4 md:gap-6 mt-3">
                    <div class="flex flex-col items-center gap-4">
                        <div class="flex w-fit flex-row items-center gap-0 rounded-full border border-gray-300 dark:border-gray-700 shadow-sm
                            bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm
                            "
                             @auth()
                                 @if($option->user_vote != null)
                                     style="
                                    background-color:@if($option->user_vote === 1)
                                    rgba(0, 158, 66, 0.9)
                                    @elseif($option->user_vote === -1)
                                    rgba(234, 0, 42, 0.9)
                                    @endif "
                            @endif
                            @endauth
                        >
                            <button class="rounded-full p-1.5 hover:bg-gray-200/70 dark:hover:bg-zinc-700/70 transition-colors duration-200"
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
                            <button class="rounded-full p-1.5 hover:bg-gray-200/70 dark:hover:bg-zinc-700/70 transition-colors duration-200"
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
                    <div class="flex flex-row gap-1.5 items-center text-SecondaryBlack/80 dark:text-SecondaryWhite/80 bg-white/50 dark:bg-zinc-800/50 px-3 py-1.5 rounded-full shadow-sm border border-gray-200 dark:border-gray-700">
                        <i class="ri-chat-1-fill"></i>
                        <!-- count comments -->
                        <span class="text-sm md:text-base">{{ $option->comments?->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 md:mt-8 px-2 md:px-0">
                @auth()
                @if(auth()->user()->hasSubFeature('ai_analysis') && $election->comments)
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <div class="font-bold text-lg md:text-xl">خلاصه هوش مصنوعی</div>
                        <button
                            class="text-xs md:text-sm bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg flex items-center gap-1"
                            hx-get="/options/{{ $option->id }}/ai-summary"
                            hx-target="#ai-summary-content"
                            hx-trigger="click"
                            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M3 5h4"/><path d="M19 17v4"/><path d="M17 19h4"/></svg>
                            تحلیل هوشمند
                        </button>
                    </div>
                    <div id="ai-summary-container" class="relative overflow-hidden rounded-lg">
                        <div class="absolute inset-0 moving-gradient"></div>
                        <div id="ai-summary-content" class="relative bg-white/70 dark:text-black backdrop-blur-sm p-4 rounded-lg text-sm md:text-base">
                            @if(cache()->has('comment_summary_' . $option->id))
                                <div class="text-gray-500 text-center dark:text-black rtl:text-right">{{ cache()->get('comment_summary_' . $option->id) }}</div>
                            @else
                                <div class="text-gray-500 text-center dark:text-black">برای دریافت تحلیل هوشمند نظرات، دکمه بالا را کلیک کنید.</div>
                            @endif
                        </div>
                    </div>

                    <!-- Gemini-style loading animation template -->
                    <template id="ai-summary-skeleton">
                        <div class="flex flex-col gap-2 rtl">
                            <div class="gemini-loading-bar h-5 w-3/4"></div>
                            <div class="gemini-loading-bar h-5 w-full" style="animation-delay: 150ms"></div>
                            <div class="gemini-loading-bar h-5 w-5/6" style="animation-delay: 300ms"></div>
                            <div class="gemini-loading-bar h-5 w-4/6" style="animation-delay: 450ms"></div>
                            <div class="gemini-loading-bar h-5 w-full" style="animation-delay: 600ms"></div>
                        </div>
                    </template>

                    <style>
                        /* Moving gradient background */
                        .moving-gradient {
                            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
                            background-size: 400% 400%;
                            animation: gradient 10s ease infinite;
                            height: 100%;
                            width: 100%;
                        }

                        @keyframes gradient {
                            0% {
                                background-position: 0% 50%;
                            }
                            50% {
                                background-position: 100% 50%;
                            }
                            100% {
                                background-position: 0% 50%;
                            }
                        }

                        /* Gemini-style loading animation */
                        @keyframes expanding {
                            0% {
                                transform: scaleX(0);
                                opacity: 0;
                            }
                            100% {
                                transform: scaleX(1);
                                opacity: 1;
                            }
                        }

                        @keyframes moving {
                            0% {
                                background-position: 0 0;
                            }
                            100% {
                                background-position: -200% 0;
                            }
                        }

                        .gemini-loading-bar {
                            transform-origin: right; /* RTL support - expand from right */
                            animation: expanding 0.4s forwards linear, moving 1s 0.4s infinite forwards linear;
                            border-radius: 0.25rem;
                            background-image: linear-gradient(to left, #eff6ff 30%, #2563eb60 60%, #eff6ff); /* RTL support */
                            background-size: 200% auto;
                            opacity: 0;
                        }
                    </style>

                    <script>
                        document.addEventListener('htmx:beforeRequest', function(event) {
                            if (event.detail.target.id === 'ai-summary-content') {
                                // Get the skeleton template content
                                const skeletonTemplate = document.getElementById('ai-summary-skeleton');
                                if (skeletonTemplate) {
                                    // Clone the template content
                                    const skeletonContent = skeletonTemplate.content.cloneNode(true);
                                    // Clear the target and append the skeleton
                                    event.detail.target.innerHTML = '';
                                    event.detail.target.appendChild(skeletonContent);
                                }
                            }
                        });
                    </script>
                </div>
                @endif
                @endauth

                <div class="font-bold text-xl md:text-2xl mb-4 text-PrimaryBlack dark:text-primaryWhite">نظرات</div>

                @if($election->has_comment)
                    <form class="mb-6"
                        hx-post="/comments"
                        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                        hx-trigger="submit"
                        hx-swap="none">
                        <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="px-3 md:px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                                <label for="comment" class="sr-only">نظر شما</label>
                                <textarea id="comment" name="comment" rows="4" class="w-full px-0 text-sm md:text-base text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="نظر خود را بنویسید..." required ></textarea>
                                <input type="hidden" name="option_id" value="{{ $option->id }}">
                                <input type="hidden" name="election_id" value="{{ $election->id }}">
                            </div>
                            <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600 border-gray-200">
                                <button type="submit" class="inline-flex items-center py-2 md:py-2.5 px-3 md:px-4 text-xs md:text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
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
          <article class="p-6 mb-6 text-base bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" id="comment-${comment.id}">
            <footer class="flex justify-between items-center mb-2">
              <div class="flex items-center">
                <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-primaryWhite font-semibold">
                  ${comment.user_name}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  <time>${comment.created_at}</time>
                </p>
              </div>
              <button class="text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300"
                      hx-delete="/comments/${comment.id}"
                      hx-target="#comment-${comment.id}"
                      hx-swap="outerHTML"
                      hx-confirm="آیا از حذف این نظر مطمئن هستید؟"
                      hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>
                <i class="ri-delete-bin-line"></i>
              </button>
            </footer>
            <p class="text-gray-500 dark:text-gray-300">${comment.body}</p>
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
                    <span class="text-gray-600 dark:text-gray-400"> امکان ثبت دیدگاه وجود ندارد</span>
                @endif

                <div id="comments-list">
                    @foreach($comments as $comment)
                        <article class="p-4 md:p-6 mb-4 md:mb-6 text-sm md:text-base bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" id="comment-{{ $comment->id }}">
                            <footer class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <p class="inline-flex items-center mr-2 md:mr-3 text-xs md:text-sm text-gray-900 dark:text-primaryWhite font-semibold">
                                        {{ $comment->user->name }}
                                    </p>
                                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400">
                                        <time>{{ $comment->created_at->diffForHumans() }}</time>
                                    </p>
                                </div>
                                @if(auth()->id() === $comment->user_id)
                                    <button class="text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 p-1"
                                        hx-delete="/comments/{{ $comment->id }}"
                                        hx-target="#comment-{{ $comment->id }}"
                                        hx-swap="outerHTML"
                                        hx-confirm="آیا از حذف این نظر مطمئن هستید؟"
                                        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                @endif
                            </footer>
                            <p class="text-gray-500 dark:text-gray-300 text-sm md:text-base">{{ $comment->body }}</p>
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
