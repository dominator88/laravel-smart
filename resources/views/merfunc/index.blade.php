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
                        <label>模块: </label>
                        <select class="form-control" name="module">
                            <option selected="" value="">不限</option>
                            <?= form_options($param['modules']) ?>
                        </select>
                    </div>
                    <!-- 查询状态 end -->
                    <button type="submit" class="btn default" id="searchBtn"><i class="fa fa-search"></i> 查询</button>
                </form> <!-- End Search Form -->

                <div class="table-scrollable">
                    <table id="treeGrid" class="table table-hover">
                        <tr>
                            <th width="40" data-field="id">ID</th>
                            <th width="280" data-field="name" data-formatter="formatName">名称</th>
                            <th width="40" data-field="module">模块</th>
                            <th width="40" data-field="sort">排序</th>
                            <th width="80" data-field="is_menu" data-formatter="formatIsFunc">是否菜单</th>
                            <th width="80" data-field="is_func" data-formatter="formatIsMenu">是否功能</th>
                            <th width="80" data-field="status" data-formatter="formatStatus">状态</th>
                            <th width="60" data-formatter="optNode"></th>
                            <th width="60" data-formatter="optPrivilege"></th>
                            <th width="60" data-formatter="optEdit"></th>
                            <th width="60" data-formatter="optDelete"></th>
                            <th>&nbsp;</th>
                        </tr>
                    </table>
                </div>

            </div>
        </div> <!-- Main Portlet Start -->




        <!-- BEGIN ADD EDIT PORTLET -->
        <div class="portlet box green-meadow" id="nodePortlet" style="display: none;">
          <div class="portlet-title">
            <div class="caption caption-md">
              <i class="icon-settings"></i>
              <span class="caption-subject uppercase"></span>

          </div>

          <div class="actions">
            <a href="javascript:;" class="btn btn-circle blue" id="addNodeBtn">
                <i class="fa fa-plus"></i> 新增
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <!-- start add edit form  -->
        <div class="row">
          <div class="col-md-12">
            <!-- start form -->
            <form id="editCoinForm" class="form-horizontal">

                {!! csrf_field() !!}
                <div class="form-body">
                    <!-- start item -->
                    <div class="table-scrollable">
                      <table id="dataGrid2" class="table table-hover">
                        <tr>
                          <th width="40" data-field="name"  >名称</th>
                          <th width="80" data-field="module" >模块</th>

                          <th width="80" data-field="symbol"   >标识</th>
                          <th width="80" data-field="status"  data-formatter="formatStatus">状态</th>

                          <th width="60" ><th width=60 data-formatter="optEditNode"></th></th>

                          <th>&nbsp;</th>
                      </tr>
                  </table>
              </div>
          </div>
          <div class="form-actions" style="margin-bottom: 60px;">
            <hr>
            <div class="row">
              <div class="col-md-offset-3 col-md-7">
                <button class="btn default btn-lg" id="closePortletBtn2" type="button" ><i class="fa fa-arrow-left"></i> 关闭</button>
            </div>
        </div>
    </div>
</form><!-- END ADD EDIT FORM -->
</div>
</div>
</div><!-- END ADD EDIT PORTLET BODY -->
</div><!-- END ADD EDIT PORTLET  -->


<!-- BEGIN ADD EDIT PORTLET -->
<div class="portlet box green-meadow" id="addNodePorlet" style="display: none;">
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
        <form id="addNodeForm" class="form-horizontal">
            <input type=hidden name='source_id' />
            <input type=hidden name='type' value='func'/>
            {!! csrf_field() !!}
            <div class="form-body">
                <!-- start item -->

                <!-- start item -->
                <div class="form-group">
                    <label class="col-md-3 control-label">名称</label>
                    <div class="col-md-7">
                        <input type="text" name="name" placeholder="名称" class="form-control"
                        data-valid="required" data-tips="名称" >
                    </div>
                </div><!-- end item -->
                <!-- start item -->
                <div class="form-group">
                    <label class="col-md-3 control-label">模块</label>
                    <div class="col-md-7">
                        <input type="text" name="module" placeholder="模块" class="form-control"
                        data-valid="required" data-tips="模块" readonly="true">
                    </div>
                </div><!-- end item -->
                <div class="form-group">
                    <label class="col-md-3 control-label">标识</label>
                    <div class="col-md-7">
                        <input type="text" name="symbol" placeholder="标识" class="form-control"
                        data-valid="required" data-tips="标识" >
                    </div>
                </div><!-- end item -->
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
                    <button class="btn default btn-lg" id="closePortletBtn3" type="button" ><i class="fa fa-arrow-left"></i> 返回</button>
                    <button class="btn red btn-lg" id="submitFormBtn3" type="button"><i class="fa fa-check"></i> 提交</button>
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

<!-- START ADD EDIT MODAL -->
<div class="modal fade" id="addEditModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title caption-subject"></h4>
            </div>
            <div class="modal-body">
                <!-- start add edit form  -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- start form -->
                        <form id="addEditForm" class="form-horizontal">
                            <div class="form-body">
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">模块</label>
                                    <div class="col-md-7">
                                        {!! form_radios('module',$param['modules']) !!}
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">名称</label>
                                    <div class="col-md-7">
                                        <input type="text" name="name" placeholder="名称" class="form-control"
                                        data-valid="required" data-tips="请输入正确的名称">
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">URI</label>
                                    <div class="col-md-7">
                                        <input type="text" name="uri" placeholder="URI" class="form-control">
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
                                    <label class="col-md-3 control-label">排序</label>
                                    <div class="col-md-7">
                                        <input type="text" name="sort" placeholder="排序" class="form-control"
                                        data-valid="required" data-tips="请输入正确的排序">
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
                                    <label class="col-md-3 control-label">是否菜单</label>
                                    <div class="col-md-7">
                                        <?= form_radios('is_menu' , $param['isMenu'] ) ?>
                                    </div>
                                </div><!-- end item -->
                                <!-- start item -->
                                <!--<div class="form-group">-->
                                    <!--<label class="col-md-3 control-label">是否功能</label>-->
                                    <!--<div class="col-md-7">-->
                                        <!--<?= form_radios('is_func' , $param['isFunc'] ) ?>-->
                                        <!--</div>-->
                                        <!--</div>-->
                                        <!-- end item -->

                                        <!-- start item -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">图标</label>
                                            <div class="col-md-7">
                                                <input type="text" name="icon" placeholder="图标" class="form-control">
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
                        <button type="button" class="btn red" id="submitFormBtn"><i class="fa fa-save"></i> 保存</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.END ADD EDIT MODAL -->


        <!-- START PRIVILEGE MODAL -->
        <div class="modal fade" id="privilegeModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title caption-subject">权限</h4>
                        </div>
                        <div class="modal-body">
                            <!-- start add edit form  -->
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- start form -->
                                    <form id="privilegeForm" class="form-horizontal">
                                        {!! csrf_field() !!}
                                        <div class="form-body">
                                            <!-- start item -->
                                            <div class="form-group">
                                                <div class="col-md-10 col-md-offset-2"  id="privilegeNode">
                                                    <?php foreach( $param['privilege'] as $key => $val ) :?>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="name[]" value="<?= $key ?>">
                                                                <?= $val ?> ( <?= implode( '&nbsp;&nbsp;,&nbsp;&nbsp;' , $param['alias'][$key]) ?> )
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
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
                            <button type="button" class="btn red" id="submitPrivilegeFormBtn"><i class="fa fa-save"></i> 保存</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.END PRIVILEGE MODAL --> 




            @stop