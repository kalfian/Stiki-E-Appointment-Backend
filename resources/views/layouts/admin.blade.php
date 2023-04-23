<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') &mdash; {{ config('app.name') }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fancybox/dist/jquery.fancybox.css') }}">

    @yield('css')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Header -->
            <div class="navbar-bg"></div>
            @include('includes.nav')

            <!-- Sidebar -->
            @include('includes.sidebar')

            <!-- Content -->
            <div class="main-content">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="main-footer">
                <div class="footer-left">
                    {{ env('FOOTER_COPY_RIGHT', 'Stiki E-Appointment') }}
                </div>
                <div class="footer-right">
                    {{ env('APP_VERSION', '1.0.0') }}
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('assets/modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/modules/fancybox/dist/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('assets/modules/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    @yield('script')

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        $("#logout-sidebar").click(function() {
            swal({
                title: "Are you sure?",
                text: "You will be logged out from this session!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willLogout) => {
                if (willLogout) {
                    window.location.href = "{{ route('auth.signout') }}";
                }
            });
        });
    </script>
</body>

</html>
