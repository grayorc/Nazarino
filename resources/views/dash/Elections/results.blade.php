<x-min-layout>
    <div class="flex flex-col md:flex-row relative min-h-screen bg-gray-900">
        @include('dash.layouts.sidebar')
        <!-- Main Content -->
        <section class="container px-2 sm:px-4 md:px-6 mx-auto flex-grow pb-4 sm:pb-8 md:pb-16">
            <div class="w-full">
                <div
                    x-data="{
                        tabSelected: 'charts',
                        tabId: $id('tabs'),
                        tabButtonClicked(tabButton, tabName){
                            this.tabSelected = tabName;
                            this.tabRepositionMarker(tabButton);
                        },
                        tabRepositionMarker(tabButton){
                            this.$refs.tabMarker.style.width = tabButton.offsetWidth + 'px';
                            this.$refs.tabMarker.style.height = '2px';
                            this.$refs.tabMarker.style.left = tabButton.offsetLeft + 'px';
                            this.$refs.tabMarker.style.bottom = '0px';
                        }
                    }"
                    x-init="
                        tabRepositionMarker($refs.tabButtons.querySelector('[data-tab=charts]'));
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
                    <div class="border-b border-gray-200 dark:border-gray-700 relative">
                        <nav x-ref="tabButtons" class="flex flex-wrap" aria-label="Tabs">
                            <button
                                data-tab="charts"
                                @click="tabButtonClicked($el, 'charts')"
                                :class="{'text-primaryColor font-bold': tabSelected === 'charts', 'text-gray-500 dark:text-gray-400': tabSelected !== 'charts'}"
                                class="px-4 py-4 text-center font-medium text-sm w-1/2 transition-colors duration-200 relative z-10"
                            >
                                <i class="ri-bar-chart-2-line ml-1"></i>نمودارها
                            </button>
                            <button
                                data-tab="analysis"
                                @click="tabButtonClicked($el, 'analysis')"
                                :class="{'text-primaryColor font-bold': tabSelected === 'analysis', 'text-gray-500 dark:text-gray-400': tabSelected !== 'analysis'}"
                                class="px-4 py-4 text-center font-medium text-sm w-1/2 transition-colors duration-200 relative z-10"
                            >
                                <i class="ri-magic-line ml-1"></i>تحلیل هوشمند
                            </button>
                            <!-- Tab Marker -->
                            <div x-ref="tabMarker" class="absolute bg-primaryColor duration-300 ease-out transition-all" x-cloak></div>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Charts Tab Content -->
                        <div x-show="tabSelected === 'charts'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="relative w-full">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
                                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-100">{{ $election->title }}</h2>
                                @can('excel-export')
                                <a href="{{ route('elections.export.single', $election->id) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 transition-colors duration-200 rounded-lg shadow-sm">
                                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_export)">
                                            <path d="M13.3333 13.3332L9.99997 9.9999M9.99997 9.9999L6.66663 13.3332M9.99997 9.9999V17.4999M16.9916 15.3249C17.8044 14.8818 18.4465 14.1806 18.8165 13.3321C19.1866 12.4835 19.2635 11.5359 19.0351 10.6388C18.8068 9.7417 18.2862 8.94616 17.5555 8.37778C16.8248 7.80939 15.9257 7.50052 15 7.4999H13.95C13.6977 6.52427 13.2276 5.61852 12.5749 4.85073C11.9222 4.08295 11.104 3.47311 10.1817 3.06708C9.25943 2.66104 8.25709 2.46937 7.25006 2.50647C6.24304 2.54358 5.25752 2.80849 4.36761 3.28129C3.47771 3.7541 2.70656 4.42249 2.11215 5.23622C1.51774 6.04996 1.11554 6.98785 0.935783 7.9794C0.756025 8.97095 0.803388 9.99035 1.07431 10.961C1.34523 11.9316 1.83267 12.8281 2.49997 13.5832" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_export">
                                                <rect width="20" height="20" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <span>خروجی اکسل</span>
                                </a>
                                @endcan
                            </div>

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

                        <!-- Analysis Tab Content -->
                        <div x-show="tabSelected === 'analysis'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="relative w-full">
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
