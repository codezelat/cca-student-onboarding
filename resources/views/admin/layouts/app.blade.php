<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Portal | ' . config('app.name', 'CCA'))</title>
    <meta name="description" content="@yield('meta_description', 'Secure internal portal for the Codezela Career Accelerator team.')">
    <meta name="robots" content="noindex, nofollow, noimageindex">
    <meta name="theme-color" content="#8b5cf6">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', 'Admin Portal | ' . config('app.name', 'CCA'))">
    <meta property="og:description" content="@yield('og_description', 'Internal dashboard for managing Codezela Career Accelerator operations.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo-wide.png') }}">
    <meta property="og:site_name" content="{{ config('app.name', 'CCA') }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Admin Portal | ' . config('app.name', 'CCA'))">
    <meta name="twitter:description" content="@yield('twitter_description', 'Internal dashboard for managing Codezela Career Accelerator operations.')">
    <meta name="twitter:image" content="{{ asset('images/logo-wide.png') }}">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YDEF398QWX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-YDEF398QWX');
    </script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="@yield('body_class', 'font-sans antialiased')">
    @yield('body')

    <style>
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

    @stack('scripts')
</body>
</html>
