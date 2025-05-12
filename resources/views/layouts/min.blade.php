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
        {!! ToastMagic::styles() !!}

        <!-- Preload critical fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Ensure CSS is loaded before JS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        </style>
    </head>
    <body class="min-h-screen bg-primaryWhite antialiased">
        {{ $slot }}
        {!! ToastMagic::scripts() !!}
        <script>
            
        </script>
    </body>
</html>
