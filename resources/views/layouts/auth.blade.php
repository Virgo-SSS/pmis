<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title-page') - PMIS</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    @include('layouts.styles')
    @yield('styles')
</head>

<body>
    @include('layouts.header')
    @include('layouts.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>@yield('title')</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="@yield('breadcrumb-item-1-link')">@yield('breadcrumb-item-1')</a></li>
                    <li class="breadcrumb-item active">@yield('breadcrumb-item-2')</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        @yield('content')
    </main><!-- End #main -->

    @include('layouts.footer')
    @include('layouts.scripts')
    @yield('scripts')
</body>
</html>
