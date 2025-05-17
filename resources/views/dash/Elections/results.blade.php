<x-min-layout>
    <div class="flex flex-col md:flex-row relative min-h-screen bg-gray-900">
        @include('dash.layouts.sidebar')
        <!-- Main Content -->
        <section class="container px-2 sm:px-4 md:px-6 mx-auto flex-grow pb-4 sm:pb-8 md:pb-16">
            <div class="w-full">
                <div dir="ltr"
                    x-data="{
                        tabSelected: 1,
                        tabId: $id('tabs'),
                        tabButtonClicked(tabButton){
                            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
                            this.tabRepositionMarker(tabButton);
                        },
                        tabRepositionMarker(tabButton){
                            this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
                            this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
                            this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
                        },
                        tabContentActive(tabContent){
                            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
                        },
                        tabButtonActive(tabContent){
                            const tabId = tabContent.id.split('-').slice(-1);
                            return this.tabSelected == tabId;
                        }
                    }"

                    x-init="
                        tabRepositionMarker($refs.tabButtons.firstElementChild);
                        Alpine.store('aiAnalysis', {
                            loading: false,
                            content: null,
                            setLoading(value) {
                                this.loading = value;
                            },
                            setContent(value) {
                                this.content = value;
                            }
                        });
                    "
                    class="flex flex-col mt-4 sm:mt-6 bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 relative w-full">

                    <!-- Tab Navigation -->
                    <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-500 bg-white border border-gray-100 rounded-lg select-none">
                        <button :id="$id(tabId)"
                                @click="tabButtonClicked($el);"
                                type="button"
                                :class="{ 'bg-gray-100 text-gray-700' : tabButtonActive($el) }"
                                class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">
                            Tab 1
                        </button>
                        <button :id="$id(tabId)"
                                @click="tabButtonClicked($el);"
                                type="button"
                                :class="{ 'bg-gray-100 text-gray-700' : tabButtonActive($el) }"
                                class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">
                            Tab 2
                        </button>
{{--                        <button :id="$id(tabId)"--}}
{{--                                @click="tabButtonClicked($el);"--}}
{{--                                type="button"--}}
{{--                                :class="{ 'bg-gray-100 text-gray-700' : tabButtonActive($el) }"--}}
{{--                                class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">--}}
{{--                            Tab 3--}}
{{--                        </button>--}}
                        <!-- Tab Marker -->
                        <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/3 h-full duration-300 ease-out" x-cloak>
                            <div class="w-full h-full bg-gray-100 rounded-md shadow-sm"></div>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="relative flex items-center justify-center w-full p-3 sm:p-4 md:p-5 mt-2 text-xs sm:text-sm text-gray-400 border rounded-md content border-gray-200/70">
                        <!-- Tab 1 Content -->
                        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative w-full">
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-100 mb-4 sm:mb-6">{{ $election->title }}</h2>

                            <div class="flex flex-col lg:flex-row gap-4 sm:gap-6 md:gap-8 justify-between items-start">
                                <!-- Bar Chart -->
                                <div class="bg-gray-700 rounded-lg shadow-md p-3 sm:p-4 md:p-6 w-full lg:w-1/2">
                                    <h3 class="text-base sm:text-lg font-medium text-gray-300 mb-3 sm:mb-4">رای های منفی و مثبت</h3>
                                    <div class="w-full overflow-x-auto">
                                        {!! $barChart->render() !!}
                                    </div>
                                </div>

                                <!-- Pie Chart -->
                                <div class="bg-gray-700 rounded-lg shadow-md p-3 sm:p-4 md:p-6 w-full lg:w-1/2">
                                    <h3 class="text-base sm:text-lg font-medium text-gray-300 mb-3 sm:mb-4">نسبت رای ها</h3>
                                    <div class="w-full overflow-x-auto">
                                        {!! $pieChart->render() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2 Content -->
                        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative w-full" x-cloak dir="rtl">
                            @can('ai-analysis')
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-100 mb-4 sm:mb-6">تحلیل هوشمند نظرات</h2>

                            <div class="flex flex-col gap-6 w-full">
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-300 text-sm md:text-base">با استفاده از هوش مصنوعی، نظرات کاربران را تحلیل کنید.</p>
                                    <button type="button"
                                            @click="$store.aiAnalysis.setLoading(true)"
                                            hx-get="{{ route('elections.ai-analysis', $election->id) }}"
                                            hx-target="#ai-analysis-content"
                                            hx-trigger="click"
                                            hx-on::after-request="$store.aiAnalysis.setLoading(false); $store.aiAnalysis.setContent(event.detail.xhr.response)"
                                            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                            class="flex items-center gap-2 px-4 py-2 text-white rounded-lg bg-gradient-to-r from-[#EB5E28] to-[#FF8C66] hover:from-[#FF8C66] hover:to-[#EB5E28] transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" class="size-4">
                                            <path fill-rule="evenodd" d="M5 4a.75.75 0 0 1 .738.616l.252 1.388A1.25 1.25 0 0 0 6.996 7.01l1.388.252a.75.75 0 0 1 0 1.476l-1.388.252A1.25 1.25 0 0 0 5.99 9.996l-.252 1.388a.75.75 0 0 1-1.476 0L4.01 9.996A1.25 1.25 0 0 0 3.004 8.99l-1.388-.252a.75.75 0 0 1 0-1.476l1.388-.252A1.25 1.25 0 0 0 4.01 6.004l.252-1.388A.75.75 0 0 1 5 4ZM12 1a.75.75 0 0 1 .721.544l.195.682c.118.415.443.74.858.858l.682.195a.75.75 0 0 1 0 1.442l-.682.195a1.25 1.25 0 0 0-.858.858l-.195.682a.75.75 0 0 1-1.442 0l-.195-.682a1.25 1.25 0 0 0-.858-.858l-.682-.195a.75.75 0 0 1 0-1.442l.682-.195a1.25 1.25 0 0 0 .858-.858l.195-.682A.75.75 0 0 1 12 1ZM10 11a.75.75 0 0 1 .728.568.968.968 0 0 0 .704.704.75.75 0 0 1 0 1.456.968.968 0 0 0-.704.704.75.75 0 0 1-1.456 0 .968.968 0 0 0-.704-.704.75.75 0 0 1 0-1.456.968.968 0 0 0 .704-.704A.75.75 0 0 1 10 11Z" clip-rule="evenodd"/>
                                        </svg>
                                        تحلیل هوشمند
                                    </button>
                                </div>

                                <div x-data="{
                                    copiedToClipboard: false,
                                    copyToClipboard() {
                                        navigator.clipboard
                                        .writeText($refs.aiAnalysisContent.textContent)
                                        .then(() => {
                                            this.copiedToClipboard = true
                                        })
                                        .catch((err) => {
                                            this.copiedToClipboard = false
                                        })
                                    },
                                }" id="ai-analysis-container" class="flex flex-col gap-4 border border-neutral-300 rounded-sm bg-neutral-50 p-6 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                                    <div id="ai-analysis-content" x-ref="aiAnalysisContent" class="relative text-sm md:text-base min-h-[200px] prose prose-sm max-w-none dark:prose-invert rtl:text-right">
                                        <template x-if="!$store.aiAnalysis.loading && $store.aiAnalysis.content">
                                            <div class="text-gray-200 rtl:text-right" x-html="$store.aiAnalysis.content"></div>
                                        </template>

                                        <template x-if="$store.aiAnalysis.loading">
                                            <div class="flex flex-col gap-4">
                                                <div class="h-5 w-10/12 origin-left animate-loading rounded-sm bg-gradient-to-r from-gray-800 from-30% via-gray-600/60 to-gray-800 bg-[length:200%] opacity-0 dark:from-gray-700 dark:via-gray-500/60 dark:to-gray-700"></div>
                                                <div class="h-5 w-full origin-left animate-loading rounded-sm bg-gradient-to-r from-gray-800 from-30% via-gray-600/60 to-gray-800 bg-[length:200%] opacity-0 dark:from-gray-700 dark:via-gray-500/60 dark:to-gray-700"></div>
                                                <div class="h-5 w-3/5 origin-left animate-loading rounded-sm bg-gradient-to-r from-gray-800 from-30% via-gray-600/60 to-gray-800 bg-[length:200%] opacity-0 dark:from-gray-700 dark:via-gray-500/60 dark:to-gray-700"></div>
                                            </div>
                                        </template>
                                        @if(!$election->aiAnalysis)
                                        <template x-if="!$store.aiAnalysis.loading && !$store.aiAnalysis.content">
                                            <div class="text-gray-300 text-center">برای دریافت تحلیل هوشمند نظرات، دکمه تحلیل هوشمند را کلیک کنید.</div>
                                        </template>
                                        @else
                                        <template x-if="!$store.aiAnalysis.loading && !$store.aiAnalysis.content">
                                            <div class="text-gray-300 text-">{!! Str::markdown($election->aiAnalysis->content) !!}</div>
                                        </template>
                                        @endif
                                    </div>
                                    <button class="rounded-full w-fit p-1 text-neutral-600/75 hover:bg-neutral-950/10 hover:text-neutral-600 focus:outline-hidden focus-visible:text-neutral-600 focus-visible:outline focus-visible:outline-offset-0 focus-visible:outline-black active:bg-neutral-950/5 active:-outline-offset-2 dark:text-neutral-300/75 dark:hover:bg-white/10 dark:hover:text-neutral-300 dark:focus-visible:text-neutral-300 dark:focus-visible:outline-white dark:active:bg-white/5" title="Copy" aria-label="Copy" x-on:click="copyToClipboard()" x-on:click.away="copiedToClipboard = false">
                                        <span class="sr-only" x-text="copiedToClipboard ? 'copied' : 'copy the response to clipboard'"></span>
                                        <svg x-show="!copiedToClipboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M13.887 3.182c.396.037.79.08 1.183.128C16.194 3.45 17 4.414 17 5.517V16.75A2.25 2.25 0 0 1 14.75 19h-9.5A2.25 2.25 0 0 1 3 16.75V5.517c0-1.103.806-2.068 1.93-2.207.393-.048.787-.09 1.183-.128A3.001 3.001 0 0 1 9 1h2c1.373 0 2.531.923 2.887 2.182ZM7.5 4A1.5 1.5 0 0 1 9 2.5h2A1.5 1.5 0 0 1 12.5 4v.5h-5V4Z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg x-show="copiedToClipboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 fill-green-500">
                                            <path fill-rule="evenodd" d="M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z" clip-rule="evenodd"/>
                                            <path fill-rule="evenodd" d="M2 7a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7Zm6.585 1.08a.75.75 0 0 1 .336 1.005l-1.75 3.5a.75.75 0 0 1-1.16.234l-1.75-1.5a.75.75 0 0 1 .977-1.139l1.02.875 1.321-2.64a.75.75 0 0 1 1.006-.336Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                                @endcan

                                <!-- Options with comment counts -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    @foreach ($election->options as $option)
                                        <div class="bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-600">
                                            <h3 class="text-gray-200 font-medium mb-2">{{ $option->title }}</h3>
                                            <div class="flex flex-row gap-3 items-center">
                                                <div class="flex flex-row gap-1.5 items-center text-gray-300 bg-gray-600/50 px-3 py-1.5 rounded-full shadow-sm border border-gray-500">
                                                    <i class="ri-chat-1-fill"></i>
                                                    <span class="text-sm md:text-base">{{ $option->comments?->count() }} نظر</span>
                                                </div>
                                                <div class="flex flex-row gap-1.5 items-center text-gray-300 bg-gray-600/50 px-3 py-1.5 rounded-full shadow-sm border border-gray-500">
                                                    <i class="ri-thumb-up-fill"></i>
                                                    <span class="text-sm md:text-base">{{ $option->votes->sum('vote') }} رای</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

{{--                        <!-- Tab 3 Content -->--}}
{{--                        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>--}}
{{--                            <div class="text-gray-100">--}}
{{--                                <h3 class="text-lg font-medium">Tab 3 Content</h3>--}}
{{--                                <p>This is the content for Tab 3. Add your content here.</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Styles for AI Analysis -->
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

        /* Loading animation */
        .gemini-loading-bar {
            animation: gemini-pulse 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        @keyframes gemini-pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        /* RTL support */
        .rtl {
            direction: rtl;
            text-align: right;
        }
    </style>
</x-min-layout>
