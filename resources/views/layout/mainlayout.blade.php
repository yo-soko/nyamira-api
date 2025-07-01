<!DOCTYPE html>
@if (!Route::is(['layout-horizontal','layout-detached','layout-modern','layout-two-column','layout-hovered','layout-boxed','layout-rtl','layout-dark']))
<html lang="en" data-theme="dark">
@endif
@if (Route::is(['layout-horizontal']))
<html lang="en" data-layout="horizontal">
@endif
{{-- [Other layout conditions remain unchanged] --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <!-- Add PWA Meta Tags -->
    <meta name="theme-color" content="#000000">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <!-- Preload Notification Assets -->
    <link rel="preload" href="{{ asset('sounds/morning_initial.mp3') }}" as="audio" type="audio/mpeg">
    <link rel="preload" href="{{ asset('js/notifications.js') }}" as="script">

    <!-- [Existing meta tags remain unchanged] -->
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

{{-- [All existing body conditions remain unchanged] --}}
@if (!Route::is(['under-maintenance', 'coming-soon', 'error-404', 'error-500', 'two-step-verification-3', /* other routes... */]))
    <body>
@endif

{{-- Add Notification Elements --}}
<div id="pwa-install-container" class="hidden fixed bottom-0 left-0 right-0 bg-blue-600 text-white p-4" style="z-index: 9999;">
    <div class="flex justify-between items-center max-w-md mx-auto">
        <span>Install for better experience</span>
        <button id="pwa-install-btn" class="bg-white text-blue-600 px-4 py-2 rounded">Install</button>
    </div>
</div>

<audio id="notification-sound" preload="auto"></audio>

{{-- [All existing wrapper divs remain unchanged] --}}
@if (!Route::is(['lock-screen','pos','pos-3','pos-4','pos-5']))
    <div class="main-wrapper">
@endif

{{-- [All existing includes remain unchanged] --}}
@include('layout.partials.header')
@include('layout.partials.sidebar')
{{-- etc... --}}

@yield('content')
@stack('scripts')

</div>
<!-- /Main Wrapper -->

@component('components.modalpopup')
@endcomponent

<!-- Add Notification Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset('sw.js') }}')
                .then(reg => {
                    console.log('ServiceWorker registered');

                    // PWA Installation Prompt
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

        // Request notification permission
        if ('Notification' in window) {
            Notification.requestPermission();
        }
    });
</script>

<!-- Load notification handler -->
<script src="{{ asset('js/notifications.js') }}" defer></script>

@include('layout.partials.footer-scripts')
</body>
</html>
