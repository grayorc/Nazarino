<x-min-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')

        <main class="flex-1 flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-4xl">
                <!-- Header Section -->
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-primaryColor dark:text-primaryColor mb-2">تأیید سفارش</h1>
                    <p class="text-gray-600 dark:text-gray-400">لطفاً جزئیات سفارش خود را بررسی کنید</p>
                </div>

                <div class="bg-white dark:bg-SecondaryBlack rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <!-- Plan Details Section -->
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $subscriptionTier->title }}</h2>
                                    <p class="mt-1 text-gray-500 dark:text-gray-400">اشتراک ویژه</p>
                                </div>
                                <div class="text-left">
                                    <div class="bg-fadedPrimaryColor dark:bg-fadedPrimaryColor/20 rounded-lg px-4 py-2">
                                        <span class="text-3xl font-bold text-primaryColor dark:text-primaryColor">{{ number_format($subscriptionTier->price, 0) }}</span>
                                        <span class="text-xl text-gray-600 dark:text-gray-400 mr-1">تومان</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Features List -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">ویژگی‌های اشتراک شما:</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($subscriptionTier->subFeatures as $feature)
                                        <div class="flex items-center gap-3 bg-gray-50 dark:bg-SecondaryBlack rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                                            <div class="flex-shrink-0">
                                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 dark:text-gray-300">{{ $feature->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary Section -->
                    <div class="p-8 bg-gray-50 dark:bg-PrimaryBlack border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">جزئیات پرداخت</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-white dark:bg-SecondaryBlack rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">مبلغ اشتراک</span>
                                <span class="text-gray-900 dark:text-primaryColor font-medium">{{ number_format($subscriptionTier->price, 0) }} تومان</span>
                            </div>
                            <div class="flex justify-between items-center bg-white dark:bg-SecondaryBlack rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">مالیات</span>
                                <span class="text-gray-900 dark:text-gray-300 font-medium">0 تومان</span>
                            </div>

                            <div class="mt-6 pt-6 border-t-2 border-dashed border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center bg-fadedPrimaryColor/10 dark:bg-fadedPrimaryColor/5 p-4 rounded-xl">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">مبلغ قابل پرداخت</span>
                                    <div class="text-left">
                                        <span class="text-2xl font-bold text-primaryColor dark:text-primaryColor">{{ number_format($subscriptionTier->price, 0) }}</span>
                                        <span class="text-lg text-gray-600 dark:text-gray-400 mr-1">تومان</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Button -->
                        <div class="mt-8">
                            <form action="{{ route('purchase.payment-process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subscription_tier_id" value="{{ $subscriptionTier->id }}">
                            <button type="submit" class="w-full bg-primaryColor hover:bg-primaryColor/90 text-white font-semibold py-4 px-6 rounded-xl transition duration-150 ease-in-out flex items-center justify-center gap-3 text-lg shadow-lg shadow-primaryColor/20 border-2 border-primaryColor dark:hover:bg-transparent dark:hover:text-primaryColor">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                پرداخت و فعال‌سازی اشتراک
                            </button>
                            </form>
                        </div>

                        <!-- Security Notice -->
                        <div class="mt-6 flex items-center justify-center gap-3 p-4 bg-white dark:bg-SecondaryBlack rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-primaryColor dark:text-primaryColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-300">پرداخت امن با درگاه بانکی معتبر</span>
                        </div>
                    </div>
                </div>

                <!-- Support Section -->
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-SecondaryBlack px-4 py-2 rounded-full shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primaryColor dark:hover:border-primaryColor transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        نیاز به راهنمایی دارید؟
                        <a href="#" class="text-primary-600 hover:text-primary-500 font-medium">تماس با پشتیبانی</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-min-layout>
