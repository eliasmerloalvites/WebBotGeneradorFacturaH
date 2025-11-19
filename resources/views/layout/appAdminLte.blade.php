<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('titulo')</title><!--parte cambiante con yield-->

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  <!-- Datatables -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">

  <!-- Select2 -->
  <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
  <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

  @yield('head')

  <style>
      html, body {
          background-color: #fff;
          color: #636b6f;
          font-family: 'Nunito', sans-serif;
          font-weight: 200;
          height: 100%;
          margin: 0;
      }
      .full-height {
          height: 100%;
      }
      .flex-center {
          align-items: center;
          display: flex;
          justify-content: center;
      }
      .position-ref {
          position: relative;
      }
      .top-right {
          position: absolute;
          right: 10px;
          top: 18px;
      }
      .content {
          text-align: center;
      }
      .title {
          font-size: 84px;
      }
      .links > a {
          color: #636b6f;
          padding: 0 25px;
          font-size: 13px;
          font-weight: 600;
          letter-spacing: .1rem;
          text-decoration: none;
          text-transform: uppercase;
      }
      .m-b-md {
          margin-bottom: 30px;
      }
  </style>
  
  <!-- Carga jQuery una sola vez (desde AdminLTE) -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
  
  <!-- Navbar -->
  @include('partials.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('partials.sidebar')

  @include('partials.container')
  
  @include('partials.controlSidebar')
</div>

@stack('scripts')
        <!-- Scripts: ORDEN CORRECTO -->
        <!-- 1. jQuery ya se cargó en el head -->
        <!-- 2. jQuery UI 1.11.4 -->
        <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolver conflicto entre jQuery UI tooltip con Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button)
        </script>
        
        <!-- 3. Bootstrap 4 (bootstrap.bundle.min.js incluye Popper) -->
        <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        
        <!-- ChartJS -->
        <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
        <!-- JQVMap -->
        <script src="{{ asset('adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
        <!-- daterangepicker -->
        <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
        
        <!-- overlayScrollbars -->
        <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        
        <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
           
        <script src="{{ asset('js/image-elias.js') }}"></script>
        <!-- ChartJS duplicado se mantiene si algún script lo requiere -->
        <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
        <!-- Sparkline -->
        <script src="{{ asset('adminlte/plugins/sparklines/sparkline.js') }}"></script>
        <!-- Theme style -->
        <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('adminlte/dist/js/demo.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ asset('adminlte/dist/js/pages/dashboard.js') }}"></script>
        
         <!-- Datatables -->
         <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
         <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
        
         <!-- Se ha eliminado la siguiente línea duplicada del CDN:
         <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.js"></script>
         -->
         
         <script type="text/javascript" language="javascript"
             src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
         <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
         </script>
         <script type="text/javascript" language="javascript"
             src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
         <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js">
         </script>
         <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js">
         </script>
         <link rel="stylesheet" type="text/css"
             href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
        
        
          <!-- ijaboCropTool -->
          <link rel="stylesheet" href="{{ asset('ijaboCropTool/ijaboCropTool.min.css') }}">
          <script src="{{ asset('ijaboCropTool/ijaboCropTool.min.js') }}"></script>
        
         <!-- Fechas -->
         <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
         <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
         <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        
        
         <!-- Graficos -->
             <script src="https://code.highcharts.com/highcharts.js"></script>
             <script src="https://code.highcharts.com/modules/data.js"></script>
             <script src="https://code.highcharts.com/modules/drilldown.js"></script>
             <script src="https://code.highcharts.com/modules/series-label.js"></script>
             <script src="https://code.highcharts.com/modules/exporting.js"></script>
             <script src="https://code.highcharts.com/modules/export-data.js"></script>
             <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        
             <script src="{{ asset('Highcharts/code/highcharts.js') }}"></script>
             <script src="{{ asset('Highcharts/code/modules/pareto.js') }}"></script>
             <script src="{{ asset('Highcharts/code/modules/data.js') }}"></script>
             <script src="{{ asset('Highcharts/code/modules/drilldown.js') }}"></script>
        
        @yield('script')
        
        <script type="text/javascript">
            if (window.location.hash && window.location.hash == '#_=_') {
                window.location.hash = '';
            }
        
        
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        
                Datos();
            });
        
        
            function Datos() {
                $.ajax({
                    url: "{{ route('personal.getimagen') }}",
                    type: "GET",
                }).done(function(data) {
                    $('#avatarImageHeader').attr('src', data.ruta);
                    $('#avatarImageMenu').attr('src', data.ruta);
                })
            }
        </script>

</body>
</html>