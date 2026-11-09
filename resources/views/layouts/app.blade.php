<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - LUWEH</title>

    <!-- Bootstrap -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    <section class="page-header ">
        <div class="d-flex align-items-center">
            <h2 class="">@yield('page-heading')</h2>
        </div>
    </section>

    <!-- PAGE CONTENT -->
    <main class="content">
        @yield('content')
    </main>

</body>

</html>
