<x-min-layout>
    <div class="flex flex-col md:flex-row relative min-h-screen bg-gray-900">
        <!-- Sidebar -->
        @include('dash.layouts.sidebar')

        <!-- Main Content -->
        <section class="container px-4 sm:px-6 mx-auto flex-grow pb-8 sm:pb-32">
            <div class="">
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

                    x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);"
                    class="flex flex-col mt-6 bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 relative w-full">

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
                    <div class="relative flex items-center justify-center w-full p-5 mt-2 text-xs text-gray-400 border rounded-md content border-gray-200/70">
                        <!-- Tab 1 Content -->
                        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-100 mb-6">{{ $election->title }}</h2>

                            <div class="flex flex-col lg:flex-row gap-6 sm:gap-8 justify-between items-start">
                                <!-- Bar Chart -->
                                <div class="bg-gray-700 rounded-lg shadow-md p-4 sm:p-6 w-full lg:w-1/2">
                                    <h3 class="text-lg font-medium text-gray-300 mb-4">Upvotes vs Downvotes</h3>
                                    {!! $barChart->render() !!}
                                </div>

                                <!-- Pie Chart -->
                                <div class="bg-gray-700 rounded-lg shadow-md p-4 sm:p-6 w-full lg:w-1/2">
                                    <h3 class="text-lg font-medium text-gray-300 mb-4">Vote Share</h3>
                                    {!! $pieChart->render() !!}
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2 Content -->
                        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>
                            <div class="text-gray-100">
                                <h3 class="text-lg font-medium">Tab 2 Content</h3>
                                <p>This is the content for Tab 2. Add your content here.</p>
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
</x-min-layout>
