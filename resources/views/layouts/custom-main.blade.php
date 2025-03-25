<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="RDN Management System">
    <meta name="author" content="XemmeX">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- TITLE -->
    <title>Les Remorques du Nord</title>

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/favicon.ico') }}" />

    @include('layouts/components/custom-styles')
    @include('layouts/components/styles')
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            display: none;
        }

        .loader {
            width: 50px;
            aspect-ratio: 1;
            display: grid;
            -webkit-mask: conic-gradient(from 15deg, #0000, #000);
            animation: l26 1s infinite steps(12);
            position: absolute;
            right: 0;
            bottom: 0;
            top: 43%;
            left: 0;
            margin: 0 auto;
            text-align: center;
        }

        .loader,
        .loader:before,
        .loader:after {
            background:
                radial-gradient(closest-side at 50% 12.5%,
                    var(--primary-bg-color) 96%, #0000) 50% 0/20% 80% repeat-y,
                radial-gradient(closest-side at 12.5% 50%,
                    var(--primary-bg-color) 96%, #0000) 0 50%/80% 20% repeat-x;
        }

        .loader:before,
        .loader:after {
            content: "";
            grid-area: 1/1;
            transform: rotate(30deg);
        }

        .loader:after {
            transform: rotate(60deg);
        }

        @keyframes l26 {
            100% {
                transform: rotate(1turn)
            }
        }
    </style>
</head>
@yield('body')
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
</div>
<!-- End Switcher --><!-- GLOBAL-LOADER -->
<div id="global-loader">
    <div class="loader"></div>
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div>
        <!-- CONTENT -->
        @yield('content')
        <!-- CONTENT CLOSED-->
    </div>
</div>

<!-- SCRIPTS -->
@include('layouts/components/common-scripts')
@include('layouts/components/custom-scripts')
@include('layouts/components/notify')
<script src="{{ asset('assets/js/loading/loading.js') }}"></script>
<!-- SCRIPTS CLOSED -->

</body>

</html>
