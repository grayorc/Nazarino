<x-min-layout>
    <div class="bg-primaryWhite dark:bg-PrimaryBlack">
        <div class="flex justify-center h-screen">
            <div class="hidden bg-cover lg:block lg:w-2/3" style="background-image: url(https://images.unsplash.com/photo-1616763355603-9755a640a287?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80)">
            </div>

            <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
                <div class="flex-1">
                    <div class="text-center">
                        <div class="flex justify-center mx-auto">
                            <img class="w-auto h-7 sm:h-8" src="/dist/img/logo.png" alt="">
                        </div>

                        <p class="mt-3 text-gray-500 dark:text-gray-300">بازیابی گذرواژه</p>
                    </div>

                    <div class="mt-8">
                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-right">
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div>
                                <label for="email" class="block mb-2 text-sm text-gray-500 dark:text-gray-200">آدرس ایمیل</label>
                                <input type="email" name="email" id="email" placeholder="example@example.com" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-primaryWhite border border-gray-200 rounded-lg dark:placeholder-gray-500 dark:bg-PrimaryBlack dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" :value="old('email')" required autofocus />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-6">
                                <button class="w-full px-4 py-2 tracking-wide text-primaryWhite transition-colors duration-300 transform bg-primaryColor rounded-lg border-primaryColor border-2 hover:bg-transparent hover:text-primaryColor focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                    {{ __('ارسال لینک بازیابی گذرواژه') }}
                                </button>
                            </div>
                        </form>

                        <p class="mt-6 text-sm text-center text-gray-400">
                            <a href="{{ route('login') }}" class="text-primaryColor focus:outline-none focus:underline hover:underline">بازگشت به صفحه ورود</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-min-layout>
