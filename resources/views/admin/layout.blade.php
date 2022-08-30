<html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <base href="{{asset('')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" id="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titlePage')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('adminLTE/images/favicon.ico')}}">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('adminLTE/dist/css/adminlte.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('adminLTE/dist/css/ionicons.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <!-- jquery ui -->
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/jquery-ui/jquery-ui.min.css')}}">

    @yield('stylecss')

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @if (!Auth::check())
    <link href="{{asset('css/style-login.css')}}" rel="stylesheet" type="text/css" />
    @endif
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    @if(Auth::check())
    <div class="wrapper">
        @include('admin.includes.header')
        @include('admin.includes.leftnav')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper white">
            @yield('main')
        </div>
        @include('admin.includes.footer')
    </div>

    @else
    @yield('main')
    @endif

    <!-- jQuery 3 -->
    <script src="{{asset('adminLTE/plugins/jquery/jquery.min.js')}}"></script>
    <!-- jquery ui -->
    <script src="{{asset('adminLTE/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- SlimScroll -->
    <script src="{{asset('adminLTE/dist/js/adminlte.min.js')}}"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('adminLTE/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <!-- vue js 2.6.10-->
    @if(config('app.env') !== 'production')
    <script src="{{asset('js/libs/vue.dev.v2.6.12.js')}}"></script>
    @else
    <script src="{{asset('js/libs/vue.v2.6.10.js')}}"></script>
    @endif
    <!-- Load file setting router của vue -->
    <script src="{{ asset('js/vue-router.js') }}"></script>
    <!-- axios -->
    <script src="{{ asset('js/libs/axios.js') }}"></script>
    <!-- Lodash -->
    <script src="{{ asset('js/libs/lodash.js') }}"></script>
    <!-- Ckeditor 5 build classic -->
    <script src="{{asset('adminLTE/plugins/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <!-- END Java Script for this page -->
    @yield('libraryjs')
    <script>
        CKEDITOR.config.filebrowserImageBrowseUrl = "{{url('/filemanager?type=Images')}}";
        CKEDITOR.config.filebrowserImageUploadUrl = "{{url('/filemanager/upload?type=Images&_token=')}}'";
        CKEDITOR.config.filebrowserBrowseUrl      = "{{url('/filemanager?type=Files')}}";
        CKEDITOR.config.filebrowserUploadUrl      = "{{url('/filemanager/upload?type=Files&_token=')}}";
        activeTabelResponsive();
    </script>
    <div id="loading">
        <div id="overlay" class="overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw "></i></div>
    </div>
</body>

</html>
