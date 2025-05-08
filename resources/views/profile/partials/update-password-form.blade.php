<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            تغییر رمز عبور
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            برای حفظ امنیت حساب کاربری خود، از یک رمز عبور طولانی و تصادفی استفاده کنید.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-sm text-gray-500 dark:text-gray-300">رمز عبور فعلی</label>
            <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('current_password') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm text-gray-500 dark:text-gray-300">رمز عبور جدید</label>
            <input type="password" name="password" id="password" autocomplete="new-password"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('password') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm text-gray-500 dark:text-gray-300">تکرار رمز عبور جدید</label>
            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->has('password_confirmation') ? 'border-red-500 focus:border-red-500' : '' }}" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="flex items-center justify-center px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
                ذخیره تغییرات
            </button>

            @if (session('status') === 'password-updated')
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
</section>
