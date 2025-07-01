<!DOCTYPE html>
@php
    $route = Route::currentRouteName();
    $accountPages = [
        'two-step-verification','two-step-verification-2','two-step-verification-3',
        'email-verification','email-verification-2','email-verification-3',
        'reset-password','reset-password-2','reset-password-3',
        'forgot-password','forgot-password-2','forgot-password-3',
        'register','register-2','register-3',
        'signin','signin-3','login',
        'success','success-2','success-3'
    ];
    $errorPages = ['under-maintenance', 'coming-soon', 'error-404', 'error-500'];
    $posPages = ['pos','pos-2','pos-3','pos-4','pos-5'];
    $showPwaPopup = !Route::is(array_merge($errorPages, $accountPages, $posPages, [
        'layout-horizontal','layout-hovered','layout-boxed','layout-rtl'
    ]));
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
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords" content="employee system, Solomon Batasi, Point of Sale, business, Human Resource, traintrack, ERM, HR, POS, JavaPA">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <title>JavaPA</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('/build/img/favicon.ico')}}">

    @include('layout.partials.head')
</head>

{{-- Begin Body Tag --}}
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
<body class="account-page">
@endif
@if (Route::is(['lock-screen']))
<body class="login-body">
    <img src="{{URL::asset('build/img/bg/lock-screen-bg.png')}}" alt="bg" class="lock-screen-bg position-absolute img-fluid d-sm-none d-md-none d-lg-flex">
@endif
@if (Route::is($posPages))
<body class="pos-page">
@endif

@component('components.loader') @endcomponent

@if (!Route::is(['lock-screen','pos','pos-3','pos-4','pos-5']))
    <div class="main-wrapper">
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

@if (!Route::is(array_merge($errorPages, $accountPages, ['lock-screen'])))
    @include('layout.partials.header')
    @include('layout.partials.sidebar')
    @include('layout.partials.collapsed-sidebar')
    @include('layout.partials.horizontal-sidebar')
@endif

@yield('content')
@stack('scripts')

</div>
<!-- /Main Wrapper -->

@component('components.modalpopup') @endcomponent

@if ($showPwaPopup)
<!-- 📱 PWA Install Modal -->
<div id="pwa-popup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[9999]">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl">
        <h3 class="font-bold text-lg mb-2">Install JavaPA</h3>
        <p class="text-gray-600 mb-4">For better experience with notifications</p>
        <div class="flex justify-end space-x-3">
            <button id="pwa-later" class="px-4 py-2 text-gray-600 hover:text-gray-800">Later</button>
            <button id="pwa-install" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">Install</button>
        </div>
    </div>
</div>

<script>
    if ('serviceWorker' in navigator) {
        let deferredPrompt;
        const popup = document.getElementById('pwa-popup');
        const installBtn = document.getElementById('pwa-install');
        const laterBtn = document.getElementById('pwa-later');
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        window.addEventListener('beforeinstallprompt', (e) => {
            if (!isMobile) return;

            e.preventDefault();
            deferredPrompt = e;

            setTimeout(() => {
                if (!localStorage.getItem('pwaPromptDismissed') && popup) {
                    popup.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }, 5000);
        });

        if (installBtn && laterBtn) {
            installBtn.addEventListener('click', async () => {
                popup.classList.add('hidden');
                document.body.style.overflow = '';
                localStorage.setItem('pwaPromptDismissed', 'true');

                if (deferredPrompt) {
                    try {
                        await deferredPrompt.prompt();
                        deferredPrompt = null;
                    } catch (err) {
                        console.error('Install failed:', err);
                    }
                }
            });

            laterBtn.addEventListener('click', () => {
                popup.classList.add('hidden');
                document.body.style.overflow = '';
                localStorage.setItem('pwaPromptDismissed', 'true');
            });
        }
    }
</script>
@endif

@include('layout.partials.footer-scripts')
</body>
</html>
