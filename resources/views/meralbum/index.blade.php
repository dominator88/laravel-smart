@extends('backend::public.layout')
@section('content')
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="javascript:;">首页</a><i class="fa fa-circle"></i></li>
            <li class="active"></li>
        </ul>
        <!-- END PAGE BREADCRUMB -->

        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="row" id="grid_portlet">
            <div class="col-md-12 col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark bold uppercase"></span>
                            <!-- <span class="caption-helper hide">weekly stats...</span>-->
                        </div>
                        <div class="actions">
                            <button id="uri_upload_btn"></button>
                            <a class="btn btn-danger btn-circle" id="delete_select_btn" href="javascript:;">
                                <i class="fa fa-trash"></i> 删除选中
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- search form start -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="search_form" class="form-inline">
                                    <!-- 查询关键字 start -->
                                    <div class="form-group">
                                        <label>关键字: </label>
                                        <input type="text" placeholder="查询关键字" id="keyword" class="form-control">
                                    </div>
                                    <!-- 查询关键字 end -->
                                    <!-- 查询状态 start -->
                                    <div class="form-group">
                                        <label>状态: </label>
                                        <select id="status" class="form-control">
                                            <option value="" selected>不限</option>
                                            <?= form_options($param['status']) ?>
                                        </select>
                                    </div>
                                    <!-- 查询状态 end -->
                                    <button id="search_btn" class="btn default" type="submit"><i class="fa fa-search"></i> 查询</button>
                                </form>
                            </div>
                        </div>
                        <!-- search form end -->

                        <div class="table-scrollable">
                            <table id="data_grid" class="table table-hover">
                                <tr>
                                    <th width="40" data-field="id">ID</th>
                                    <th width="40" data-field="sort">排序</th>
                                    <th width="80" data-field="uri" data-formatter="formatIcon">图片</th>
                                    <th width="80" data-field="size" data-formatter="format_file_size">文件大小</th>
                                    <th width="80" data-field="mimes">mime类型</th>
                                    <th width="80" data-field="img_size">图片尺寸</th>
                                    <th width="180" data-field="desc">描述</th>
                                    <th width="80" data-field="status" data-formatter="formatStatus">状态</th>
                                    <th width="60" data-formatter="optEdit"></th>
                                    <th width="60" data-formatter="optDelete"></th>
                                    <th>&nbsp;</th>
                                </tr>
                            </table>
                        </div>

                        <div class="back-btn-row">
                            <hr>
                            <a href="javascript:history.go(-1);" class="btn btn-lg default "><i class="icon-arrow-left"></i> 返回</a>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->


    </div>
</div>
<!-- END CONTENT -->

<!-- START ADD EDIT MODAL -->
<div class="modal fade" id="add_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title caption-subject">Modal title</h4>
            </div>
            <div class="modal-body">
                <!-- start add edit form  -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- start form -->
                        <form id="add_edit_form" class="form-horizontal">
                            <div class="form-body">
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">描述</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <input type="text" name="desc" placeholder="描述" class="form-control">
                                        </div>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">排序</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <input type="text" name="sort" placeholder="排序" class="form-control"
                                                   data-valid="required" data-tips="请输入正确的排序">
                                        </div>
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
                        </form><!-- end form -->
                    </div>
                </div>
                <!-- end add edit form-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal"><i class="fa fa-times"></i> 关闭</button>
                <button type="button" class="btn red" id="submit_form_btn"><i class="fa fa-save"></i> 保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.END ADD EDIT MODAL -->
    @stop
