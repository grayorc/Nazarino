<x-min-layout>
<section>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1 mt-16">
            <div class="flex justify-between items-center mt-6">
                <h2 class="text-xl font-bold text-gray-700 dark:text-gray-300">افزودن گزینه جدید به نظرسنجی "{{ $election->title }}"</h2>
                <div class="flex space-x-2 rtl:space-x-reverse">
                    <a href="{{ route('elections.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700">
                        همه نظرسنجی‌ها
                    </a>
                    <a href="{{ route('elections.result', $election->id) }}" class="px-4 py-2 text-sm font-medium text-white bg-primaryColor rounded-md hover:bg-opacity-80 transition">
                        مشاهده نظرسنجی
                    </a>
                </div>
            </div>

            @if($options->count() > 0)
                <div class="mt-6 bg-SecondaryBlack rounded-lg p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">گزینه‌های ایجاد شده</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">تعداد: {{ $options->count() }}</span>
                    </div>

                    <!-- Simple Options List -->
                    <div class="space-y-4">
                        @foreach($options as $i => $option)
                            <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <div class="flex justify-between items-start">
                                    <div class="flex-grow">
                                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-semibold text-white bg-primaryColor rounded-full">
                                                {{ $i + 1 }}
                                            </span>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-200">{{ $option->title }}</h4>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $option->description }}</p>
                                        <div class="mt-3">
                                            <a href="{{ route('options.edit', [$option->election_id, $option->id]) }}" class="text-xs text-primaryColor hover:text-primaryColor/80 font-medium inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 rtl:mr-1 rtl:ml-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                ویرایش گزینه
                                            </a>
                                        </div>
                                    </div>

                                    @if($option->image)
                                        <div class="ml-4 rtl:mr-4 rtl:ml-0">
                                            <img src="{{ asset('storage/' . $option->image->path) }}" alt="{{ $option->title }}"
                                                class="w-16 h-16 object-cover rounded-lg">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <form action="{{ route('options.store', request()->route('election')) }}" method="post" class="flex flex-col mt-6 bg-SecondaryBlack rounded-lg p-5" enctype="multipart/form-data">
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
                <div class="mt-5 flex justify-between">
                    <button type="submit" class="flex items-center justify-center px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">ایجاد گزینه</button>

                    @if($options->count() > 0)
                        <a href="{{ route('election.show', request()->route('election')) }}" class="flex items-center justify-center px-5 py-2 text-sm text-white transition-colors duration-200 bg-primaryColor border border-primaryColor rounded-lg gap-x-2 sm:w-auto hover:bg-opacity-90">
                            اتمام و مشاهده نظرسنجی
                        </a>
                    @endif
                </div>
            </form>
        </section>
    </div>
</x-min-layout>
