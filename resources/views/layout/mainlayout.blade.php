<!DOCTYPE html>
@php
    $route = Route::currentRouteName();
@endphp

@if (!Route::is(['layout-horizontal','layout-detached','layout-modern','layout-two-column','layout-hovered','layout-boxed','layout-rtl','layout-dark']))
<html lang="en" data-theme="dark">
@endif
@if (Route::is(['layout-horizontal']))
<html lang="en" data-layout="horizontal">
@endif
@if (Route::is(['layout-detached']))
<html lang="en" data-layout="detached">
@endif
@if (Route::is(['layout-modern']))
<html lang="en" data-layout="modern">
@endif
@if (Route::is(['layout-two-column']))
<html lang="en" data-layout="twocolumn">
@endif
@if (Route::is(['layout-hovered']))
<html lang="en" data-layout="layout-hovered">
@endif
@if (Route::is(['layout-boxed']))
<html lang="en" data-layout="default" data-width="box">
@endif
@if (Route::is(['layout-rtl']))
<html lang="en" data-layout-mode="light_mode">
@endif
@if (Route::is(['layout-dark']))
<html lang="en" data-theme="dark">
@endif

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <!-- PWA Meta -->
    <meta name="theme-color" content="#000000">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <!-- Preload Sound and Notification Assets -->
    <link rel="preload" href="{{ asset('sounds/morning_initial.mp3') }}" as="audio" type="audio/mpeg">
    <link rel="preload" href="{{ asset('js/notifications.js') }}" as="script">

    <!-- Meta -->
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords" content="employee system, Solomon Batasi, Point of Sale, business, Human Resource, traintrack, ERM, HR, POS, JavaPA">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>JavaPA</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('/build/img/favicon.ico')}}">

    @include('layout.partials.head')
</head>

@php
    $accountPages = [
        'two-step-verification', 'email-verification', 'reset-password', 'forgot-password',
        'register', 'register-2', 'register-3', 'signin', 'signin-3', 'login',
        'success', 'success-2', 'success-3'
    ];
    $errorPages = ['under-maintenance', 'coming-soon', 'error-404', 'error-500'];
    $posLayouts = ['pos', 'pos-2', 'pos-3', 'pos-4', 'pos-5'];
@endphp

@if (!Route::is(array_merge($errorPages, $accountPages, ['layout-horizontal','layout-hovered','layout-boxed','layout-rtl','pos','pos-2','pos-3','pos-4','pos-5'])))
<body>
@endif
@if (Route::is(['layout-horizontal']))
<body class="menu-horizontal">
@endif
@if (Route::is(['layout-hovered']))
<body class="mini-sidebar expand-menu">
@endif
@if (Route::is(['layout-boxed']))
<body class="mini-sidebar layout-box-mode">
@endif
@if (Route::is(['layout-rtl']))
<body class="layout-mode-rtl">
@endif
@if (Route::is($errorPages))
<body class="error-page">
@endif
@if (Route::is($accountPages))
    <body class="account-page {{ Route::is(['register-3', 'reset-password-3', 'forgot-password-3', 'email-verification-3', 'two-step-verification-3', 'signin-3', 'login', 'success-3']) ? 'bg-white' : '' }}">
@endif
@if (Route::is(['lock-screen']))
    <img src="{{URL::asset('build/img/bg/lock-screen-bg.png')}}" alt="bg" class="lock-screen-bg position-absolute img-fluid d-sm-none d-md-none d-lg-flex">
@endif
@if (Route::is($posLayouts))
<body class="pos-page">
@endif

<!-- Loader -->
@component('components.loader') @endcomponent

<!-- Notification UI -->
<div id="pwa-install-container" class="hidden fixed bottom-0 left-0 right-0 bg-blue-600 text-white p-4" style="z-index: 9999;">
    <div class="flex justify-between items-center max-w-md mx-auto">
        <span>Install for better experience</span>
        <button id="pwa-install-btn" class="bg-white text-blue-600 px-4 py-2 rounded">Install</button>
    </div>
</div>
<audio id="notification-sound" preload="auto"></audio>

<!-- Main Wrapper -->
@if (!Route::is(array_merge(['lock-screen'], $posLayouts)))
<div class="main-wrapper">
@endif
@if (Route::is(['lock-screen']))
<div class="main-wrapper login-body">
@endif
@if (Route::is(['pos']))
<div class="main-wrapper pos-five">
@endif
@if (Route::is(['pos-3']))
<div class="main-wrapper pos-two">
@endif
@if (Route::is(['pos-4']))
<div class="main-wrapper pos-three">
@endif
@if (Route::is(['pos-5']))
<div class="main-wrapper pos-three pos-four">
@endif

<!-- Header + Sidebar -->
@if (!Route::is(array_merge($errorPages, $accountPages, ['lock-screen'])))
    @include('layout.partials.header')
    @include('layout.partials.sidebar')
    @include('layout.partials.collapsed-sidebar')
    @include('layout.partials.horizontal-sidebar')
@endif

<!-- Page Content -->
@yield('content')
@stack('scripts')

</div> <!-- /Main Wrapper -->

@component('components.modalpopup') @endcomponent

<!-- PWA + Notification Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').then(reg => {
                console.log('ServiceWorker registered');

                let deferredPrompt;
                const installContainer = document.getElementById('pwa-install-container');
                const installBtn = document.getElementById('pwa-install-btn');

                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    deferredPrompt = e;
                    installContainer.classList.remove('hidden');
                });

                installBtn.addEventListener('click', () => {
                    if (!deferredPrompt) return;
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(choice => {
                        if (choice.outcome === 'accepted') {
                            installContainer.classList.add('hidden');
                        }
                        deferredPrompt = null;
                    });
                });
            });
        }

        // Ask for notification permission
        if ('Notification' in window) {
            Notification.requestPermission();
        }
    });
</script>

<script src="{{ asset('js/notifications.js') }}" defer></script>
@include('layout.partials.footer-scripts')
</body>
</html>
