@extends('backend::public.layout')
@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li> <a href="<?=$param['uri']['base']?>">首页</a> <i class="fa fa-circle"></i> </li>
            <li> <span>系统设置</span> </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->

    <div class="row" style="margin-top: 16px">
        <!-- Main Portlet Start -->
        <div class="portlet light bordered" id="tablePortlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?=$param['pageTitle']?></span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle blue" id="addNewBtn">
                        <i class="fa fa-plus"></i> 新增
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <!-- Start Search Form -->
                <form class="form-inline" id="searchForm">
                    <!-- 查询关键字 start -->
                    <div class="form-group">
                        <label>关键字: </label>
                        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="查询关键字">
                    </div>
                    <!-- 查询关键字 end -->
                    <!-- 查询状态 start -->
                    <div class="form-group">
                        <label>状态: </label>
                        <select class="form-control" name="status" id="status">
                            <option selected="" value="">不限</option>
                            <option value="0">禁用</option>
                            <option value="1">启用</option>
                        </select>
                    </div>

                    <!-- 查询状态 end -->
                    <button type="submit" class="btn default" id="searchBtn"><i class="fa fa-search"></i> 查询</button>
                </form> <!-- End Search Form -->

                <!-- Start DateGrid -->
                <div class="table-scrollable">
                    <table id="dataGrid" class="table table-hover">
                        <tr>
                            <th width="40" data-field="id">ID</th>
                            <th width="80" data-field="icon" data-formatter="formatIcon">头像</th>
                            <th width="160" data-field="username" data-formatter="formatUsername">用户名</th>
                            <th width="80" data-field="phone">手机号码</th>
                            <th width="80" data-field="status" data-formatter="formatStatus">状态</th>
                            <th width="100" data-field="created_at" data-formatter="formatDate">创建时间</th>
                            <th width="160" data-field="signed_at" data-formatter="formatDatetime">最后登录</th>
                            <th width="160" data-field="user_device" data-formatter="formatTest">测试</th>
                            <th width="60" data-formatter="optResetPwd"></th>
                            <th width="60" data-formatter="optEdit"></th>
                            <th width="60" data-formatter="optDelete"></th>
                            <th>&nbsp;</th>
                        </tr>
                    </table>
                </div><!-- End DateGrid -->

            </div>
        </div> <!-- Main Portlet Start -->

        <div class="portlet box red" id="addEditPortlet" style="display: none">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-settings"></i>
                    <span class="caption-title uppercase"></span>
                </div>
            </div>
            <div class="portlet-body">
                <!-- start form -->
                <form id="addEditForm" class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="form-body">
                        <!-- start item -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">用户名</label>
                            <div class="col-md-7">
                                <input type="text" name="username" placeholder="用户名" class="form-control"
                                       data-valid="required" data-tips="请输入正确的用户名">
                            </div>
                        </div><!-- end item -->
                        <!-- start item -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">头像</label>
                            <div class="col-md-7">
                                <div id="iconPreview" class="fit-img-preview"></div>
                                <button id="iconUploadBtn"></button>
                                <input type="text" name="icon" class="hide">
                            </div>
                        </div><!-- end item -->
                        <!-- start item -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">手机号码</label>
                            <div class="col-md-7">
                                <input type="text" name="phone" placeholder="手机号码" class="form-control">
                            </div>
                        </div><!-- end item -->
                        <!-- start item -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">账号状态</label>
                            <div class="col-md-7">
                                <?=form_radios('status', $param['status'])?>
                            </div>
                        </div><!-- end item -->
                        <!-- start item -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Token</label>
                            <div class="col-md-7">
                                <input type="text" name="token" placeholder="登录Token" class="form-control" disabled>
                            </div>
                        </div><!-- end item -->
                        <!-- start item -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">角色</label>
                            <div class="col-md-7">
                                <?=form_checkbox_rows('roles', $param['roles'])?>
                            </div>
                        </div><!-- end item -->

                         <!-- start item -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">api测试账户</label>
                            <div class="col-md-7">
                                <?=form_radios('for_test', $param['for_test'])?>
                            </div>
                        </div><!-- end item -->
                    </div>
                    <div class="form-actions" style="margin-bottom: 60px;">
                        <hr>
                        <div class="row">
                            <div class="col-md-offset-3 col-md-7">
                                <button class="btn default btn-lg" id="closePortletBtn" type="button">
                                    <i class="fa fa-arrow-left"></i> 返回
                                </button>
                                <button class="btn red btn-lg" id="submitFormBtn" type="button">
                                    <i class="fa fa-check"></i> 提交
                                </button>
                            </div>
                        </div>
                    </div>
                </form><!-- END ADD EDIT FORM -->
            </div>
        </div>

    </div>

</div>

<!-- END CONTENT BODY -->
    @stop