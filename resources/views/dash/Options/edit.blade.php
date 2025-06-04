<x-min-layout>
<section>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1 mt-16">
            <div class="flex justify-between items-center mt-6">
                <h2 class="text-xl font-bold text-gray-700 dark:text-gray-300">ویرایش گزینه "{{ $option->title }}"</h2>
                <div class="flex space-x-2 rtl:space-x-reverse">
                    <a href="{{ route('options.create', $election->id) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700">
                        بازگشت به گزینه‌ها
                    </a>
                    <a href="{{ route('elections.result', $election->id) }}" class="px-4 py-2 text-sm font-medium text-white bg-primaryColor rounded-md hover:bg-opacity-80 transition">
                        مشاهده نظرسنجی
                    </a>
                </div>
            </div>

            <form action="{{ route('options.update', [$election->id, $option->id]) }}" method="post" class="flex flex-col mt-6 bg-SecondaryBlack rounded-lg p-5" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex flex-row">
                    <div class="w-full">
                        <label for="title" class="block text-sm text-gray-500 dark:text-gray-300">عنوان</label>

                        <input type="text" id="title" name="title" value="{{ $option->title }}" placeholder="عنوان گزینه را بنویسید" class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300" />
                    </div>
                </div>
                <div class="mt-5">
                    <label for="description" class="block text-sm text-gray-500 dark:text-gray-300">توضیحات گزینه</label>

                    <textarea id="description" name="description" placeholder="توضیحات گزینه که به شرکت کنندگان نمایش داده میشود" class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-4 h-32 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300">{{ $option->description }}</textarea>

                    <p class="mt-3 text-xs text-gray-400 dark:text-gray-600"></p>
                </div>

                <div class="mt-5">
                    <div>
                        <label for="dropzone-file" class="block text-sm text-gray-500 dark:text-gray-300">تصویر</label>

                        <label
                            style="background-image: url('{{ Storage::url($option->image?->path) }}');"
                            for="dropzone-file"
                            class="relative bg-cover bg-center bg-no-repeat image flex flex-col items-center w-full max-w-lg p-5 mx-auto mt-2 text-center bg-white border-2 border-gray-300 border-dashed cursor-pointer dark:bg-PrimaryBlack dark:border-gray-700 rounded-xl {{ $errors->has('image') ? 'border-red-500' : '' }}">

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/40 rounded-xl"></div>

                            <!-- Content -->
                            <div class="relative z-10 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500 dark:text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>

                                <h2 class="mt-1 font-medium tracking-wide text-gray-700 dark:text-gray-200">تصویر گزینه</h2>

                                <p class="mt-2 text-xs tracking-wide text-gray-500 dark:text-gray-400">آپلود تصاویر با فرمت های SVG, PNG, JPG or GIF. </p>

                                <input id="dropzone-file" name="image" type="file" class="hidden" />
                            </div>
                        </label>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-5 flex justify-between">
                    <button type="submit" class="flex items-center justify-center px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">ذخیره تغییرات</button>

                    <a href="{{ route('options.create', $election->id) }}" class="flex items-center justify-center px-5 py-2 text-sm text-white transition-colors duration-200 bg-gray-500 border border-gray-500 rounded-lg gap-x-2 sm:w-auto hover:bg-gray-600">
                        انصراف
                    </a>
                </div>
            </form>
        </section>
    </div>
</x-min-layout>
