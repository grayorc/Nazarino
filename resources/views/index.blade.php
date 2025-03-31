<!doctype html>
<html dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-primaryWhite">
    <header class="flex justify-between items-center py-4 px-20 bg-PrimaryBlack sticky top-0 z-10">
        <div class="flex justify-between gap-20" >
            <div class="text-primaryColor text-xl">
                <img src="dist/img/logo.png" class="w-10">
            </div>

            <ul class="flex space-x-reverse-6 gap-20">
                <li><a href="#" class="text-primaryWhite hover:text-primaryColor transition delay-100 duration-200">صفحه اصلی</a></li>
                <li><a href="#" class="text-primaryWhite hover:text-primaryColor transition delay-100 duration-200">قیمت‌ها</a></li>
                <li><a href="#" class="text-primaryWhite hover:text-primaryColor transition delay-100 duration-200">نمونه‌ها</a></li>
            </ul>
        </div>
        <div class="text-primaryWhite text-l">
            @auth
            <a href="/dashboard">
                <button class="bg-primaryColor rounded-md px-8 py-0.5 border-primaryColor border-2
                hover:bg-PrimaryBlack hover:border-primaryColor hover:text-primaryColor
                transition delay-100 duration-200">داشبورد</button>
            </a>
            @else
            <a href="/login">
                <button class="bg-primaryColor rounded-md px-8 py-0.5 border-primaryColor border-2
                hover:bg-PrimaryBlack hover:border-primaryColor hover:text-primaryColor
                transition delay-100 duration-200">ورود</button>
            </a>
            @endauth
        </div>
    </header>

    <main class="flex">
        <div class="text-center px-6 py-8 bg-primaryBlack max-w-lvh min-h-lvh mx-auto mt-20">
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center animate-float">
                    <img src="dist/img/main-logo.png" class="w-96">
                </div>
            </div>
            <h1 class="text-primaryBlack text-2xl font-bold mb-4">نظرسنجی آنلاین بسازید</h1>
            <p class="text-secondaryWhite mb-6">راهی آسان برای دریافت بازخورد و اطلاعات مفید از مخاطبان شما.</p>
            <div class="relative inline-flex items-center justify-center gap-4 group">
              <div
                class="absolute inset-0 duration-1000 opacity-60 transitiona-all bg-gradient-to-r from-primaryColor via-primaryColor to-primaryColor rounded-xl blur-lg filter group-hover:opacity-100 group-hover:duration-200"
              ></div>
              <a
                role="button"
                class="group relative inline-flex items-center justify-center text-base rounded-xl bg-gray-900 px-8 py-3 font-semibold text-white transition-all duration-200 hover:bg-gray-800 hover:shadow-lg hover:-translate-y-0.5 hover:shadow-gray-600/30"
                href="#"
                >
                شروع کنید!
              </a>
            </div>
        </div>
    </main>
</body>
</html>
