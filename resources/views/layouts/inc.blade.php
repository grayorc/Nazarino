<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="scroll-smooth">
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

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Preload critical fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Ensure CSS is loaded before JS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {!! ToastMagic::styles() !!}

        <!-- Force responsive behavior -->
        <style>
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
    <body class="min-h-screen bg-primaryWhite">

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
                            <a href="#" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">صفحه اصلی</a>
                            <a href="#featurs" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">ویژگی ها</a>
                            <a href="#prices" class="text-primaryWhite hover:text-primaryColor transition-colors duration-200">قیمت‌ها</a>
                        </div>
                    </nav>

                    <!-- Auth Buttons - Desktop -->
                    <div class="hidden sm:block shrink-0">
                        @auth
                            <a href="{{ route('dashboard') }}">
                                <button class="bg-primaryColor text-white rounded-md px-6 py-2 text-sm font-medium
                                    border-2 border-primaryColor hover:bg-transparent hover:text-primaryColor
                                    transition-all duration-200">
                                    داشبورد
                                </button>
                            </a>
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

        <footer class="bg-PrimaryBlack">
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
