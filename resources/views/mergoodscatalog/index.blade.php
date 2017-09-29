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
                    {!! csrf_field() !!}
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
                    <table id="treeGrid" class="table table-hover">
                        <tr>
                            <th width="40" data-field="id">ID</th>
                            <th width="40" data-field="sort">排序</th>
                            <th width="80" data-field="type" data-formatter="formatType">类型</th>
                            <th width="80" data-field="pid">上级</th>
                            <th width="200" data-field="text">名称</th>
                            <th width="80" data-field="icon" data-formatter="formatIcon">图标</th>
                            <th width="80" data-field="status" data-formatter="formatStatus">状态</th>
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
                                    <label class="col-md-3 control-label">排序</label>
                                    <div class="col-md-7">
                                        <input type="text" name="sort" placeholder="排序" class="form-control"
                                               data-valid="required" data-tips="请输入正确的排序">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">类型</label>
                                    <div class="col-md-7">
                                        <?= form_radios('type' , $param['type'] ) ?>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">上级</label>
                                    <div class="col-md-7">
                                        <select name="pid" class="form-control">
                                        </select>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">名称</label>
                                    <div class="col-md-7">
                                        <input type="text" name="text" placeholder="名称" class="form-control"
                                               data-valid="required" data-tips="请输入正确的名称">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">图标</label>
                                    <div class="col-md-7">
                                        <div id="iconPreview" class="fit-img-preview"></div>
                                        <button id="iconUploadBtn"></button>
                                        <input type="text" name="icon" class="hide">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">描述</label>
                                    <div class="col-md-7">
                                        <input type="text" name="desc" placeholder="描述" class="form-control"
                                               data-valid="required" data-tips="请输入正确的描述">
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