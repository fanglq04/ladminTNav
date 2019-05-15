<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>登录-{{config('admin.title')}}后台管理系统</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" />
    <link rel="stylesheet" href="{{ asset("/library/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/library/font-awesome/4.6.1/css/font-awesome.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/library/AdminLTE/css/skins/" . config('admin.skin') .".min.css") }}">
    <link rel="stylesheet" href="{{ asset('/library/iCheck/square/red.css') }}">
    <link rel="stylesheet" href="{{ asset("/library/AdminLTE/css/AdminLTE.min.css") }}">
@stack('css')
    <script src="{{ asset ("/library/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <script src="{{ asset ("/library/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset ("/library/AdminLTE/js/app.min.js") }}"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page" style="background-image: url({{asset('images/login-bg.jpg')}})">
<div class="login-box">
    <div class="login-logo">
        <a>后台管理系统</a>
    </div>
    @include('admin::layouts.validator-error')
<!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎使用</p>
        <form action="{{ route('admin.login') }}" method="post">
            {!! csrf_field() !!}
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="登录邮箱"  name="email" >
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control"  name="password" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember">&nbsp;&nbsp;记&nbsp;住&nbsp;
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>


<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset('/library/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('/library/toastr/toastr.min.js') }}"></script>
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
