<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - LUWEH</title>

    <!-- Bootstrap -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="d-flex justify-content-between align-items-center">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo LUWEH">

            <button class="btn text-white fs-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                ☰
            </button>
        </div>
    </header>

    <!-- SIDEBAR -->
    @include('components.sidebar')

    <!-- PAGE TITLE -->
    <section class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                @php
                    $hasBack = View::hasSection('back-url') || request()->is('*fnb-owner/history*') || request()->is('*courier/history*') || request()->is('*courier/activity/history*');
                    $backUrl = View::hasSection('back-url') ? trim(View::yieldContent('back-url')) : (request()->is('*courier*') ? route('courier.activity') : url('/fnb-owner/order'));
                @endphp

                @if ($hasBack)
                    <a href="{{ $backUrl }}" class="header-back-btn" aria-label="Kembali" title="Kembali">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" class="back-icon">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                @endif

                <div>
                    @if (View::hasSection('page-subtitle'))
                        <div class="page-subtitle">@yield('page-subtitle')</div>
                    @endif
                    <h2>@yield('page-heading')</h2>
                </div>
            </div>

            {{-- Logo / Tombol History: tampil pada order fnb-owner dan activity courier --}}
            @php
                $showHistory = request()->is('*fnb-owner/order*') || (View::hasSection('show-history') && trim(View::yieldContent('show-history')) === 'true');
                $historyUrl = View::hasSection('history-url') ? trim(View::yieldContent('history-url')) : (request()->is('*courier*') ? route('courier.history') : url('/fnb-owner/history'));
            @endphp
            <div class="header-action {{ $showHistory ? '' : 'd-none' }}">
                <a href="{{ $historyUrl }}" class="header-history-btn" aria-label="History" title="History">
                    <img src="{{ asset('assets/images/history.svg') }}" alt="History" class="history-icon">
                </a>
            </div>
        </div>
    </section>

    <!-- PAGE CONTENT -->
    <main class="content">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
