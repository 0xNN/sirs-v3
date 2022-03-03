<!-- 
    Versi 3 RS Online - RSUD Siti Fatimah 
    Dibuat oleh Muhammad Sendi
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('paper') }}/img/favicon.png">
    <link rel="icon" type="image/png" href="{{ asset('paper') }}/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Extra details for Live View on GitHub Pages -->
    
    <title>
        {{ config('myconfig.name') }}
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="{{ asset('paper') }}/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('paper') }}/css/paper-dashboard.css?v=2.0.0" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('paper') }}/demo/demo.css" rel="stylesheet" />

    <!-- CSS Datatable -->
    <link href="{{ asset('datatables') }}/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('datatables') }}/css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('datatables') }}/css/select.bootstrap4.min.css" rel="stylesheet">

    <!-- CSS Select2 -->
    <link href="{{ asset('select2') }}/css/select2.min.css" rel="stylesheet">

    <!-- CSS FontAwesome -->
    <link href="{{ asset('fontawesome') }}/css/all.min.css">

    {{-- <link href="{{ asset('css') }}/app.css" rel="stylesheet"> --}}

    <style>
        .dataTables_wrapper {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-size: 12px;
            position: relative;
            clear: both;
            *zoom: 1;
            zoom: 1;
        }

        table th {
            font-size: 13px !important;
        }

        /* Start by setting display:none to make this hidden.
        Then we position it in relation to the viewport window
        with position:fixed. Width, height, top and left speak
        for themselves. Background we set to 80% white with
        our animation centered, and no-repeating */
        .modal-loading {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 ) 
                        url("{{ asset('animation') }}/Hourglass.gif") 
                        50% 50% 
                        no-repeat;
        }

        /* When the body has the loading class, we turn
        the scrollbar off with overflow:hidden */
        body.loading .modal-loading {
            overflow: hidden;   
        }

        /* Anytime the body has the loading class, our
        modal element will be visible */
        body.loading .modal-loading {
            display: block;
        }
    </style>
    @stack('css')
</head>

<body class="{{ $class }}">
    
    @auth()
        @include('layouts.page_templates.auth')
        {{-- @include('layouts.navbars.fixed-plugin') --}}
    @endauth
    
    @guest
        @include('layouts.page_templates.guest')
    @endguest

    <div class="modal-loading"><!-- Place at bottom of page --></div>
    <!--   Core JS Files   -->
    <script src="{{ asset('paper') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('paper') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('paper') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('paper') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!--  Google Maps Plugin    -->
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}
    <!-- Chart JS -->
    <script src="{{ asset('paper') }}/js/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('paper') }}/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('paper') }}/js/paper-dashboard.min.js?v=2.0.0" type="text/javascript"></script>
    <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    <script src="{{ asset('paper') }}/demo/demo.js"></script>
    <!-- Sharrre libray -->
    {{-- <script src="{{ asset('paper')}}/demo/jquery.sharrre.js"></script> --}}
    
    <!-- Datatables -->
    <script src="{{ asset('datatables') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('datatables') }}/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('datatables') }}/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('datatables') }}/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('datatables') }}/js/dataTables.select.min.js"></script>
    
    <!-- Select2 -->
    <script src="{{ asset('select2') }}/js/select2.full.min.js"></script>
    
    <!-- FontAwesome -->
    <script src="{{ asset('fontawesome') }}/js/all.min.js"></script>

    <script src="{{ asset('js') }}/app.js"></script>
    @stack('scripts')

    @include('layouts.navbars.fixed-plugin-js')
</body>

</html>
