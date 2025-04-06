<x-min-layout>
<section>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1">
            <form action="{{ route('options.store',request()->route('id')) }}" method="post" class="flex flex-col mt-6 bg-SecondaryBlack rounded-lg p-5">
                @csrf
                <div class="flex flex-row">
                    <div class="">
                        <label for="username" class="block text-sm text-gray-500 dark:text-gray-300">عنوان</label>

                        <input type="text" name="title" placeholder="عنوان نظرسنجی را بنویسید" class="block  mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300" />
                    </div>
                </div>
                <div class="flex flex-row">
                </div>
                <div class="mt-5">
                    <label for="Description" class="block text-sm text-gray-500 dark:text-gray-300">توضیحات گزینه</label>

                    <textarea name="description" placeholder="توضیحات نظرسنجی که به شرکت کنندگان نمایش داده میشود" class="block  mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-4 h-32 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"></textarea>

                    <p class="mt-3 text-xs text-gray-400 dark:text-gray-600"></p>
                </div>
                <div class="mt-5">
                    <button type="submit" class="flex items-center justify-center w-full px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">ایجاد نظرسنجی</button>
                </div>
            </form>
        </section>
    </div>
</x-min-layout>
