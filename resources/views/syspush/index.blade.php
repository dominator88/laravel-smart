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
                            <th width="40" data-field="id">ID</th>
                            <th width="80" data-field="catalog" data-formatter="formatCatalog">分类</th>
                            <th width="200" data-field="title" data-formatter="formatTitle">标题</th>
                            <th width="80" data-field="platform" data-formatter="formatPlatform">设备类型</th>
                            <th width="80" data-field="alias">别名</th>
                            <th width="80" data-field="tags">标签</th>
                            <th width="80" data-field="registration_id">激光ID</th>
                            <th width="80" data-field="extras">附加参数</th>
                            <th width="80" data-field="status" data-formatter="formatStatus">状态</th>
                            <th width="80" data-field="sent_at">发送时间</th>
                            <th width="180" data-field="created_at" data-formatter="formatDatetime">创建时间</th>
                            <th width="60" data-formatter="optSend"></th>
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
                            {!! csrf_field() !!}
                            <div class="form-body">
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">标题(仅Android)</label>
                                    <div class="col-md-7">
                                        <input type="text" name="title" placeholder="标题(仅Android)" class="form-control">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">内容</label>
                                    <div class="col-md-7">
                                        <input type="text" name="alert" placeholder="内容" class="form-control"
                                               data-valid="required" data-tips="请输入正确的内容">
                                    </div>
                                </div><!-- end item -->

                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">消息类型</label>
                                    <div class="col-md-7">
                                        <?= form_radios('catalog' , $param['catalog'] ) ?>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">设备类型</label>
                                    <div class="col-md-7">
                                        <?= form_radios('platform' , $param['platform'] ) ?>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">别名(用户id)</label>
                                    <div class="col-md-7">
                                        <input type="text" name="alias" placeholder="请输入正确的别名(用户id) 多个用,分隔" class="form-control">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">标签</label>
                                    <div class="col-md-7">
                                        <input type="text" name="tags" placeholder="用户分组 多个用,分隔" class="form-control">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">激光ID</label>
                                    <div class="col-md-7">
                                        <input type="text" name="registration_id" placeholder="激光ID 多个用,分隔" class="form-control">
                                    </div>
                                </div><!-- end item -->

                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">附加参数</label>
                                    <div class="col-md-7">
                                        <input type="text" name="extras" placeholder='附加参数 json字符串{"key1":"value1","key2":value2"}' class="form-control">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">状态</label>
                                    <div class="col-md-7">
                                        <?= form_radios('status' , $param['status'] ) ?>
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