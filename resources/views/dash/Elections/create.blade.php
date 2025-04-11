<x-min-layout>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1">
            <form action="{{ route('elections.store') }}" method="post" class="flex flex-col mt-6 bg-SecondaryBlack rounded-lg p-5" enctype="multipart/form-data">
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
                    <label for="Description" class="block text-sm text-gray-500 dark:text-gray-300">توضیحات نظرسنجی</label>

                    <textarea name="description" placeholder="توضیحات نظرسنجی که به شرکت کنندگان نمایش داده میشود" class="block  mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-4 h-32 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"></textarea>

                    <p class="mt-3 text-xs text-gray-400 dark:text-gray-600"></p>
                </div>
                <div class="mt-5">
                    <div>
                        <label for="file" class="block text-sm text-gray-500 dark:text-gray-300">تصویر</label>

                        <label for="dropzone-file" class="flex flex-col items-center w-full max-w-lg p-5 mx-auto mt-2 text-center bg-white border-2 border-gray-300 border-dashed cursor-pointer dark:bg-gray-900 dark:border-gray-700 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500 dark:text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>

                            <h2 class="mt-1 font-medium tracking-wide text-gray-700 dark:text-gray-200">تصویر نظرسنجی</h2>

                            <p class="mt-2 text-xs tracking-wide text-gray-500 dark:text-gray-400">آپلود تصاویر با فرمت های SVG, PNG, JPG or GIF. </p>

                            <input id="dropzone-file" name="image" type="file" class="hidden" />
                        </label>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="flex flex-row items-center space-x-4">
                        <div class="flex">
                            <label for="date-check" class="text-sm text-gray-500 dark:text-gray-300">پایان در تاریخ مشخص</label>
                            <input type="checkbox" name="date-check" id="date-check" class="ml-2 rounded-md" value="">
                        </div>
                        <div>
                            <input type="date" placeholder="John Doe" name="end_date"
                                   class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300">
                        </div>


                    </div>
                </div>
                <div class="mt-5">
                    <div class="flex ml-auto">
                        <label for="date-check" class="text-sm text-gray-500 dark:text-gray-300">امکان درج کامنت</label>
                        <input type="checkbox" name="comment" class="ml-2 rounded-md" value="">
                    </div>
                </div>

                <div class="mt-5">
                    <div class="flex ml-auto">
                        <label for="date-check" class="text-sm text-gray-500 dark:text-gray-300">امکان پس گرفتن رای</label>
                        <input type="checkbox" name="revote" class="ml-2 rounded-md" value="">
                    </div>
                </div>

                <div class="mt-5">
                    <div class="flex ml-auto">
                        <label for="date-check" class="text-sm text-gray-500 dark:text-gray-300">امکان انتخاب چندگزینه ای</label>
                        <input type="checkbox" name="multivote" class="ml-2 rounded-md" value="">
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="flex items-center justify-center w-full px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">ایجاد نظرسنجی</button>
                </div>
            </form>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('date-check');
            const dateInput = document.querySelector('input[type="date"]');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    dateInput.disabled = false;
                    dateInput.style.cursor = 'not-allowed';
                } else {
                    dateInput.disabled = true;
                    dateInput.style.cursor = 'auto';
                }
            });
        });

        const newOptionButton = document.querySelector('#new-option-button');
        const optionContainer = document.querySelector('#option-container');

        newOptionButton.addEventListener('click', function() {
            const newOption = document.createElement('div');
            newOption.classList.add('option');

            const optionNumber = document.querySelector('.option-number');
            optionNumber.textContent = optionNumber.textContent + 1;

            newOption.innerHTML = `
                <input type="text" placeholder="گزینه" class="block  mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300">
            `;
        });

    </script>
</x-min-layout>
