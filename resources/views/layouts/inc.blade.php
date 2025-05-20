<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="scroll-smooth dark:bg-PrimaryBlack">
    <head>
        <meta charset="utf-8">
        <!-- Ensure viewport meta is first after charset for proper mobile rendering -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no, viewport-fit=cover">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#EB5E28">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="#EB5E28">

        <title>{{ config('app.name', 'نظرینو') }}</title>

        <!-- Preload critical fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Ensure CSS is loaded before JS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {!! ToastMagic::styles() !!}

        <!-- Force responsive behavior -->
        <style>
            /* Hide Alpine elements before initializing */
            [x-cloak] { display: none !important; }

            html, body {
                overflow-x: hidden;
                width: 100%;
                -webkit-text-size-adjust: 100%;
            }
            @media screen and (max-width: 768px) {
                body {
                    position: relative;
                    min-height: -webkit-fill-available;
                }
            }
            /* Add smooth transition for menu */
            #menu {
                transition: all 0.3s ease-in-out;
            }
            /* Responsive padding adjustments */
            @media (max-width: 640px) {
                .container {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
            }
            /* Improved mobile menu animation */
            .menu-open {
                transform: translateY(0);
                opacity: 1;
            }
            .menu-closed {
                transform: translateY(-100%);
                opacity: 0;
            }

            /* Header and Navigation Styles */
            .sticky-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 50;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                transition: all 0.3s ease-in-out;
            }

            .header-shadow {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            /* Mobile Menu Animation */
            .mobile-menu {
                transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            }

            .mobile-menu.hidden {
                transform: translateY(-100%);
                opacity: 0;
            }

            .mobile-menu.visible {
                transform: translateY(0);
                opacity: 1;
            }

            /* Responsive Container */
            .responsive-container {
                width: 100%;
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 1rem;
            }

            @media (min-width: 640px) {
                .responsive-container {
                    padding: 0 2rem;
                }
            }

            @media (min-width: 1024px) {
                .responsive-container {
                    padding: 0 4rem;
                }
            }
        </style>
    </head>
    <body class="min-h-screen bg-primaryWhite dark:bg-PrimaryBlack dark:text-primaryWhite transition-colors duration-300">

        <!-- Sticky Header -->
        <header class="sticky-header bg-PrimaryBlack/95">
            <div class="responsive-container">
                <div class="flex justify-between items-center h-16 md:h-20">
                    <!-- Logo -->
                    <a href="{{ route('index') }}" class="flex items-center space-x-2 rtl:space-x-reverse shrink-0" hx-boost="true">
                        <img src="/dist/img/logo.png" class="w-8 md:w-10 h-auto" alt="Logo">
                        <span class="text-primaryColor font-semibold text-lg md:text-xl hidden sm:block">{{ config('app.name', 'Laravel') }}</span>
                    </a>

                    <!-- Desktop Navigation - Centered -->
                    <nav class="hidden sm:flex items-center justify-center flex-1 mx-4">
                        <div class="flex items-center space-x-8 rtl:space-x-reverse">
                            <a href="{{ route('index') }}#" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">صفحه اصلی</a>
                            <a href="{{ route('index') }}#featurs" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">ویژگی ها</a>
                            <a href="{{ route('index') }}#pricing" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">قیمت‌ها</a>
                            <a href="{{ route('elections.feed') }}" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">نظرسنجی های اخیر</a>
                        </div>
                    </nav>

                    <!-- Auth Buttons - Desktop -->
                    <div class="hidden sm:block shrink-0">
                        @auth
                            <div x-data="{
                                dropdownOpen: false
                            }"
                            class="relative">
                                <button @click="dropdownOpen=true" class="inline-flex items-center justify-center py-1.5 pl-2 pr-3 text-sm font-medium transition-colors bg-transparent hover:bg-gray-700 text-primaryWhite rounded-md border border-none">
                                    <img src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image->path) : '/dist/img/def-pfp.jpg' }}" class="object-cover w-7 h-7 border rounded-full border-gray-600" />
                                    <span class="flex flex-col items-start flex-shrink-0 h-full mr-2 leading-none translate-y-px">
                                        <span>{{ auth()->user()->name }}</span>
                                        <span class="text-xs font-light text-gray-300">{{ '@' . auth()->user()->username }}</span>
                                    </span>
                                    <svg class="w-4 h-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                                </button>

                                <div x-show="dropdownOpen"
                                    @click.away="dropdownOpen=false"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="-translate-y-2"
                                    x-transition:enter-end="translate-y-0"
                                    class="absolute top-0 z-50 w-56 mt-12 -translate-x-1/2 left-1/2"
                                    x-cloak>
                                    <div class="p-1 mt-1 bg-white border rounded-md shadow-md border-neutral-200/70 text-neutral-700 dark:bg-PrimaryBlack dark:border-gray-700 dark:text-gray-200">
                                        <div class="px-2 py-1.5 text-sm font-semibold dark:text-primaryWhite">حساب کاربری</div>
                                        <div class="h-px my-1 -mx-1 bg-neutral-200 dark:bg-gray-700"></div>

                                        <a href="{{ route('dashboard') }}" class="relative flex cursor-pointer select-none hover:bg-neutral-100 dark:hover:bg-Sidebar_background_hover items-center rounded px-2 py-1.5 text-sm outline-none transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17" /></svg>
                                            <span>داشبورد</span>
                                        </a>

                                        <a href="{{ route('elections.index') }}" class="relative flex cursor-pointer select-none hover:bg-neutral-100 dark:hover:bg-Sidebar_background_hover items-center rounded px-2 py-1.5 text-sm outline-none transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" /><path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" /></svg>
                                            <span>نظرسنجی های من</span>
                                        </a>

                                        <a href="{{ route('notifications.index') }}" class="relative flex cursor-pointer select-none hover:bg-neutral-100 dark:hover:bg-Sidebar_background_hover items-center rounded px-2 py-1.5 text-sm outline-none transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                            <span>اعلان‌ها</span>
                                            @if(auth()->user()->unreadNotifications->count() > 0)
                                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full mr-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                                            @endif
                                        </a>

                                        <div class="h-px my-1 -mx-1 bg-neutral-200 dark:bg-gray-700"></div>

                                        <a href="{{ route('receipts.index')}}" class="relative flex cursor-pointer select-none hover:bg-neutral-100 dark:hover:bg-Sidebar_background_hover items-center rounded px-2 py-1.5 text-sm outline-none transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12H4C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C9.53614 4 7.33243 5.11383 5.86492 6.86543L8 9H2V3L4.44656 5.44648C6.28002 3.33509 8.9841 2 12 2ZM13 7L12.9998 11.585L16.2426 14.8284L14.8284 16.2426L10.9998 12.413L11 7H13Z"></path></svg>
                                            <span>مالی</span>
                                        </a>

                                        <a href="{{ route('subscription.index')}}" class="relative flex cursor-pointer select-none hover:bg-neutral-100 dark:hover:bg-Sidebar_background_hover items-center rounded px-2 py-1.5 text-sm outline-none transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="currentColor"><path d="M12.0049 22.0027C6.48204 22.0027 2.00488 17.5256 2.00488 12.0027C2.00488 6.4799 6.48204 2.00275 12.0049 2.00275C17.5277 2.00275 22.0049 6.4799 22.0049 12.0027C22.0049 17.5256 17.5277 22.0027 12.0049 22.0027ZM12.0049 20.0027C16.4232 20.0027 20.0049 16.421 20.0049 12.0027C20.0049 7.58447 16.4232 4.00275 12.0049 4.00275C7.5866 4.00275 4.00488 7.58447 4.00488 12.0027C4.00488 16.421 7.5866 20.0027 12.0049 20.0027ZM8.50488 14.0027H14.0049C14.281 14.0027 14.5049 13.7789 14.5049 13.5027C14.5049 13.2266 14.281 13.0027 14.0049 13.0027H10.0049C8.62417 13.0027 7.50488 11.8835 7.50488 10.5027C7.50488 9.12203 8.62417 8.00275 10.0049 8.00275H11.0049V6.00275H13.0049V8.00275H15.5049V10.0027H10.0049C9.72874 10.0027 9.50488 10.2266 9.50488 10.5027C9.50488 10.7789 9.72874 11.0027 10.0049 11.0027H14.0049C15.3856 11.0027 16.5049 12.122 16.5049 13.5027C16.5049 14.8835 15.3856 16.0027 14.0049 16.0027H13.0049V18.0027H11.0049V16.0027H8.50488V14.0027Z"></path></svg>
                                            <span>اشتراک</span>
                                        </a>

                                        <a href="{{ route('profile.update')}}" class="relative flex cursor-pointer select-none hover:bg-neutral-100 dark:hover:bg-Sidebar_background_hover items-center rounded px-2 py-1.5 text-sm outline-none transition-colors">
                                            <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.3246 4.31731C10.751 2.5609 13.249 2.5609 13.6754 4.31731C13.9508 5.45193 15.2507 5.99038 16.2478 5.38285C17.7913 4.44239 19.5576 6.2087 18.6172 7.75218C18.0096 8.74925 18.5481 10.0492 19.6827 10.3246C21.4391 10.751 21.4391 13.249 19.6827 13.6754C18.5481 13.9508 18.0096 15.2507 18.6172 16.2478C19.5576 17.7913 17.7913 19.5576 16.2478 18.6172C15.2507 18.0096 13.9508 18.5481 13.6754 19.6827C13.249 21.4391 10.751 21.4391 10.3246 19.6827C10.0492 18.5481 8.74926 18.0096 7.75219 18.6172C6.2087 19.5576 4.44239 17.7913 5.38285 16.2478C5.99038 15.2507 5.45193 13.9508 4.31731 13.6754C2.5609 13.249 2.5609 10.751 4.31731 10.3246C5.45193 10.0492 5.99037 8.74926 5.38285 7.75218C4.44239 6.2087 6.2087 4.44239 7.75219 5.38285C8.74926 5.99037 10.0492 5.45193 10.3246 4.31731Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                            <span>تنظیمات</span>
                                        </a>

                                        <div class="h-px my-1 -mx-1 bg-neutral-200 dark:bg-gray-700"></div>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full relative flex cursor-pointer select-none hover:bg-neutral-100 dark:hover:bg-Sidebar_background_hover items-center rounded px-2 py-1.5 text-sm outline-none transition-colors text-right">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                                                <span>خروج</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}">
                                <button class="bg-primaryColor text-white rounded-md px-6 py-2 text-sm font-medium
                                    border-2 border-primaryColor hover:bg-transparent hover:text-primaryColor
                                    transition-all duration-200">
                                    ورود
                                </button>
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="menu-toggle" class="sm:hidden p-2 text-primaryWhite focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="mobile-menu hidden sm:hidden bg-PrimaryBlack/95 absolute left-0 right-0 p-4 border-t border-gray-700">
                    <nav class="flex flex-col space-y-4">
                        <a href="#" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">صفحه اصلی</a>
                        <a href="#featurs" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">ویژگی ها</a>
                        <a href="#prices" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">قیمت‌ها</a>

                        @auth
                            <a href="{{ route('dashboard') }}" class="block">
                                <button class="w-full bg-primaryColor text-white rounded-md px-4 py-2 text-sm font-medium
                                    border-2 border-primaryColor hover:bg-transparent hover:text-primaryColor
                                    transition-all duration-200">
                                    داشبورد
                                </button>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block">
                                <button class="w-full bg-primaryColor text-white rounded-md px-4 py-2 text-sm font-medium
                                    border-2 border-primaryColor hover:bg-transparent hover:text-primaryColor
                                    transition-all duration-200">
                                    ورود
                                </button>
                            </a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Add margin to prevent content from being hidden under fixed header -->
        <div class="pt-16 md:pt-20">
            <!-- Page Content -->
            <main class="min-h-screen">
                {{ $slot }}
            </main>
        </div>

        <footer class="bg-PrimaryBlack mt-28">
            <div class="container flex flex-col items-center justify-between p-4 md:p-6 mx-auto space-y-4 sm:space-y-0 sm:flex-row max-w-7xl">
                <a href="#" class="mb-4 sm:mb-0">
                    <img class="w-auto h-6 md:h-7" src="/dist/img/logo.png" alt="">
                </a>

                <p class="text-xs md:text-sm text-gray-300">© {{ verta()->year }}</p>

                <div class="flex -mx-2">
                    <a href="https://github.com/grayorc" class="mx-2 transition-colors duration-300 text-gray-300 hover:text-primaryColor" aria-label="Github">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.026 2C7.13295 1.99937 2.96183 5.54799 2.17842 10.3779C1.395 15.2079 4.23061 19.893 8.87302 21.439C9.37302 21.529 9.55202 21.222 9.55202 20.958C9.55202 20.721 9.54402 20.093 9.54102 19.258C6.76602 19.858 6.18002 17.92 6.18002 17.92C5.99733 17.317 5.60459 16.7993 5.07302 16.461C4.17302 15.842 5.14202 15.856 5.14202 15.856C5.78269 15.9438 6.34657 16.3235 6.66902 16.884C6.94195 17.3803 7.40177 17.747 7.94632 17.9026C8.49087 18.0583 9.07503 17.99 9.56902 17.713C9.61544 17.207 9.84055 16.7341 10.204 16.379C7.99002 16.128 5.66202 15.272 5.66202 11.449C5.64973 10.4602 6.01691 9.5043 6.68802 8.778C6.38437 7.91731 6.42013 6.97325 6.78802 6.138C6.78802 6.138 7.62502 5.869 9.53002 7.159C11.1639 6.71101 12.8882 6.71101 14.522 7.159C16.428 5.868 17.264 6.138 17.264 6.138C17.6336 6.97286 17.6694 7.91757 17.364 8.778C18.0376 9.50423 18.4045 10.4626 18.388 11.453C18.388 15.286 16.058 16.128 13.836 16.375C14.3153 16.8651 14.5612 17.5373 14.511 18.221C14.511 19.555 14.499 20.631 14.499 20.958C14.499 21.225 14.677 21.535 15.186 21.437C19.8265 19.8884 22.6591 15.203 21.874 10.3743C21.089 5.54565 16.9181 1.99888 12.026 2Z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </footer>

        {!! ToastMagic::scripts() !!}

        <script>
            // Header Scroll Effect
            const header = document.querySelector('.sticky-header');
            let lastScroll = 0;

            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;

                // Add shadow when scrolling down
                if (currentScroll > 0) {
                    header.classList.add('header-shadow');
                } else {
                    header.classList.remove('header-shadow');
                }

                lastScroll = currentScroll;
            });

            // Mobile Menu Toggle
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            let isMenuOpen = false;

            menuToggle.addEventListener('click', () => {
                isMenuOpen = !isMenuOpen;

                if (isMenuOpen) {
                    mobileMenu.classList.remove('hidden');
                    // Force a reflow
                    mobileMenu.offsetHeight;
                    mobileMenu.classList.add('visible');
                } else {
                    mobileMenu.classList.remove('visible');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300); // Match the transition duration
                }
            });

            // Close mobile menu on window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 640 && isMenuOpen) {
                    mobileMenu.classList.remove('visible');
                    mobileMenu.classList.add('hidden');
                    isMenuOpen = false;
                }
            });
        </script>
    </body>
</html>
