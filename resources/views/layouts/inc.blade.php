<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @notifyCss
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-primaryWhite">

        <header class="flex justify-between items-center py-4 px-5 sm:px-20 bg-PrimaryBlack sticky top-0 z-10">
            <div class="flex justify-between items-center w-full">
            <!-- Logo -->
            <a href="{{ route('index') }}">
                <div class="text-primaryColor text-xl">
                    <img src="dist/img/logo.png" class="w-10" alt="Logo">
                </div>
            </a>
            <!-- Hamburger Menu -->
            <div class="sm:hidden">
                <button id="menu-toggle" class="text-primaryWhite focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <ul id="menu" class="hidden sm:flex flex-row sm:flex-row space-y-4 sm:space-y-0 sm:space-x-reverse-6 gap-5 sm:gap-20 bg-PrimaryBlack sm:bg-transparent sm:static sm:w-auto absolute top-16 right-0 w-full px-5 rounded-md shadow-lg sm:shadow-none">
                <li><a href="#" class="text-primaryWhite hover:text-primaryColor transition delay-100 duration-200">صفحه اصلی</a></li>
                <li><a href="#featurs" class="text-primaryWhite hover:text-primaryColor transition delay-100 duration-200">ویژگی ها</a></li>
                <li><a href="#prices" class="text-primaryWhite hover:text-primaryColor transition delay-100 duration-200">قیمت‌ها</a></li>
                <!-- Authentication Buttons -->
                @auth
                <li class="lg:hidden">
                <a href="{{ route('dashboard') }}">
                    <button class="bg-primaryColor rounded-md px-8 py-0.5 border-primaryColor border-2
                    hover:bg-PrimaryBlack hover:border-primaryColor hover:text-primaryColor
                    transition delay-100 duration-200 w-full">داشبورد</button>
                </a>
                </li>
                @else
                <li class="lg:hidden">
                <a href="{{ route('login') }}">
                    <button class="bg-primaryColor rounded-md px-8 py-0.5 border-primaryColor border-2
                    hover:bg-PrimaryBlack hover:border-primaryColor hover:text-primaryColor
                    transition delay-100 duration-200 w-full">ورود</button>
                </a>
                </li>
                @endauth
            </ul>

            <!-- Authentication Buttons for Desktop -->
            <div class="hidden sm:block text-primaryWhite text-l">
                @auth
                <a href="{{ route('dashboard') }}">
                <button class="bg-primaryColor rounded-md px-8 py-0.5 border-primaryColor border-2
                hover:bg-PrimaryBlack hover:border-primaryColor hover:text-primaryColor
                transition delay-100 duration-200">داشبورد</button>
                </a>
                @else
                <a href="{{ route('login') }}">
                <button class="bg-primaryColor rounded-md px-8 py-0.5 border-primaryColor border-2
                hover:bg-PrimaryBlack hover:border-primaryColor hover:text-primaryColor
                transition delay-100 duration-200">ورود</button>
                </a>
                @endauth
            </div>
            </div>
        </header>
        <script>
            // Mobile Menu Toggle
            const menuToggle = document.getElementById('menu-toggle');
            const menu = document.getElementById('menu');

            menuToggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
            menu.classList.toggle('flex-col');
            menu.classList.toggle('gap-4');
            });
        </script>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        <footer class="bg-PrimaryBlack">
            <div class="container flex flex-col items-center justify-between p-6 mx-auto space-y-4 sm:space-y-0 sm:flex-row">
                <a href="#">
                    <img class="w-auto h-7" src="/dist/img/logo.png" alt="">
                </a>

                <p class="text-sm text-gray-300">© {{ verta()->year }}</p>

                <div class="flex -mx-2">
                    <a href="https://github.com/grayorc" class="mx-2  transition-colors duration-300 text-gray-300 hover:text-primaryColor" aria-label="Github">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.026 2C7.13295 1.99937 2.96183 5.54799 2.17842 10.3779C1.395 15.2079 4.23061 19.893 8.87302 21.439C9.37302 21.529 9.55202 21.222 9.55202 20.958C9.55202 20.721 9.54402 20.093 9.54102 19.258C6.76602 19.858 6.18002 17.92 6.18002 17.92C5.99733 17.317 5.60459 16.7993 5.07302 16.461C4.17302 15.842 5.14202 15.856 5.14202 15.856C5.78269 15.9438 6.34657 16.3235 6.66902 16.884C6.94195 17.3803 7.40177 17.747 7.94632 17.9026C8.49087 18.0583 9.07503 17.99 9.56902 17.713C9.61544 17.207 9.84055 16.7341 10.204 16.379C7.99002 16.128 5.66202 15.272 5.66202 11.449C5.64973 10.4602 6.01691 9.5043 6.68802 8.778C6.38437 7.91731 6.42013 6.97325 6.78802 6.138C6.78802 6.138 7.62502 5.869 9.53002 7.159C11.1639 6.71101 12.8882 6.71101 14.522 7.159C16.428 5.868 17.264 6.138 17.264 6.138C17.6336 6.97286 17.6694 7.91757 17.364 8.778C18.0376 9.50423 18.4045 10.4626 18.388 11.453C18.388 15.286 16.058 16.128 13.836 16.375C14.3153 16.8651 14.5612 17.5373 14.511 18.221C14.511 19.555 14.499 20.631 14.499 20.958C14.499 21.225 14.677 21.535 15.186 21.437C19.8265 19.8884 22.6591 15.203 21.874 10.3743C21.089 5.54565 16.9181 1.99888 12.026 2Z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </footer>
        <x-notify::notify />
        @notifyJs
    </body>
</html>
