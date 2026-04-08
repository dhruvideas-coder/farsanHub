<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FarsanHub') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fade.css') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-input.form-control { background-color: #fff !important; cursor: pointer; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body class="ltr app sidebar-mini">

    <!-- <div id="global-loader">
        <img src="{{ asset('images/loader.svg') }}" class="loader-img" alt="Loader">
    </div> -->
    <div class="page is-expanded overflow-auto">
        <div class="page-main">
            <div class="app-content mt-0 overflow-auto">
                @include('admin.parts.sidebar')
                <div class="main-content">
                    <div class="side-app">
                        <div class="main-container container-fluid">
                            @include('admin.parts.header')
                            <hr class="dropdown-divider" />
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="#top" id="back-to-top" style="display: none;"><i class="fa fa-long-arrow-up"></i></a>

    <script type="text/Javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script> <!-- jquery -->

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/spinner.js') }}"></script>
    <script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('sidemenu/sidemenu.js') }}"></script>
    <!-- <script src="{{ asset('assets/plugins/inputtags/inputtags.js') }}"></script> -->
    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- datepicker -->
    <!-- <script src="{{ asset('assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script> -->

    <!--datetimepicker-->
    <!-- <script src="{{ asset('assets/adminlte/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script> -->


    <!-- Flatpickr JS + global date picker init -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="date"]').forEach(function (el) {
                var config = {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd-m-Y',
                    allowInput: false,
                    disableMobile: true,
                };
                // If the input has data-fp-onchange, call that window function on date select
                if (el.dataset.fpOnchange) {
                    var fnName = el.dataset.fpOnchange;
                    config.onChange = function () {
                        if (typeof window[fnName] === 'function') window[fnName]();
                    };
                }
                flatpickr(el, config);
            });
        });
    </script>

    <script>
        function handleLanguageChange(selectElement) {
            const selectedLang = selectElement.value;
            // Redirect to the same page with the new language code in the URL
            window.location.href = 'lang/' + selectedLang;
        }
    </script>

    <!-- Auto-logout after 5 minutes of inactivity -->
    <script>
        (function () {
            const IDLE_TIMEOUT   = 5 * 60;   // 5 minutes in seconds
            const WARN_COUNTDOWN = 30;        // seconds shown in popup

            let idleSeconds  = 0;
            let countingDown = false;
            let countdown    = WARN_COUNTDOWN;
            let tickInterval = null;
            let warnShown    = false;

            // ── activity reset ────────────────────────────────────────────
            function resetIdle() {
                idleSeconds = 0;
                if (countingDown) {
                    countingDown = false;
                    warnShown    = false;
                    countdown    = WARN_COUNTDOWN;
                    Swal.close();
                }
            }

            ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click']
                .forEach(function (evt) {
                    document.addEventListener(evt, resetIdle, { passive: true });
                });

            // ── logout helper (POST to /logout) ───────────────────────────
            function doLogout() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                const csrf = document.createElement('input');
                csrf.type  = 'hidden';
                csrf.name  = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }

            // ── show warning popup ────────────────────────────────────────
            function showWarning() {
                warnShown    = true;
                countingDown = true;
                countdown    = WARN_COUNTDOWN;

                Swal.fire({
                    title              : 'Are you still there?',
                    html               : 'You will be logged out in <strong id="swal-countdown">' + countdown + '</strong> seconds.<br>Click anywhere to stay logged in.',
                    icon               : 'warning',
                    showConfirmButton  : true,
                    confirmButtonText  : 'Stay Logged In',
                    confirmButtonColor : '#0d6efd',
                    allowOutsideClick  : true,
                    allowEscapeKey     : false,
                    didOpen: function () {
                        // clicking confirm button counts as activity
                        document.querySelector('.swal2-confirm').addEventListener('click', resetIdle);
                        // clicking outside the popup counts as activity
                        document.querySelector('.swal2-container').addEventListener('click', resetIdle);
                    }
                }).then(function (result) {
                    if (result.isConfirmed) resetIdle();
                });
            }

            // ── main tick (every second) ──────────────────────────────────
            tickInterval = setInterval(function () {
                idleSeconds++;

                if (idleSeconds >= IDLE_TIMEOUT && !warnShown) {
                    showWarning();
                }

                if (countingDown) {
                    countdown--;

                    const el = document.getElementById('swal-countdown');
                    if (el) el.textContent = countdown;

                    if (countdown <= 0) {
                        clearInterval(tickInterval);
                        Swal.close();
                        doLogout();
                    }
                }
            }, 1000);
        })();
    </script>

</body>

</html>
