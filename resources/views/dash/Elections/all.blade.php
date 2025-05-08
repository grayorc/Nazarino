<x-min-layout>
    <div x-data="{
        dropdownOpen: false
    }"
         class="relative">
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-2 md:px-4 mx-auto flex-grow-1 w-full">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-x-3">
{{--                        <h2 class="text-lg font-medium text-gray-800 dark:text-white">Customers</h2>--}}

                        <span class="px-3 py-1 text-xs text-primaryColor bg-blue-100 rounded-full dark:bg-SecondaryBlack dark:text-blue-400">{{ auth()->user()->elections()->count() }} نظرسنجی ساخته شده</span>
                    </div>

{{--                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">این نظرسنجی ها از قبل ایجاد شده اند</p>--}}
                </div>

                <div class="flex flex-col sm:flex-row items-center mt-4 gap-y-2 sm:gap-y-0 gap-x-3">
                    <button class="flex items-center justify-center w-full sm:w-auto px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_3098_154395)">
                                <path d="M13.3333 13.3332L9.99997 9.9999M9.99997 9.9999L6.66663 13.3332M9.99997 9.9999V17.4999M16.9916 15.3249C17.8044 14.8818 18.4465 14.1806 18.8165 13.3321C19.1866 12.4835 19.2635 11.5359 19.0351 10.6388C18.8068 9.7417 18.2862 8.94616 17.5555 8.37778C16.8248 7.80939 15.9257 7.50052 15 7.4999H13.95C13.6977 6.52427 13.2276 5.61852 12.5749 4.85073C11.9222 4.08295 11.104 3.47311 10.1817 3.06708C9.25943 2.66104 8.25709 2.46937 7.25006 2.50647C6.24304 2.54358 5.25752 2.80849 4.36761 3.28129C3.47771 3.7541 2.70656 4.42249 2.11215 5.23622C1.51774 6.04996 1.11554 6.98785 0.935783 7.9794C0.756025 8.97095 0.803388 9.99035 1.07431 10.961C1.34523 11.9316 1.83267 12.8281 2.49997 13.5832" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_3098_154395">
                                    <rect width="20" height="20" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>

                        <span>خروجی</span>
                    </button>
                    <a href="{{ route('elections.create') }}" class="w-full sm:w-auto">
                        <button class="flex items-center justify-center w-full px-5 py-2 text-sm tracking-wide text-white transition-colors border-2 border-primaryColor duration-200 bg-primaryColor rounded-lg shrink-0 gap-x-2 hover:bg-primaryColor dark:hover:bg-transparent dark:hover:text-primaryColor dark:bg-primaryColor">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <span>ایجاد نظرسنجی</span>
                        </button>
                    </a>
                </div>
            </div>

            <div class="mt-6 md:flex md:items-center md:justify-between">
                <div class="flex flex-wrap gap-2 overflow-x-auto bg-white border divide-x rounded-lg dark:bg-PrimaryBlack rtl:flex-row-reverse dark:border-gray-700 dark:divide-gray-700">
                    <a hx-boost="true" href="{{ route('elections.index')}}">
                        <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 {{ request('filter') == 'all' ? 'bg-gray-100 dark:bg-SecondaryBlack' : '' }} sm:text-sm dark:text-gray-300 hover:bg-gray-100">
                            همه
                        </button>
                    </a>

                    <a hx-boost="true" href="{{ route('elections.index', array_merge(request()->except(['filter']), ['filter' => 'visible'])) }}">
                        <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 {{ request('filter') == 'visible' ? 'bg-gray-100 dark:bg-SecondaryBlack' : '' }} sm:text-sm dark:text-gray-300 hover:bg-gray-100">
                            عمومی
                        </button>
                    </a>

                    <a hx-boost="true" href="{{ route('elections.index', array_merge(request()->except('filter'), ['filter' => 'hidden'])) }}">
                        <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 {{ request('filter') == 'hidden' ? 'bg-gray-100 dark:bg-SecondaryBlack' : '' }} sm:text-sm dark:text-gray-300 hover:bg-gray-100">
                            خصوصی
                        </button>
                    </a>
                    <a hx-boost="true" href="{{ route('elections.index', array_merge(request()->except('status'), ['status' => 'open'])) }}">
                        <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 {{ request('status') == 'open' ? 'bg-gray-100 dark:bg-SecondaryBlack' : '' }} sm:text-sm dark:text-gray-300 hover:bg-gray-100">
                            باز
                        </button>
                    </a>
                    <a hx-boost="true" href="{{ route('elections.index', array_merge(request()->except('status'), ['status' => 'closed'])) }}">
                        <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 {{ request('status') == 'closed' ? 'bg-gray-100 dark:bg-SecondaryBlack' : '' }} sm:text-sm dark:text-gray-300 hover:bg-gray-100">
                            بسته
                        </button>
                    </a>
                </div>

                <div class="relative flex items-center mt-4 md:mt-0">
                    <span class="absolute right-3 rtl:left-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 dark:text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <form
                        hx-get="{{ route('elections.index') }}"
                        hx-target="#table-section"
                        hx-swap="outerHTML"
                        hx-trigger="change, keyup[target.value.length > 2] delay:500ms from:input"
                        class="w-full"
                    >
                        <input type="text" name="search" placeholder="جست و جو" class="block w-full py-1.5 pr-10 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 rtl:pl-10 dark:bg-PrimaryBlack dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    </form>
                </div>
            </div>

            <div class="flex flex-col mt-6">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    @fragment('table-container')
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8" id="table-container">
    {{--                    --}}{{--         if empty           --}}
                        @if($elections->isEmpty())
                            <div class="flex items-center mt-6 text-center border rounded-lg h-96 dark:border-gray-700">
                            <div class="flex flex-col w-full max-w-sm px-4 mx-auto">
                                <div class="p-3 mx-auto text-primaryColor bg-fadedPrimaryColor rounded-full dark:bg-fadedPrimaryColor">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <h1 class="mt-3 text-lg text-gray-800 dark:text-white">هیچ نظرسنجی یافت نشد</h1>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">شما هیچ نظرسنجی ایجاد نکردید. لطفاً دوباره امتحان کنید یا یک نظرسنجی جدید ایجاد کنید.</p>
                                <div class="flex items-center mt-4 sm:mx-auto gap-x-3">
                                    <a href="{{ route('elections.create') }}">
                                    <button class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-primaryColor rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-primaryColor dark:hover:bg-primaryColor dark:bg-primaryColor">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                        <span>ایجاد نظرسنجی</span>
                                    </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @else
                        {{--                    --}}
                        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-SecondaryBlack">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <button class="flex items-center gap-x-3 focus:outline-none">
                                                <span>نظرسنجی</span>

                                                <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                    <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                    <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                                </svg>
                                            </button>
                                        </th>

                                        <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <span>وضعیت</span>
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">کاربران شرکت کرده</th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">تا پایان نظرسنجی</th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">تاریح ایجاد</th>

                                        <th scope="col" class="relative py-3.5 px-4">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    @fragment('table-section')
                                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-PrimaryBlack" id="table-section">
                                    @foreach ($elections as $election)
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium">
                                            <div>
                                                <a href="{{route('elections.result', $election->id)}}">
                                                    <h2 class="font-medium text-gray-800 dark:text-white break-words">{{ $election->title }}</h2>
                                                </a>
                                                <p class="text-sm font-normal text-gray-600 dark:text-gray-400 break-words">{{ $election->description }}</p>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="inline px-3 py-1 text-sm font-normal rounded-full {{ $election->is_open ? 'text-emerald-500' : 'text-red-500' }}  gap-x-2 bg-emerald-100/60 dark:bg-SecondaryBlack">
                                                {{ $election->is_open ? 'باز' : 'بسته' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-gray-700 shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                                <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-gray-700 shrink-0" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                                <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-gray-700 shrink-0" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1256&q=80" alt="">
                                                <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-gray-700 shrink-0" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                                <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-primaryColor bg-blue-100 border-2 border-white rounded-full">{{ $election->userCount() }}+</p>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-sm whitespace-nowrap text-white">
    {{--                                        <div class="w-48 h-1.5 bg-blue-200 overflow-hidden rounded-full">--}}
    {{--                                            <div class="bg-primaryColor w-1/6 h-1.5"></div>--}}
    {{--                                        </div>--}}
                                            @if($election->end_date == null)
                                                تاریخ پایان مشخص نشده
                                            @elseif($election->end_date != null && $election->end_date > now())
                                                {{ verta($election->end_date)->formatDifference() }}
                                            @elseif($election->end_date < now())
                                                پایان یافته
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-gray-800 dark:text-white ">{{ verta($election->created_at)->format("Y/m/d") }}</h2>
                                            </div>
                                        </td>

                                        <td class="relative px-4 py-4 text-sm whitespace-nowrap">
                                            <button class="px-1 py-1 text-gray-500 transition-colors duration-200 rounded-lg dark:text-gray-300 hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>

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
                    صفحه <span class="font-medium text-gray-700 dark:text-gray-100">{{ $elections->currentPage() }} از {{ $elections->lastPage() }}</span>
                </div>

                <div class="flex items-center gap-x-4">
                    <a href="{{ $elections->previousPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $elections->onFirstPage()?"pointer-events-none opacity-40":""}}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>

                        <span>
                        صفحه قبل
                    </span>
                    </a>

                    <a href="{{ $elections->nextPageUrl() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-PrimaryBlack dark:text-gray-200 dark:border-gray-700 dark:hover:bg-SecondaryBlack {{ $elections->onLastPage()?"pointer-events-none opacity-40":""}}">
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
