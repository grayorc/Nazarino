@if (auth()->check() && !auth()->user()->hasVerifiedEmail())
<div x-data="{
        bannerVisible: false,
        bannerVisibleAfter: 300,
    }"
    x-show="bannerVisible"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="-translate-y-10"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="-translate-y-10"
    x-init="
        setTimeout(()=>{ bannerVisible = true }, bannerVisibleAfter);
    "
    class="fixed top-0 left-0 w-full h-auto py-2 duration-300 ease-out bg-white shadow-md dark:bg-PrimaryBlack border-b border-gray-200 dark:border-gray-700 z-50" x-cloak>
    <div class="flex items-center justify-between w-full h-full px-3 mx-auto max-w-7xl">
        <div class="flex flex-col w-full h-full text-xs leading-6 text-gray-800 dark:text-white sm:flex-row sm:items-center">
            <span class="flex items-center">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-primaryColor ml-2">
                    <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C21.5523 3 22 3.44772 22 4V20.0066C22 20.5552 21.5447 21 21.0082 21H2.9918C2.44405 21 2 20.5551 2 20.0066V19H20V7.3L12 14.5L2 5.5V4C2 3.44772 2.44772 3 3 3H21ZM8 15V17H0V15H8ZM5 10V12H0V10H5ZM19.5659 5H4.43414L12 11.8093L19.5659 5Z"></path></svg>
                </div>
                <strong class="font-semibold">تایید ایمیل</strong>
                <span class="hidden w-px h-4 mx-3 rounded-full sm:block bg-gray-200 dark:bg-gray-700"></span>
            </span>
            <span class="block pt-1 pb-2 leading-none sm:inline sm:pt-0 sm:pb-0">ایمیل تایید برای شما ارسال شده است. لطفا ایمیل خود را بررسی کنید.</span>
        </div>
        <div class="flex items-center">
            <form method="POST" action="{{ route('verification.send') }}" class="ml-2">
                @csrf
                <button type="submit" class="text-xs py-1 px-3 min-w-[70px] whitespace-nowrap bg-primaryColor hover:bg-opacity-90 text-white rounded-lg">
                    ارسال مجدد
                </button>
            </form>
            <button @click="bannerVisible=false;" class="flex items-center flex-shrink-0 translate-x-1 ease-out duration-150 justify-center w-6 h-6 p-1.5 text-gray-500 dark:text-gray-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
</div>
@endif
