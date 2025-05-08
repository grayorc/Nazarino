<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            اطلاعات پروفایل
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            به‌روزرسانی اطلاعات حساب کاربری و آدرس ایمیل.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <label for="avatar" class="block text-sm text-gray-500 dark:text-gray-300">تصویر پروفایل</label>
            <div class="mt-2">
                <div class="flex items-center gap-x-3">
                    <div class="relative">
                        @if($user->image)
                            <img src="{{ Storage::url($user->image->path) }}" alt="تصویر پروفایل" class="w-32 h-32 rounded-lg object-cover">
                        @else
                            <div class="w-32 h-32 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif

                        <label for="avatar" class="absolute bottom-0 right-0 p-1 bg-white dark:bg-gray-800 rounded-full cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                            <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden" />
                        </label>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <p>فرمت‌های مجاز: JPG، PNG، GIF</p>
                        <p>حداکثر حجم: 2 مگابایت</p>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </div>
        </div>

        <div>
            <label for="username" class="block text-sm text-gray-500 dark:text-gray-300">نام کاربری</label>
            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required autocomplete="username"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('username') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div>
            <label for="first_name" class="block text-sm text-gray-500 dark:text-gray-300">نام</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required autocomplete="given-name"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('first_name') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div>
            <label for="last_name" class="block text-sm text-gray-500 dark:text-gray-300">نام خانوادگی</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('last_name') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div>
            <label for="phone_number" class="block text-sm text-gray-500 dark:text-gray-300">شماره تلفن</label>
            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required autocomplete="tel"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('phone_number') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-sm text-gray-500 dark:text-gray-300">ایمیل</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('email') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        آدرس ایمیل شما تایید نشده است.

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            برای ارسال مجدد ایمیل تایید اینجا کلیک کنید.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            لینک تایید جدید به آدرس ایمیل شما ارسال شد.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="flex items-center justify-center px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
                ذخیره تغییرات
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >ذخیره شد.</p>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('avatar').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                const img = document.querySelector('[alt="تصویر پروفایل"]');
                if (img) {
                    img.src = URL.createObjectURL(file);
                } else {
                    const preview = document.createElement('img');
                    preview.src = URL.createObjectURL(file);
                    preview.alt = 'تصویر پروفایل';
                    preview.className = 'w-32 h-32 rounded-lg object-cover';
                    document.querySelector('.w-32.h-32.rounded-lg.bg-gray-200')?.replaceWith(preview);
                }
            }
        }
    </script>
</section>
