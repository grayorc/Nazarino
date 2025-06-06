<x-min-layout>
    <div class="flex items-center min-h-screen bg-secondaryWhite dark:bg-PrimaryBlack">
        <div class="container mx-auto">
            <div class="max-w-md mx-auto my-10">
                <div class="text-center">
                    <h1 class="my-3 text-3xl font-semibold text-gray-700 dark:text-gray-200">بازیابی رمز عبور</h1>
                    <p class="text-gray-500 dark:text-gray-400">لطفا رمز عبور جدید خود را وارد کنید</p>
                </div>
                <div class="m-7">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block mb-2 text-sm text-gray-500 dark:text-gray-200">آدرس ایمیل</label>
                            <input type="email" name="email" id="email" :value="old('email', $request->email)" required autofocus autocomplete="username"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-primaryWhite border border-gray-200 rounded-lg dark:placeholder-gray-500 dark:bg-PrimaryBlack dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <label for="password" class="block mb-2 text-sm text-gray-500 dark:text-gray-200">رمز عبور جدید</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-primaryWhite border border-gray-200 rounded-lg dark:placeholder-gray-500 dark:bg-PrimaryBlack dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <label for="password_confirmation" class="block mb-2 text-sm text-gray-500 dark:text-gray-200">تکرار رمز عبور جدید</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-primaryWhite border border-gray-200 rounded-lg dark:placeholder-gray-500 dark:bg-PrimaryBlack dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full px-4 py-2 tracking-wide text-primaryWhite transition-colors duration-300 transform bg-primaryColor rounded-lg border-primaryColor border-2 hover:bg-transparent hover:text-primaryColor focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                تغییر رمز عبور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-min-layout>
