<x-min-layout>
<section>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1">
            <form action="{{ route('options.store',request()->route('id')) }}" method="post" class="flex flex-col mt-6 bg-SecondaryBlack rounded-lg p-5" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-row">
                    <div class="">
                        <label for="username" class="block text-sm text-gray-500 dark:text-gray-300">عنوان</label>

                        <input type="text" name="title" placeholder="عنوان نظرسنجی را بنویسید" class="block  mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300" />
                    </div>
                </div>
                <div class="flex flex-row">
                </div>
                <div class="mt-5">
                    <label for="Description" class="block text-sm text-gray-500 dark:text-gray-300">توضیحات گزینه</label>

                    <textarea name="description" placeholder="توضیحات نظرسنجی که به شرکت کنندگان نمایش داده میشود" class="block  mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-4 h-32 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300"></textarea>

                    <p class="mt-3 text-xs text-gray-400 dark:text-gray-600"></p>
                </div>
                <div class="mt-5">
                    <div>
                        <label for="file" class="block text-sm text-gray-500 dark:text-gray-300">تصویر</label>

                        <label for="dropzone-file" class="flex flex-col items-center w-full max-w-lg p-5 mx-auto mt-2 text-center bg-white border-2 border-gray-300 border-dashed cursor-pointer dark:bg-PrimaryBlack dark:border-gray-700 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500 dark:text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>

                            <h2 class="mt-1 font-medium tracking-wide text-gray-700 dark:text-gray-200">تصویر گزینه</h2>

                            <p class="mt-2 text-xs tracking-wide text-gray-500 dark:text-gray-400">آپلود تصاویر با فرمت های SVG, PNG, JPG or GIF. </p>

                            <input id="dropzone-file" name="image" type="file" class="hidden" />
                        </label>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="flex items-center justify-center w-full px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">ایجاد نظرسنجی</button>
                </div>
            </form>
        </section>
    </div>
</x-min-layout>
