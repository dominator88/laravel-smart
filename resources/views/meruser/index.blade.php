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
                    <a href="javascript:;" class="btn btn-circle blue" id="addNewBtn">
                        <i class="fa fa-plus"></i> 新增
                    </a>
                    <a href="javascript:;" class="btn btn-circle red" id="destroySelectBtn">
                        <i class="fa fa-trash"></i> 删除
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <!-- Start Search Form -->
                <form class="form-inline" id="searchForm">
                    <!-- 查询关键字 start -->
                    <div class="form-group">
                        <label>关键字: </label>
                        <input type="text" class="form-control" name="keyword" placeholder="查询关键字">
                    </div>
                    <!-- 查询关键字 end -->
                    <!-- 查询状态 start -->
                    <div class="form-group">
                        <label>状态: </label>
                        <select class="form-control" name="status">
                            <option selected="" value="">不限</option>
                            <?= form_options($param['status']) ?>
                        </select>
                    </div>
                    <!-- 查询状态 end -->
                    <button type="submit" class="btn default" id="searchBtn"><i class="fa fa-search"></i> 查询</button>
                </form> <!-- End Search Form -->

                <div class="table-scrollable">
                    <table id="dataGrid" class="table table-hover">
                        <tr>
                            <th width="40" data-field="id"></th>
                            <th width="80" data-field="icon" data-formatter="formatIcon">头像</th>
                            <th width="200" data-field="昵称" data-formatter="formatName">昵称</th>
                            <!--<th width="80" data-field="bucks">零钱</th>-->
                            <!--<th width="80" data-field="points">积分</th>-->
                            <th width="80" data-field="reg_from" data-formatter="formatRegFrom">注册类型</th>
                            <th width="80" data-field="status" data-formatter="formatStatus">状态</th>
                            <th width="120" data-field="reg_at" data-formatter="formatDate">注册时间</th>
                            <th width="120" data-field="login_at" data-formatter="formatDate">最后登录时间</th>
                            <th width="40" data-formatter="optResetPwd"></th>
                            <th width="60" data-formatter="optEdit"></th>
                            <th width="60" data-formatter="optDelete"></th>
                            <th>&nbsp;</th>
                        </tr>
                    </table>
                </div>


            </div>
        </div> <!-- Main Portlet Start -->

        <!-- BEGIN ADD EDIT PORTLET -->
        <div class="portlet box green-meadow" id="addEditPortlet" style="display: none;">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-settings"></i>
                    <span class="caption-subject uppercase"></span>
                </div>
            </div>
            <div class="portlet-body">
                <!-- start add edit form  -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- start form -->
                        <form id="addEditForm" class="form-horizontal">
                            <div class="form-body">
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">昵称</label>
                                    <div class="col-md-7">
                                        <input type="text" name="nickname" placeholder="昵称" class="form-control"
                                               data-valid="required" data-tips="请输入正确的昵称">
                                    </div>
                                </div><!-- end item -->

                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">手机</label>
                                    <div class="col-md-7">
                                        <input type="text" name="phone" placeholder="手机" class="form-control"
                                               data-valid="required" data-tips="请输入正确的手机">
                                    </div>
                                </div><!-- end item -->

                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">性别</label>
                                    <div class="col-md-7">
                                        <?= form_radios('sex' , $param['sex'] ) ?>
                                    </div>
                                </div><!-- end item -->

                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">用户头像</label>
                                    <div class="col-md-7">
                                        <div id="iconPreview" class="fit-img-preview"></div>
                                        <button id="iconUploadBtn"></button>
                                        <input type="text" name="icon" class="hide">
                                    </div>
                                </div><!-- end item -->

                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">零钱</label>
                                    <div class="col-md-7">
                                        <input type="text" name="bucks" placeholder="用户零钱" class="form-control">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">积分</label>
                                    <div class="col-md-7">
                                        <input type="text" name="points" placeholder="用户积分" class="form-control">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">状态</label>
                                    <div class="col-md-7">
                                        <?= form_radios('status' , $param['status'] ) ?>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">注册类型</label>
                                    <div class="col-md-7">
                                        <?= form_radios('reg_from' , $param['regFrom']) ?>
                                    </div>
                                </div><!-- end item -->

                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">是否测试用户</label>
                                    <div class="col-md-7">
                                        <?= form_radios('for_test' , ['否','是'])?>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">注册IP</label>
                                    <div class="col-md-7">
                                        <input type="text" name="reg_ip" placeholder="注册IP" class="form-control" readonly>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">登录IP</label>
                                    <div class="col-md-7">
                                        <input type="text" name="login_ip" placeholder="登录IP" class="form-control" readonly>
                                    </div>
                                </div><!-- end item -->
                            </div>
                            <div class="form-actions" style="margin-bottom: 60px;">
                                <hr>
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-7">
                                        <button class="btn default btn-lg" id="closePortletBtn" type="button"><i
                                                    class="fa fa-arrow-left"></i> 返回
                                        </button>
                                        <button class="btn red btn-lg" id="submitFormBtn" type="button"><i class="fa fa-check"></i> 提交
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form><!-- END ADD EDIT FORM -->
                    </div>
                </div>
            </div><!-- END ADD EDIT PORTLET BODY -->
        </div><!-- END ADD EDIT PORTLET  -->

    </div>
</div>


<!-- END CONTENT BODY -->
@stop