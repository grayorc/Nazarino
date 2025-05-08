<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            حذف حساب کاربری
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            پس از حذف حساب کاربری، تمام منابع و داده‌های آن به طور دائمی حذف خواهند شد. قبل از حذف حساب کاربری خود، لطفاً هر گونه اطلاعاتی را که می‌خواهید حفظ کنید، دانلود کنید.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="flex items-center justify-center px-5 py-2 text-sm text-white transition-colors duration-200 bg-red-500 border border-red-500 rounded-lg gap-x-2 sm:w-auto hover:bg-red-600">
        حذف حساب کاربری
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                آیا از حذف حساب کاربری خود اطمینان دارید؟
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                پس از حذف حساب کاربری، تمام منابع و داده‌های آن به طور دائمی حذف خواهند شد. لطفاً رمز عبور خود را وارد کنید تا حذف دائمی حساب کاربری خود را تأیید کنید.
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm text-gray-500 dark:text-gray-300">رمز عبور</label>
                <input type="password" name="password" id="password"
                    class="block mt-2 w-full placeholder-gray-400/70 dark:placeholder-gray-500 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-PrimaryBlack dark:text-gray-300 dark:focus:border-blue-300 {{ $errors->userDeletion->has('password') ? 'border-red-500 focus:border-red-500' : '' }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <button x-on:click="$dispatch('close')" type="button"
                    class="flex items-center justify-center px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-SecondaryBlack dark:bg-PrimaryBlack hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700 ml-3">
                    انصراف
                </button>

                <button type="submit"
                    class="flex items-center justify-center px-5 py-2 text-sm text-white transition-colors duration-200 bg-red-500 border border-red-500 rounded-lg gap-x-2 sm:w-auto hover:bg-red-600">
                    حذف حساب کاربری
                </button>
            </div>
        </form>
    </x-modal>
</section>
