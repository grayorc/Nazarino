<x-min-layout>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack pt-16">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1 pb-32 dark:bg-PrimaryBlack">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-PrimaryBlack dark:text-primaryWhite">دعوت به نظرسنجی</h1>
                        <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90">دوستان خود را به نظرسنجی "{{ $election->title }}" دعوت کنید</p>
                    </div>
                    <a href="{{ route('elections.result', $election) }}" class="text-gray-500 dark:text-gray-400 hover:text-primaryColor dark:hover:text-primaryColor">
                        <i class="ri-arrow-right-line text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Invite Tabs -->
            <div
                x-data="{
                    tabSelected: 'followers',
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
                x-init="tabRepositionMarker($refs.tabButtons.querySelector('[data-tab=followers]'));"
                class="bg-white dark:bg-PrimaryBlack rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700"
            >
                <div class="border-b border-gray-200 dark:border-gray-700 relative">
                    <nav x-ref="tabButtons" class="flex flex-wrap" aria-label="Tabs">
                        <button
                            data-tab="followers"
                            @click="tabButtonClicked($el, 'followers')"
                            :class="{'text-primaryColor font-bold': tabSelected === 'followers', 'text-gray-500 dark:text-gray-400': tabSelected !== 'followers'}"
                            class="px-4 py-4 text-center font-medium text-sm w-1/2 transition-colors duration-200 relative z-10"
                        >
                            <i class="ri-user-follow-line ml-1"></i>دنبال‌کنندگان
                        </button>
                        <button
                            data-tab="link"
                            @click="tabButtonClicked($el, 'link')"
                            :class="{'text-primaryColor font-bold': tabSelected === 'link', 'text-gray-500 dark:text-gray-400': tabSelected !== 'link'}"
                            class="px-4 py-4 text-center font-medium text-sm w-1/2 transition-colors duration-200 relative z-10"
                        >
                            <i class="ri-link-m ml-1"></i>لینک دعوت
                        </button>
                        <div x-ref="tabMarker" class="absolute bg-primaryColor duration-300 ease-out transition-all" x-cloak></div>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Followers Tab Content -->
                    <div>
                        <div x-data="{ searchTerm: '' }" class="mb-4" x-show="tabSelected === 'followers'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="searchTerm"
                                    placeholder="جستجوی دنبال‌کنندگان..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primaryColor"
                                >
                                <div class="absolute left-3 top-3 text-gray-400">
                                    <i class="ri-search-line text-xl"></i>
                                </div>
                            </div>

                        <div class="mt-6 max-h-96 overflow-y-auto">
                            @if(auth()->user()->followers->isEmpty())
                                <div class="flex flex-col items-center justify-center p-8 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-gray-200 dark:border-gray-700">
                                    <i class="ri-user-follow-line text-5xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                    <h3 class="text-xl font-medium text-PrimaryBlack dark:text-primaryWhite">هیچ دنبال‌کننده‌ای یافت نشد</h3>
                                    <p class="mt-2 text-SecondaryBlack/90 dark:text-SecondaryWhite/90 text-center">
                                        هنوز کسی شما را دنبال نمی‌کند.
                                    </p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach(auth()->user()->followers as $follower)
                                        <div
                                            x-show="searchTerm === '' ||
                                                '{{ strtolower($follower->follower->username) }}'.includes(searchTerm.toLowerCase()) ||
                                                '{{ strtolower($follower->follower->getDisplayNameAttribute()) }}'.includes(searchTerm.toLowerCase())"
                                            x-transition
                                            class="bg-gradient-to-br from-white to-primaryWhite/90 dark:from-Sidebar_background dark:to-Chart_background shadow-sm rounded-xl border border-gray-200/50 dark:border-Sidebar_background_hover/30 p-4">
                                            <div class="flex items-center gap-4">
                                                <!-- User Avatar -->
                                                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white dark:border-gray-700">
                                                    @if($follower->follower->image)
                                                        <img src="{{ asset('storage/' . $follower->follower->image->path) }}" alt="{{ $follower->follower->username }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-primaryColor text-white text-xl font-bold">
                                                            {{ strtoupper(substr($follower->follower->username, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- User Info -->
                                                <div class="flex-1">
                                                    <p class="font-bold text-PrimaryBlack dark:text-primaryWhite">
                                                        {{ $follower->follower->getDisplayNameAttribute() }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $follower->follower->username.'@' }}</p>
                                                </div>

                                                <!-- Invite Button -->
                                                @if(!$follower->follower->isInvitedToElection($election->id))
                                                <button
                                                    class="invite-button px-4 py-2 rounded-lg bg-primaryColor hover:bg-primaryColor/90 text-white transition-all duration-300 flex items-center gap-2 text-sm"
                                                    hx-vals='{"user_id": "{{ $follower->follower->id }}", "election_id": "{{ $election->id }}"}'
                                                    hx-post="{{ route('election.send-invite', $election) }}"
                                                    hx-headers='{"x-csrf-token": "{{ csrf_token() }}"}'
                                                    hx-trigger="click"
                                                    hx-swap="none"
                                                >
                                                    <i class="ri-user-add-line"></i>
                                                    دعوت
                                                </button>
                                                @else
                                                <button class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 transition-all duration-300 flex items-center gap-2 text-sm">
                                                    <i class="ri-check-line"></i>
                                                    دعوت شده
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Link Tab Content -->
                    <div x-show="tabSelected === 'link'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6 text-center">
                            <h3 class="text-lg font-medium text-PrimaryBlack dark:text-primaryWhite mb-4">لینک دعوت به نظرسنجی</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">با اشتراک‌گذاری این لینک، دوستان خود را به نظرسنجی دعوت کنید</p>

                            <div x-data="{ copied: false }" class="relative">
                                <input
                                    type="text"
                                    id="invite-link"
                                    x-ref="inviteLink"
                                    value="{{ url('/election/' . $election->id) }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primaryColor pr-24"
                                    readonly
                                >
                                <button
                                    @click="copied = true; $refs.inviteLink.select(); document.execCommand('copy'); setTimeout(() => copied = false, 2000)"
                                    class="absolute left-2 top-2 px-4 py-1 bg-primaryColor hover:bg-primaryColor/90 text-white rounded-md transition-all duration-300"
                                >
                                    <span x-show="!copied">کپی</span>
                                    <span x-show="copied" x-transition>کپی شد!</span>
                                </button>
                            </div>

                            <div class="mt-8">
                                <h4 class="text-md font-medium text-PrimaryBlack dark:text-primaryWhite mb-4">اشتراک‌ گذاری در شبکه‌های اجتماعی</h4>
                                <div class="flex justify-center gap-4">
                                    <a href="https://wa.me/?text={{ urlencode('شما به نظرسنجی ' . $election->title . ' دعوت شده‌اید. برای شرکت در نظرسنجی روی لینک زیر کلیک کنید: ' . route('election.show', $election)) }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#25D366] text-white hover:opacity-90 transition-opacity">
                                        <i class="ri-whatsapp-line text-xl"></i>
                                    </a>
                                    <a href="https://telegram.me/share/url?url={{ urlencode(route('election.show', $election)) }}&text={{ urlencode('شما به نظرسنجی ' . $election->title . ' دعوت شده‌اید.') }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#0088cc] text-white hover:opacity-90 transition-opacity">
                                        <i class="ri-telegram-2-fill text-xl"></i>
                                    </a>
                                    <a href="https://x.com/intent/tweet?text={{ urlencode('شما به نظرسنجی ' . $election->title . ' دعوت شده‌اید. برای شرکت در نظرسنجی روی لینک زیر کلیک کنید: ' . route('election.show', $election)) }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:opacity-90 transition-opacity">
                                        <i class="ri-twitter-x-line text-xl"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.body.addEventListener('htmx:afterRequest', function(event) {
            if (event.detail.elt.classList.contains('invite-button')) {
                const response = JSON.parse(event.detail.xhr.responseText);
                if (response.success) {
                    event.detail.elt.outerHTML = `
                        <button class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 transition-all duration-300 flex items-center gap-2 text-sm">
                            <i class="ri-check-line"></i>
                            دعوت شده
                        </button>
                    `;
                }
                if (response.success === false && response.error === 'duplicated') {
                    const toastMagic = new ToastMagic();
                    toastMagic.error(response.message);
                }
            }
        });
    </script>
</x-min-layout>
