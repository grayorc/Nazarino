<x-min-layout>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-2 md:px-4 mx-auto flex-grow-1 w-full">
            <!-- Main Content Container -->
                <!-- Dashboard Title -->
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">داشبورد</h1>
                </div>

                <!-- Stats Cards Row -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4">
                    <!-- Stat Card 1 - Subscription Days -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-PrimaryBlack md:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primaryColor">
                            <svg class="fill-gray-800 dark:fill-white/90" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M2 11H22V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V11ZM17 3H21C21.5523 3 22 3.44772 22 4V9H2V4C2 3.44772 2.44772 3 3 3H7V1H9V3H15V1H17V3Z"></path>
                            </svg>
                        </div>

                        <div class="mt-5 flex items-end justify-between">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">روزهای باقی‌مانده اشتراک</span>
                                <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white">
                                    {{ $remainingDays ?? 0 }} روز
                                </h4>
                            </div>

                            <span class="flex items-center gap-1 rounded-full bg-blue-100 py-0.5 px-2 text-sm font-medium {{ $remainingDays > 0 ? 'text-blue-600 dark:bg-blue-900/30' : 'text-red-600 dark:bg-red-900/30' }}">
                                @if($remainingDays > 0)
                                <svg class="h-3 w-3 mr-0.5" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                فعال
                                @else
                                <svg class="h-3 w-3 mr-0.5" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                منقضی
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Stat Card 2 - Active Polls -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-PrimaryBlack md:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primaryColor">
                            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5M12 12H15M12 16H15M9 12H9.01M9 16H9.01" stroke="" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <div class="mt-5 flex items-end justify-between">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">نظرسنجی‌های فعال</span>
                                <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white">
                                    45
                                </h4>
                            </div>

                            <span class="flex items-center gap-1 rounded-full bg-green-100 py-0.5 px-2 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-500">
                                <svg class="h-3 w-3 mr-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 19V5M5 12L12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                12.5%
                            </span>
                        </div>
                    </div>

                    <!-- Stat Card 3 - Votes -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-PrimaryBlack md:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primaryColor">
                            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>

                        <div class="mt-5 flex items-end justify-between">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">آرای ثبت شده</span>
                                <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white">
                                    2,790
                                </h4>
                            </div>

                            <span class="flex items-center gap-1 rounded-full bg-green-100 py-0.5 px-2 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-500">
                                <svg class="h-3 w-3 mr-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 19V5M5 12L12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                3.8%
                            </span>
                        </div>
                    </div>

                    <!-- Stat Card 4 - Comments -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-PrimaryBlack md:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primaryColor">
                            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 10H8.01M12 10H12.01M16 10H16.01M9 16H5C3.89543 16 3 15.1046 3 14V6C3 4.89543 3.89543 4 5 4H19C20.1046 4 21 4.89543 21 6V14C21 15.1046 20.1046 16 19 16H14L9 21V16Z" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>

                        <div class="mt-5 flex items-end justify-between">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">نظرات</span>
                                <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white">
                                    325
                                </h4>
                            </div>

                            <span class="flex items-center gap-1 rounded-full bg-red-100 py-0.5 px-2 text-sm font-medium text-red-600 dark:bg-red-900/30 dark:text-red-500">
                                <svg class="h-3 w-3 mr-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 5V19M5 12L12 19L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                2.5%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Chart and Recent Activity Section -->
                <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
                    <!-- Chart -->
                    <div class="col-span-2 rounded-xl border  border-gray-200 bg-white p-5 md:p-6 shadow-md dark:border-gray-700 dark:bg-PrimaryBlack">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h4 class="text-xl font-bold text-gray-800 dark:text-white">
                                    نظرسنجی‌های برتر بر اساس آرا
                                </h4>
                            </div>
                            <div class="flex">
                                <button class="text-slate-400 hover:text-slate-600 dark:text-slate-400 dark:hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Top Users Chart -->
                        <div class="h-[300px] w-full">
                            <div class="bg-gray-50 dark:bg-SecondaryBlack rounded-lg p-4 h-full">
                                {!! $topElectionsChart->render() !!}
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="col-span-1 rounded-xl px-5 py-5 border border-gray-200 bg-white shadow-default dark:border-gray-700 dark:bg-PrimaryBlack shadow-md">
                        <div class="mb-3 flex items-center justify-between">
                            <h5 class="text-xl font-semibold text-gray-800 dark:text-white">
                                فعالیت‌های اخیر
                            </h5>
                        </div>

                        <div>
                            <div class="flex items-center gap-5 py-3 border-b border-stroke dark:border-gray-700">
                                <div class="relative h-10 w-10 rounded-full">
                                    <img src="/dist/img/def-pfp.jpg" alt="User" class="h-full w-full rounded-full object-cover" />
                                </div>
                                <div>
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-white">
                                        علی احمدی نظرسنجی جدید ایجاد کرد
                                    </h6>
                                    <p class="text-xs text-gray-500">2 ساعت پیش</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-5 py-3 border-b border-stroke dark:border-gray-700">
                                <div class="relative h-10 w-10 rounded-full">
                                    <img src="/dist/img/def-pfp.jpg" alt="User" class="h-full w-full rounded-full object-cover" />
                                </div>
                                <div>
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-white">
                                        سارا محمدی در نظرسنجی شرکت کرد
                                    </h6>
                                    <p class="text-xs text-gray-500">3 ساعت پیش</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-5 py-3 border-b border-stroke dark:border-gray-700">
                                <div class="relative h-10 w-10 rounded-full">
                                    <img src="/dist/img/def-pfp.jpg" alt="User" class="h-full w-full rounded-full object-cover" />
                                </div>
                                <div>
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-white">
                                        رضا کریمی نظر جدید ارسال کرد
                                    </h6>
                                    <p class="text-xs text-gray-500">5 ساعت پیش</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-5 py-3">
                                <div class="relative h-10 w-10 rounded-full">
                                    <img src="/dist/img/def-pfp.jpg" alt="User" class="h-full w-full rounded-full object-cover" />
                                </div>
                                <div>
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-white">
                                        مریم حسینی نظرسنجی را به اشتراک گذاشت
                                    </h6>
                                    <p class="text-xs text-gray-500">7 ساعت پیش</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Users Table Section -->
                <div class="mt-6 col-span-2">
                    <div class="rounded-xl px-5 py-5 border border-gray-200 bg-white shadow-default dark:border-gray-700 dark:bg-PrimaryBlack shadow-md">
                        <div class="mb-4 flex flex-wrap items-start justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-white">نظرسنجی‌های برتر بر اساس آرا</h3>
                            </div>
                            <a href="{{ route('elections.index') }}" class="inline-flex items-center justify-center rounded-md bg-primaryColor py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">
                                مشاهده همه
                            </a>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-SecondaryBlack text-right">
                                        <th class="py-4 px-4 font-medium text-gray-600 dark:text-gray-400">عنوان نظرسنجی</th>
                                        <th class="py-4 px-4 font-medium text-gray-600 dark:text-gray-400">تعداد آرا</th>
                                        <th class="py-4 px-4 font-medium text-gray-600 dark:text-gray-400">وضعیت</th>
                                        <th class="py-4 px-4 font-medium text-gray-600 dark:text-gray-400">تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topElections as $election)
                                    <tr class="border-b border-stroke dark:border-gray-700">
                                        <td class="py-4 px-4">
                                            <p class="text-gray-800 dark:text-white">{{ $election->title }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="text-gray-800 dark:text-white">{{ $election->total_votes }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-block rounded-full bg-green-100 py-1 px-3 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-500">
                                                {{ $election->is_open ? 'فعال' : 'پایان یافته' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($election->end_date)->format('Y/m/d') }}</p>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
    </div>
</x-min-layout>
