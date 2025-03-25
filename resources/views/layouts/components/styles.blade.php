<!-- BOOTSTRAP CSS -->
<link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

<!-- STYLE CSS -->
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/css/skin-modes.css') }}" rel="stylesheet" />

<!--- FONT-ICONS CSS -->
<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />


<!-- INTERNAL SWITCHER CSS -->
<link href="{{ asset('assets/switcher/css/switcher.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/switcher/demo.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css" />
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
<style>
    .numberCircle {
        border-radius: 50%;
        width: 20px;
        height: 18px;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 7px;
        padding-right: 7px;

        background: #fff;
        border: 2px solid #666;
        color: #666;
        text-align: center;
        margin-left: 3px;
        font: 12px Arial, sans-serif;
    }

    .max-width-address {
        max-width: 400px;
        white-space: normal;
    }
</style>
@yield('styles')
