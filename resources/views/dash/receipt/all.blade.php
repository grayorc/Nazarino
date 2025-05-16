<x-min-layout>
    <div x-data="{
        dropdownOpen: false
    }"
         class="relative">
        @include('dash.layouts.sidebar')
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        <section class="container px-2 md:px-4 mx-auto flex-grow-1 w-full">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-x-3">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white">صورتحساب‌ها</h2>
                        <span class="px-3 py-1 text-xs text-primaryColor bg-blue-100 rounded-full dark:bg-SecondaryBlack dark:text-blue-400">{{ auth()->user()->receipts()?->count() }} صورتحساب</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">تاریخچه پرداخت‌های شما</p>
                </div>
            </div>

            <div class="flex flex-col mt-6">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    @fragment('table-container')
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8" id="table-container">
                        @if($receipts->isEmpty())
                            <div class="flex items-center mt-6 text-center border rounded-lg h-96 dark:border-gray-700">
                            <div class="flex flex-col w-full max-w-sm px-4 mx-auto">
                                <div class="p-3 mx-auto text-primaryColor bg-fadedPrimaryColor rounded-full dark:bg-fadedPrimaryColor">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <h1 class="mt-3 text-lg text-gray-800 dark:text-white">هیچ صورت حسابی یافت نشد</h1>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">تاریخچه پرداخت‌های شما اینجا نمایش داده خواهد شد</p>
                            </div>
                        </div>
                        @else
                        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-SecondaryBlack">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-x-3">
                                                <span>شماره صورتحساب</span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <span>مبلغ</span>
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <span>وضعیت</span>
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <span>روش پرداخت</span>
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <span>تاریخ پرداخت</span>
                                        </th>


                                    </tr>
                                    </thead>
                                    @fragment('table-section')
                                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-PrimaryBlack" id="table-section">
                                    @foreach ($receipts as $receipt)
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium">
                                            <div>
                                                <h2 class="font-medium text-gray-800 dark:text-white break-words">{{ $receipt->receipt_number }}</h2>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-gray-800 dark:text-white">{{ number_format($receipt->amount) }} {{ $receipt->currency }}</h2>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="inline px-3 py-1 text-sm font-normal rounded-full {{ $receipt->status === 'paid' ? 'text-emerald-500 bg-emerald-100/60' : 'text-red-500 bg-red-100/60' }} dark:bg-SecondaryBlack">
                                                {{ $receipt->status === 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap text-gray-700 dark:text-gray-300">
                                            {{ $receipt->payment_method }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-gray-800 dark:text-white">{{ $receipt->paid_at ? verta($receipt->paid_at)->format("Y/m/d") : '---' }}</h2>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                    </tbody>
                                    @endfragment
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endfragment
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    صفحه <span class="font-medium text-gray-700 dark:text-gray-100">{{ $receipts->currentPage() }} از {{ $receipts->lastPage() }}</span>
                </div>

                <div class="flex items-center gap-x-4">
                    <a href="{{ $receipts->previousPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $receipts->onFirstPage()?"pointer-events-none opacity-40":""}}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>

                        <span>
                        صفحه قبل
                    </span>
                    </a>

                    <a href="{{ $receipts->nextPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $receipts->onLastPage()?"pointer-events-none opacity-40":""}}">
                    <span>
                        صفحه بعد
                    </span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </div>


</x-min-layout>
