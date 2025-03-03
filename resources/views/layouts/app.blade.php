<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#151823">

    <title>{{ config('app.name', 'Hybon') }}</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <link rel="apple-touch-icon" sizes="16x16" href="{{ asset('pwa/icons/ios/16.png') }}">
    <link rel="apple-touch-icon" sizes="20x20" href="{{ asset('pwa/icons/ios/20.png') }}">
    <link rel="apple-touch-icon" sizes="29x29" href="{{ asset('pwa/icons/ios/29.png') }}">
    <link rel="apple-touch-icon" sizes="32x32" href="{{ asset('pwa/icons/ios/32.png') }}">
    <link rel="apple-touch-icon" sizes="40x40" href="{{ asset('pwa/icons/ios/40.png') }}">
    <link rel="apple-touch-icon" sizes="50x50" href="{{ asset('pwa/icons/ios/50.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('pwa/icons/ios/57.png') }}">
    <link rel="apple-touch-icon" sizes="58x58" href="{{ asset('pwa/icons/ios/58.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('pwa/icons/ios/60.png') }}">
    <link rel="apple-touch-icon" sizes="64x64" href="{{ asset('pwa/icons/ios/64.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('pwa/icons/ios/72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('pwa/icons/ios/76.png') }}">
    <link rel="apple-touch-icon" sizes="80x80" href="{{ asset('pwa/icons/ios/80.png') }}">
    <link rel="apple-touch-icon" sizes="87x87" href="{{ asset('pwa/icons/ios/87.png') }}">
    <link rel="apple-touch-icon" sizes="100x100" href="{{ asset('pwa/icons/ios/100.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('pwa/icons/ios/114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('pwa/icons/ios/120.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('pwa/icons/ios/128.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('pwa/icons/ios/144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('pwa/icons/ios/152.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('pwa/icons/ios/167.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('pwa/icons/ios/180.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('pwa/icons/ios/192.png') }}">
    <link rel="apple-touch-icon" sizes="256x256" href="{{ asset('pwa/icons/ios/256.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('pwa/icons/ios/512.png') }}">
    <link rel="apple-touch-icon" sizes="1024x1024" href="{{ asset('pwa/icons/ios/1024.png') }}">

    <link href="{{ asset('pwa/icons/ios/1024.png') }}" sizes="1024x1024" rel="apple-touch-startup-image">
    <link href="{{ asset('pwa/icons/ios/512.png') }}" sizes="512x512" rel="apple-touch-startup-image">
    <link href="{{ asset('pwa/icons/ios/256.png') }}" sizes="256x256" rel="apple-touch-startup-image">
    <link href="{{ asset('pwa/icons/ios/192.png') }}" sizes="192x192" rel="apple-touch-startup-image">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="mainState" :class="{ dark: isDarkMode }" x-on:resize.window="handleWindowResize" x-cloak>
        <div class="dark:bg-dark-eval-0 dark:text-gray-200 min-h-screen text-gray-900 bg-gray-100">
            <!-- Sidebar -->
            <x-sidebar.sidebar />

            <!-- Page Wrapper -->
            <div class="flex flex-col min-h-screen"
                :class="{
                    'lg:ml-64': isSidebarOpen,
                    'md:ml-16': !isSidebarOpen
                }"
                style="transition-property: margin; transition-duration: 150ms;">

                <!-- Navbar -->
                <x-navbar />

                <!-- Page Heading -->
                <header>
                    <div class="sm:p-6 p-4">
                        {{ $header }}
                    </div>
                </header>

                <!-- Page Content -->
                <main class="sm:px-6 flex-1 px-4">
                    {{ $slot }}
                </main>

                <!-- Page Footer -->
                <x-footer />
            </div>
        </div>
    </div>

    @stack('modals')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"
        integrity="sha512-eYSzo+20ajZMRsjxB6L7eyqo5kuXuS2+wEbbOkpaur+sA2shQameiJiWEzCIDwJqaB0a4a6tCuEvCOBHUg3Skg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
