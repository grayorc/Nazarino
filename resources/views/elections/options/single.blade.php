<x-inc-layout>
    <div x-data="{
        deleteModalOpen: false,
        commentToDelete: null
    }"
         class="relative">
    <div class="flex flex-col md:flex-row w-11/12 lg:w-10/12 mx-auto min-h-lvh">
        @include('elections.layouts.sidebar')
        <div class="flex flex-col w-full md:w-4/6 mx-auto my-4 md:my-16">
            <div class="flex flex-col p-4 bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-md hover:shadow-lg transition-all duration-300 rounded-2xl border border-gray-200/50 dark:border-Sidebar_background_hover/30 mt-4">
                @if($option->image)
                <div x-data="{
                    imageModalOpen: false,
                    imageUrl: '{{ asset('storage/' . $option->image->path) }}',
                    imageAlt: '{{ $option->title }}',
                    openImage() {
                        this.imageModalOpen = true;
                    },
                    closeImage() {
                        this.imageModalOpen = false;
                    }
                }" 
                @keydown.window.escape="closeImage"
                class="mb-3 rounded-xl overflow-hidden border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                    <img 
                        @click="openImage" 
                        src="{{ asset('storage/' . $option->image->path) }}" 
                        alt="{{ $option->title }}" 
                        class="w-full h-auto object-cover cursor-zoom-in">
                    
                    <!-- Image Modal -->
                    <template x-teleport="body">
                        <div 
                            x-show="imageModalOpen" 
                            x-transition:enter="transition ease-in-out duration-300" 
                            x-transition:enter-start="opacity-0" 
                            x-transition:leave="transition ease-in-in duration-300" 
                            x-transition:leave-end="opacity-0" 
                            @click="closeImage" 
                            class="fixed inset-0 z-[99] flex items-center justify-center bg-black bg-opacity-80 select-none cursor-zoom-out" 
                            x-cloak>
                            <div class="relative flex items-center justify-center w-11/12 xl:w-4/5 h-11/12"> 
                                <img 
                                    x-show="imageModalOpen" 
                                    x-transition:enter="transition ease-in-out duration-300" 
                                    x-transition:enter-start="opacity-0 transform scale-50" 
                                    x-transition:leave="transition ease-in-in duration-300" 
                                    x-transition:leave-end="opacity-0 transform scale-50" 
                                    class="object-contain object-center w-full h-full max-h-[90vh] select-none cursor-zoom-out" 
                                    :src="imageUrl" 
                                    :alt="imageAlt">
                                <button @click="closeImage" class="absolute top-4 right-4 bg-white/10 text-white rounded-full p-2 hover:bg-white/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                @endif
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
                      @click="deleteModalOpen = true; commentToDelete = ${comment.id}">
                <i class="ri-delete-bin-line"></i>
              </button>
            </footer>
            <p class="text-gray-500 dark:text-gray-300">${comment.body}</p>
          </article>
        `;

                                        const commentsList = document.getElementById('comments-list');
                                        if (commentsList) {
                                            commentsList.insertAdjacentHTML('afterbegin', commentTemplate);
                                        }
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
                                @auth
                                    @if(auth()->id() === $comment->user_id || auth()->user()->id == $election->user_id)
                                        <button class="text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 p-1"
                                            @click="deleteModalOpen = true; commentToDelete = {{ $comment->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    @endif
                                @endauth
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

    <template x-teleport="body">
        <div x-show="deleteModalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" dir="ltr" x-cloak>
            <div x-show="deleteModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="deleteModalOpen=false" class="absolute inset-0 w-full h-full bg-white dark:bg-SecondaryBlack/70 backdrop-blur-sm bg-opacity-70"></div>
            <div x-show="deleteModalOpen"
                 x-trap.inert.noscroll="deleteModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 -translate-y-2 sm:scale-95"
                 class="relative w-full py-6 bg-white border shadow-lg px-7 border-neutral-200 sm:max-w-lg sm:rounded-lg dark:bg-PrimaryBlack dark:border-gray-700">
                <div class="flex items-center justify-between pb-3">
                    <h3 class="text-lg font-semibold dark:text-white">حذف نظر</h3>
                    <button @click="deleteModalOpen=false" class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="relative w-auto pb-8">
                    <p class="dark:text-gray-300">آیا از حذف این نظر اطمینان دارید؟ این عمل غیرقابل بازگشت است.</p>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                    <button @click="deleteModalOpen=false" type="button" class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium transition-colors border rounded-md focus:outline-none focus:ring-2 focus:ring-neutral-100 focus:ring-offset-2 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">انصراف</button>
                    <button
                        type="button"
                        @click="
                            const deleteUrl = `/comments/${commentToDelete}`;
                            const targetSelector = `#comment-${commentToDelete}`;
                            const csrfToken = '{{ csrf_token() }}';

                            htmx.ajax('DELETE', deleteUrl, {
                                target: targetSelector,
                                swap: 'outerHTML',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            deleteModalOpen = false;
                        "
                        class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium text-white transition-colors border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2 bg-red-600 hover:bg-red-700">
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
</x-inc-layout>
