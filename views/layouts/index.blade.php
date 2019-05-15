<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Jiafang.Wang" content="王家访,270494194@qq.com">
    <title>{{config('admin.title')}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset("/library/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset('/library/ionicons/2.0.1/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset("/library/font-awesome/4.6.1/css/font-awesome.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/library/AdminLTE/css/skins/" . config('admin.skin') .".min.css") }}">
    <link rel="stylesheet" href="{{ asset("/library/datatables/jquery.dataTables.min.css") }}">
    <link rel="stylesheet" href="{{ asset('/library/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('/library/iCheck/square/red.css') }}">
    <link rel="stylesheet" href="{{ asset('/library/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/library/bootstrap-fileinput/css/fileinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/library/load/load.css') }}">
    <link rel="stylesheet" href="{{ asset("/library/nestable/nestable.css") }}">
    <link rel="stylesheet" href="{{ asset('/library/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/library/nprogress/nprogress.css') }}">
    <link rel="stylesheet" href="{{ asset('/library/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset("/library/AdminLTE/css/AdminLTE.min.css") }}">
    @stack('css')
    <script src="{{ asset ("/library/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <script src="{{ asset ("/library/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset ("/library/slimScroll/jquery.slimscroll.min.js") }}"></script>
    <script src="{{ asset ("/library/AdminLTE/js/app.min.js") }}"></script>
    <script src="{{ asset ("/library/jquery-pjax/jquery.pjax.js") }}"></script>
    <script src="{{ asset('/library/nprogress/nprogress.js') }}"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition {{config('admin.skin')}} {{join(' ', config('admin.layout'))}}">
<div class="wrapper">
    @if(view()->exists('admin.layouts.header'))
        @include('admin.layouts.header')
    @else
        @include('admin::layouts.header')
    @endif

    @if(view()->exists('admin.layouts.sidebar'))
        @include('admin.layouts.sidebar')
    @else
        @include('admin::layouts.sidebar')
    @endif

    <div class="content-wrapper">
        @yield('content')

    </div>
    @if(view()->exists('admin.layouts.validator-error'))
        @include('admin.layouts.validator-error')
    @else
        @include('admin::layouts.validator-error')
    @endif

    @if(view()->exists('admin.layouts.footer'))
        @include('admin.layouts.footer')
    @else
        @include('admin::layouts.footer')
    @endif

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset ("/library/chartjs/Chart.min.js") }}"></script>
<script src="{{ asset ("/library/nestable/jquery.nestable.js") }}"></script>
<script src="{{ asset('/library/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('/library/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('/library/select2/js/i18n/zh-CN.js') }}"></script>
<script src="{{ asset('/library/layer/layer.js') }}"></script>
<script src="{{ asset('/library/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('/library/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/library/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.icheck').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red',
            increaseArea: '20%' // optional
        });
        $(".select2").select2();
        $('table').delegate('.btn-del','click',function(){
            $('.deleteForm').attr('action',$(this).attr('data-href'));
            $('#modal-delete').modal();
        });
        $(".datepicker").datepicker({
            language: "zh-CN",
            autoclose: true,
            clearBtn: false,
            todayBtn: false,
            format: "yyyy-mm-dd"
        });
        toastr.options = {
            closeButton: false,
            debug: false,
            progressBar: true,
            positionClass: "toast-top-center",
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "2000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    });
</script>
@stack('js')
</body>
</html>
