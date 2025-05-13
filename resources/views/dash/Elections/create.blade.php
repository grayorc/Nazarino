<x-min-layout>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1 pb-32 dark:bg-PrimaryBlack">
            <form action="{{ route('elections.store') }}" method="post" class="flex flex-col mt-6 bg-SecondaryBlack rounded-lg p-5" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-row">
                    <div class="w-full">
                        <label for="title" class="block text-sm text-gray-500 dark:text-gray-300">عنوان</label>

                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="عنوان نظرسنجی را بنویسید" class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('title') ? 'border-red-500 focus:border-red-500' : '' }}" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                </div>
                <div class="flex flex-row">
                </div>
                <div class="mt-5">
                    <label for="description" class="block text-sm text-gray-500 dark:text-gray-300">توضیحات نظرسنجی</label>

                    <textarea name="description" id="description" placeholder="توضیحات نظرسنجی که به شرکت کنندگان نمایش داده میشود" class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-4 h-32 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('description') ? 'border-red-500 focus:border-red-500' : '' }}">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    <p class="mt-3 text-xs text-gray-400 dark:text-gray-600"></p>
                </div>
                <div class="mt-5">
                    <div>
                        <label for="dropzone-file" class="block text-sm text-gray-500 dark:text-gray-300">تصویر</label>

                        <label for="dropzone-file" class="flex flex-col items-center w-full max-w-lg p-5 mx-auto mt-2 text-center bg-white border-2 border-gray-300 border-dashed cursor-pointer dark:bg-PrimaryBlack dark:border-gray-700 rounded-xl {{ $errors->has('image') ? 'border-red-500' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500 dark:text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>

                            <h2 class="mt-1 font-medium tracking-wide text-gray-700 dark:text-gray-200">تصویر نظرسنجی</h2>

                            <p class="mt-2 text-xs tracking-wide text-gray-500 dark:text-gray-400">آپلود تصاویر با فرمت های SVG, PNG, JPG or GIF. </p>

                            <input id="dropzone-file" name="image" type="file" class="hidden" />
                        </label>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-5">
                    <div class="flex flex-row items-center space-x-4">
                        <div class="flex flex-row items-center space-x-4">
                            <!-- The switch component -->
                            <div dir="ltr" x-data="{ switchOn: true }" class="flex items-center justify-center space-x-2">
                                <input id="has_end_date" type="checkbox" name="has_end_date" class="hidden" :checked="switchOn">

                                <button
                                    x-ref="switchButton"
                                    type="button"
                                    @click="switchOn = !switchOn; $dispatch('toggle-switch', {switchOn})"
                                    :class="switchOn ? 'bg-primaryColor' : 'bg-neutral-200'"
                                    class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
                                    x-cloak>
                                    <span :class="switchOn ? 'translate-x-[18px]' : 'translate-x-0.5'" class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                                </button>

                                <label @click="$refs.switchButton.click(); $refs.switchButton.focus()" :id="$id('switch')"
                                       :class="{ 'text-blue-600': switchOn, 'text-gray-400': !switchOn }"
                                       class="select-none text-sm text-gray-500 dark:text-gray-300"
                                       x-cloak>
                                </label>
                            </div>

                            <!-- The date picker component -->
                            <div x-data="{
                datePickerOpen: false,
                datePickerValue: '',
                datePickerFormat: 'Y/M/D',
                datePickerMonth: 0,
                datePickerYear: 0,
                datePickerDay: 0,
                datePickerDaysInMonth: [],
                datePickerBlankDaysInMonth: [],
                datePickerMonthNames: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'],
                datePickerDays: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
                isDisabled: false,

                // Convert Gregorian date to Jalali (Persian) date
                gregorianToJalali(gy, gm, gd) {
                    var g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
                    var jy = (gy <= 1600) ? 0 : 979;
                    gy -= (gy <= 1600) ? 621 : 1600;
                    var gy2 = (gm > 2) ? (gy + 1) : gy;
                    var days = (365 * gy) + (parseInt((gy2 + 3) / 4)) - (parseInt((gy2 + 99) / 100)) + (parseInt((gy2 + 399) / 400)) - 80 + gd + g_d_m[gm - 1];
                    jy += 33 * (parseInt(days / 12053));
                    days %= 12053;
                    jy += 4 * (parseInt(days / 1461));
                    days %= 1461;
                    jy += parseInt((days - 1) / 365);
                    if (days > 365) days = (days - 1) % 365;
                    var jm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
                    var jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
                    return [jy, jm, jd];
                },

                // Convert Jalali (Persian) date to Gregorian date
                jalaliToGregorian(jy, jm, jd) {
                    var gy = (jy <= 979) ? 621 : 1600;
                    jy -= (jy <= 979) ? 0 : 979;
                    var days = (365 * jy) + ((parseInt(jy / 33)) * 8) + (parseInt(((jy % 33) + 3) / 4)) + 78 + jd + ((jm < 7) ? (jm - 1) * 31 : ((jm - 7) * 30) + 186);
                    gy += 400 * (parseInt(days / 146097));
                    days %= 146097;
                    if (days > 36524) {
                        gy += 100 * (parseInt(--days / 36524));
                        days %= 36524;
                        if (days >= 365) days++;
                    }
                    gy += 4 * (parseInt(days / 1461));
                    days %= 1461;
                    gy += parseInt((days - 1) / 365);
                    if (days > 365) days = (days - 1) % 365;
                    var gm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
                    var gd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
                    return [gy, gm, gd];
                },

                // Get number of days in a given Jalali month
                getDaysInJalaliMonth(year, month) {
                    if (month <= 6) return 31;
                    if (month <= 11) return 30;
                    // Check for leap year
                    if (this.isJalaliLeapYear(year)) return 30;
                    return 29;
                },

                // Check if a Jalali year is leap year
                isJalaliLeapYear(year) {
                    return ((year % 33) % 4 === 1);
                },

                // Get day of week in Jalali calendar (0 = Saturday, 1 = Sunday, etc.)
                getJalaliDayOfWeek(jy, jm, jd) {
                    const gregorian = this.jalaliToGregorian(jy, jm, jd);
                    const date = new Date(gregorian[0], gregorian[1] - 1, gregorian[2]);
                    // Adjust for Persian calendar (week starts on Saturday)
                    return (date.getDay() + 1) % 7;
                },

                datePickerDayClicked(day) {
                    this.datePickerDay = day;
                    this.datePickerValue = this.datePickerFormatDate(this.datePickerYear, this.datePickerMonth + 1, day);
                    this.datePickerOpen = false;
                },

                datePickerPreviousMonth() {
                    if (this.datePickerMonth === 0) {
                        this.datePickerYear--;
                        this.datePickerMonth = 11;
                    } else {
                        this.datePickerMonth--;
                    }
                    this.datePickerCalculateDays();
                },

                datePickerNextMonth() {
                    if (this.datePickerMonth === 11) {
                        this.datePickerMonth = 0;
                        this.datePickerYear++;
                    } else {
                        this.datePickerMonth++;
                    }
                    this.datePickerCalculateDays();
                },

                datePickerIsSelectedDate(day) {
                    const formattedDate = this.datePickerFormatDate(this.datePickerYear, this.datePickerMonth + 1, day);
                    return this.datePickerValue === formattedDate;
                },

                datePickerIsToday(day) {
                    // Use server-side provided values for today
                    return {{ verta()->year }} === this.datePickerYear &&
                           {{ verta()->month }} === (this.datePickerMonth + 1) &&
                           {{ verta()->day }} === day;
                },

                datePickerCalculateDays() {
                    let daysInMonth = this.getDaysInJalaliMonth(this.datePickerYear, this.datePickerMonth + 1);

                    // Find where to start calendar day of week (in Persian calendar week starts with Saturday)
                    let dayOfWeek = this.getJalaliDayOfWeek(this.datePickerYear, this.datePickerMonth + 1, 1);

                    let blankdaysArray = [];
                    for (var i = 0; i < dayOfWeek; i++) {
                        blankdaysArray.push(i);
                    }

                    let daysArray = [];
                    for (var i = 1; i <= daysInMonth; i++) {
                        daysArray.push(i);
                    }

                    this.datePickerBlankDaysInMonth = blankdaysArray;
                    this.datePickerDaysInMonth = daysArray;
                },

                datePickerFormatDate(year, month, day) {
                    let formattedMonthInNumber = ('0' + month).slice(-2);
                    let formattedDay = ('0' + day).slice(-2);

                    return `${year}/${formattedMonthInNumber}/${formattedDay}`;
                },

                initCalendar() {
                    // Use server-side provided values for initialization
                    this.datePickerYear = {{ verta()->year }};
                    this.datePickerMonth = {{ verta()->month - 1 }};
                    this.datePickerDay = {{ verta()->day }};
                    this.datePickerValue = this.datePickerFormatDate(this.datePickerYear, this.datePickerMonth + 1, this.datePickerDay);
                    this.datePickerCalculateDays();

                    // Listen for switch toggle events
                    this.$watch('$parent.switchOn', (value) => {
                        this.isDisabled = value;
                        if (value) {
                            this.datePickerOpen = false;
                        }
                    });
                }
            }"
                                 x-init="initCalendar()"
                                 @toggle-switch.window="isDisabled = !$event.detail.switchOn"
                                 x-cloak>
                                <div class="container px-4 py-2 mx-auto md:py-10">
                                    <div class="w-full mb-5">
                                        <label for="datepicker" class="block mb-1 text-sm font-medium light:text-neutral-500 dark:text-gray-300">انتخاب تاریخ پایان</label>
                                        <div class="relative w-[17rem]">
                                            <input
                                                x-ref="datePickerInput"
                                                name="end_date"
                                                id="end_date"
                                                type="text"
                                                @click="if (!isDisabled) { datePickerOpen = !datePickerOpen }"
                                                x-model="datePickerValue"
                                                x-on:keydown.escape="datePickerOpen=false"
                                                :class="isDisabled ? 'opacity-50 cursor-not-allowed' : ''"
                                                class="flex focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 w-full h-10 px-3 py-2 text-sm bg-white rounded-md text-neutral-600 placeholder:text-neutral-400 {{ $errors->has('end_date') ? 'border-red-500 focus:border-red-500' : '' }}"
                                                placeholder="انتخاب تاریخ پایان"
                                                readonly
                                                :disabled="isDisabled"
                                            />

                                            <div
                                                @click="if (!isDisabled) { datePickerOpen=!datePickerOpen; if(datePickerOpen){ $refs.datePickerInput.focus() }}"
                                                :class="isDisabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:text-neutral-500'"
                                                class="absolute top-0 left-0 px-3 py-2 text-neutral-400">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div
                                                x-show="datePickerOpen && !isDisabled"
                                                x-transition
                                                @click.away="datePickerOpen = false"
                                                class="absolute top-0 right-0 z-50 max-w-lg p-4 mt-12 antialiased bg-white border rounded-lg shadow w-[17rem] border-neutral-200/70">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div>
                                                        <button @click="datePickerNextMonth()" type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                                                            <svg class="inline-flex w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <span x-text="datePickerMonthNames[datePickerMonth]" class="text-lg font-bold text-gray-800"></span>
                                                        <span x-text="datePickerYear" class="mr-1 text-lg font-normal text-gray-600"></span>
                                                    </div>
                                                    <div>
                                                        <button @click="datePickerPreviousMonth()" type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                                                            <svg class="inline-flex w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-7 mb-3">
                                                    <template x-for="(day, index) in datePickerDays" :key="index">
                                                        <div class="px-0.5">
                                                            <div x-text="day" class="text-xs font-medium text-center text-gray-800"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                                <div class="grid grid-cols-7">
                                                    <template x-for="blankDay in datePickerBlankDaysInMonth">
                                                        <div class="p-1 text-sm text-center border border-transparent"></div>
                                                    </template>
                                                    <template x-for="(day, dayIndex) in datePickerDaysInMonth" :key="dayIndex">
                                                        <div class="px-0.5 mb-1 aspect-square">
                                                            <div
                                                                x-text="day"
                                                                @click="datePickerDayClicked(day)"
                                                                :class="{
                                                    'bg-neutral-200': datePickerIsToday(day) == true,
                                                    'text-gray-600 hover:bg-neutral-200': datePickerIsToday(day) == false && datePickerIsSelectedDate(day) == false,
                                                    'bg-neutral-800 text-white hover:bg-opacity-75': datePickerIsSelectedDate(day) == true
                                                }"
                                                                class="flex items-center justify-center text-sm leading-none text-center rounded-full cursor-pointer h-7 w-7">
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="flex ml-auto">
                        <div dir="ltr" x-data="{ switchOn: true }" class="flex items-center justify-center space-x-2">
                            <input id="comment" type="checkbox" name="comment" class="hidden" :checked="switchOn">

                            <button
                                x-ref="switchButton"
                                type="button"
                                @click="switchOn = ! switchOn"
                                :class="switchOn ? 'bg-primaryColor' : 'bg-neutral-200'"
                                class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
                                x-cloak>
                                <span :class="switchOn ? 'translate-x-[18px]' : 'translate-x-0.5'" class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                            </button>

                            <label @click="$refs.switchButton.click(); $refs.switchButton.focus()" :id="$id('switch')"
                                   :class="{ 'text-blue-600': switchOn, 'text-gray-400': ! switchOn }"
                                   class="select-none text-sm text-gray-500 dark:text-gray-300"
                                   x-cloak>
                                امکان درج کامنت
                            </label>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                </div>

                <div class="mt-5">
                    <div class="flex ml-auto">
                        <div dir="ltr" x-data="{ switchOn: false }" class="flex items-center justify-center space-x-2">
                            <input id="public" type="checkbox" name="public" class="hidden" :checked="switchOn">

                            <button
                                x-ref="switchButton"
                                type="button"
                                @click="switchOn = ! switchOn"
                                :class="switchOn ? 'bg-primaryColor' : 'bg-neutral-200'"
                                class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
                                x-cloak>
                                <span :class="switchOn ? 'translate-x-[18px]' : 'translate-x-0.5'" class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                            </button>

                            <label @click="$refs.switchButton.click(); $refs.switchButton.focus()" :id="$id('switch')"
                                   :class="{ 'text-blue-600': switchOn, 'text-gray-400': ! switchOn }"
                                   class="select-none text-sm text-gray-500 dark:text-gray-300"
                                   x-cloak>
                                عمومی
                            </label>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('public')" class="mt-2" />
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
                <input type="text" placeholder="گزینه" class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300">
            `;
        });
    </script>
</x-min-layout>
