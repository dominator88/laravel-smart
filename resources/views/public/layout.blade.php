
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <base href="{{ $param['uri']['base'] }}">
    <title>{{ $param['pageTitle'] }} - {{ config('moduleName') }}</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <!-- No Baidu Site App-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <!-- Site Logo -->
    <link rel="icon" type="image/png" href="static/favicon.png">

    <!-- Core CSS Start -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
    <link href="node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="node_modules/simple-line-icons-webfont/dist/css/simple-line-icons.css" rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">

    <link href="static/themes/global/app.min.css" rel="stylesheet">
    <link href="static/themes/global/custom.min.css" rel="stylesheet">
    <!-- Core CSS End -->

    <!-- Custom CSS Start -->
{!! $css !!}
<!-- Custom CSS End -->
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">

<!-- Header Start -->
@include('backend::public.header')
<!-- Header End  -->

<!-- Body Start -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar navbar-collapse collapse">
            @include('backend::public.sidebar_menu')
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
@yield('content')
    </div>
    <!-- END CONTENT -->
</div>

<!-- 修改密码 Start -->
<div id="chPwdModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
                <h4 class="modal-title">修改密码</h4>
            </div>
            <div class="modal-body">
                <form id="chPwdForm" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="password" name="oldPwd" placeholder="原密码" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="password" name="pwd" placeholder="新密码" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="password" name="pwdConfirm" placeholder="密码确认" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn dark btn-outline" type="button">关闭</button>
                <button class="btn red" type="button" id="submitChPwdForm">修改</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div> <!-- 修改密码 End -->

<!-- Body End -->

<!-- Footer Start -->
@include('backend::public.footer')
<!-- Footer End -->

<!-- Core Javascript Start -->
<script src="node_modules/jquery/dist/jquery.min.js" type="text/javascript"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="node_modules/js-cookie/src/js.cookie.js" type="text/javascript"></script>
<script src="node_modules/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="node_modules/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="node_modules/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="node_modules/bootstrap-switch/dist/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="node_modules/bootpag/lib/jquery.bootpag.min.js" type="text/javascript"></script>
<!-- Core Javascript End  -->

<!-- Layout Javascript End-->
<!--<script src="static/src/js/global/app.js" type="text/javascript"></script>-->
<!--<script src="static/src/js/global/layout.js" type="text/javascript"></script>-->
<script src="static/js/global/app.min.js" type="text/javascript"></script>
<script src="static/js/global/custom.min.js" type="text/javascript"></script>
<!-- Layout Javascript End  -->

<!-- Custom Javascript Start -->
{!! $js !!}
<!-- Custom Javascript End  -->

</body>
</html>