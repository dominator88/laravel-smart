@extends('backend::public.layout')
@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
<ul class="page-breadcrumb">
<li><a href="<?= $param['uri']['module'] ?>">首页</a> <i class="fa fa-circle"></i></li>
<li><span><?= $param['pageTitle'] ?></span></li>
</ul>
</div>
<!-- END PAGE BAR -->
<div class="row" style="margin-top: 16px">
    <!-- Main Portlet Start -->
    <div class="portlet light bordered" id="tablePortlet">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings"></i>
                <span class="caption-subject uppercase"><?= $param['pageTitle'] ?></span>
            </div>
            <div class="actions">
                <a href="<?= $param['uri']['readme'] ?>" class="btn btn-circle btn-sm btn-primary"><i class="fa fa-file"></i>
                    接口说明</a>
            </div>
        </div>

        <div class="portlet-body">
            <!-- uri start -->
            <div class="row">
                <div class="col-md-12">
                    <h4 class="block">Uri</h4>
                    <hr>
                    <form id="uriForm" class="form-horizontal">
                        <div class="form-body">
                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">接口uri</label>
                                <div class="col-md-6">
                                    <span class="help-inline"> <?= $param['uri']['api'] ?> </span>
                                    <select name="version" id="version" class="form-control input-inline input-mini"></select>
                                    <select name="actions" id="actions" class="form-control input-inline input-medium"></select>
                                </div>
                                <div class="col-md-3">
										<span class="help-inline">
											<button class="btn btn-sm default" type="button" id="selectActionBtn">
												<i class="fa fa-check"></i> 选择接口</button>
										</span>
                                </div>
                            </div><!-- end item -->
                        </div>
                    </form>
                </div>
            </div><!-- uri end -->

            <!-- header start -->
            <div class="row">
                <div class="col-md-12">
                    <h4>
                        Header
                        <button id="showOrHideHeader" type="button" class="btn btn-sm green">显示</button>
                    </h4>
                    <hr>
                    <form id="headerForm" class="form-horizontal" style="display: none;">
                        <div class="form-body">
                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">客户端系统类型</label>
                                <div class="col-md-6">
                                    <input type="text" name="device" placeholder="设备类型( 品牌 + 型号)" class="form-control "
                                           value="Apple iPhone 7">
                                </div>
                                <div class="col-md-3">
                                    <span class="help-inline"> device </span>
                                </div>
                            </div><!-- end item -->

                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">客户端系统版本号</label>
                                <div class="col-md-6">
                                    <input type="text" name="device-os-version" placeholder="操作系统版本号" class="form-control "
                                           value="9.3.2">
                                </div>
                                <div class="col-md-3">
                                    <span class="help-inline"> device-os-version </span>
                                </div>
                            </div><!-- end item -->

                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">客户端版本号</label>
                                <div class="col-md-6">
                                    <input type="text" name="app-version" placeholder="客户端版本号" class="form-control"
                                           value="1.0.0">
                                </div>
                                <div class="col-md-3">
                                    <span class="help-inline"> app-version </span>
                                </div>
                            </div><!-- end item -->

                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">时间戳</label>
                                <div class="col-md-6">
                                    <input type="text" name="timestamp" id="timestamp" placeholder="时间戳" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <span class="help-inline"> timestamp </span>
                                </div>
                            </div><!-- end item -->
                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">签名</label>
                                <div class="col-md-6">
                                    <input type="text" name="signature" id="signatureInp" placeholder="签名" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <span class="help-inline"> signature </span>
                                </div>
                            </div><!-- end item -->

                            

                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">签名密钥(secret)</label>
                                <div class="col-md-6">
                                    <span class="help-inline"> <?= $param['secret'] ?> </span>
                                </div>
                            </div><!-- end item -->
                        </div>
                    </form>
                </div>
            </div><!-- header end -->

            <!-- Params start -->
            <div class="row">
                <div class="col-md-12">
                    <h4 class="block">Params</h4>
                    <hr>
                    <div id="params">

                    </div>
                </div>
            </div><!-- Params end -->

            <!-- response start-->
            <div class="row">
                <div class="col-md-12">
                    <h4 class="block">返回结果</h4>
                    <hr>
                    <form class="form-horizontal">
                        <div class="form-body">
                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">签名字符串</label>
                                <div class="col-md-6">
                                    <pre id="signatureStr"></pre>
                                </div>
                            </div><!-- end item -->
                            <!-- start item -->
                            <div class="form-group">
                                <label class="col-md-2 control-label">返回结果</label>
                                <div class="col-md-6">
                                    <pre id="apiResponse"></pre>
                                </div>
                            </div><!-- end item -->
                        </div>
                    </form>

                </div>
            </div><!-- response end -->

        </div>
    </div> <!-- Main Portlet Start -->
</div>
<!-- END PAGE CONTENT INNER -->
</div>

@stop
