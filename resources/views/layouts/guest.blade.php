<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('page-title') - PMIS</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    @include('layouts.styles')
</head>

<body>
    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="">
                                    <span class="d-none d-lg-block">PMIS</span>
                                </a>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    @include('layouts.scripts')
</body>

</html>
