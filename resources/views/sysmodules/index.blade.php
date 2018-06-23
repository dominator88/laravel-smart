@extends('Backend::public.layout')
@section('content')
<!-- BEGIN CONTENT BODY -->
{!! csrf_field() !!}
<div class="page-content">
	<!-- BEGIN PAGE BAR -->
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li> <a href="<?=$param['uri']['module']?>">首页</a> <i class="fa fa-circle"></i> </li>
			<li> <span><?=$param['pageTitle']?></span> </li>
		</ul>
	</div>
	<!-- END PAGE BAR -->

	<div class="row" style="margin-top: 16px">
		<!-- Main Portlet Start -->
		<div class="portlet light bordered" id="tablePortlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-settings"></i>
					<span class="caption-subject uppercase"><?=$param['pageTitle']?></span>
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
							<?=form_options($param['status'])?>
						</select>
					</div>
					<!-- 查询状态 end -->
					<button type="submit" class="btn default" id="searchBtn"><i class="fa fa-search"></i> 查询</button>
				</form> <!-- End Search Form -->

        <div class="table-scrollable">
  <table id="dataGrid" class="table table-hover">
    <tr>


<th width="40" data-field="id" >ID</th>
<th width="200" data-field="name" >模块名称</th>
<th width="80" data-field="symbol" >标识</th>
<th width="80" data-field="status" data-formatter="formatStatus">状态</th>
<th width="80" data-field="thumb" >缩略图</th>
<th width="80" data-field="updated_at" ></th>
<th width="80" data-field="version" >版本号</th>
<th width="80" data-field="author" >作者</th>
<th width="80" data-field="displayorder" >排序序号</th>
<th width="180" data-field="created_at" data-formatter="formatDatetime"></th>
<th width="60" data-formatter="formatIcon"></th>
 	<th width="60" data-formatter="optIncome"></th>
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
	<label class="col-md-3 control-label">模块名称</label>
	<div class="col-md-7">
    <input type="text" name="name" placeholder="模块名称" class="form-control"
           data-valid="required" data-tips="请输入正确的模块名称" >
	</div>
</div><!-- end item -->
<!-- start item -->
<div class="form-group">
	<label class="col-md-3 control-label">标识</label>
	<div class="col-md-7">
    <input type="text" name="symbol" placeholder="标识" class="form-control"
           data-valid="required" data-tips="请输入正确的标识" >
	</div>
</div><!-- end item -->
            <!-- start item -->
<div class="form-group">
	<label class="col-md-3 control-label">描述</label>
	<div class="col-md-7">
    <input type="text" name="desc" placeholder="描述" class="form-control"
           data-valid="required" data-tips="请输入正确的描述" >
	</div>
</div><!-- end item -->
<!-- start item -->
<div class="form-group">
	<label class="col-md-3 control-label">排序序号</label>
	<div class="col-md-7">
    <input type="text" name="displayorder" placeholder="排序序号" class="form-control"
           data-valid="required" data-tips="请输入正确的排序序号" >
	</div>
</div><!-- end item -->

<!-- start item -->
<div class="form-group">
	<label class="col-md-3 control-label">状态</label>
	<div class="col-md-7">
		<?=form_radios('status', $param['status'])?>
	</div>
</div><!-- end item -->

<!-- start item -->
<div class="form-group">
	<label class="col-md-3 control-label">缩略图</label>
	<div class="col-md-7">
    <input type="text" name="thumb" placeholder="缩略图" class="form-control"
           data-valid="required" data-tips="请输入正确的缩略图" >
	</div>
</div><!-- end item -->
<!-- start item -->
<div class="form-group">
	<label class="col-md-3 control-label">版本号</label>
	<div class="col-md-7">
    <input type="text" name="version" placeholder="版本号" class="form-control"
           data-valid="required" data-tips="请输入正确的版本号" >
	</div>
</div><!-- end item -->
<!-- start item -->
<div class="form-group">
	<label class="col-md-3 control-label">作者</label>
	<div class="col-md-7">
    <input type="text" name="author" placeholder="作者" class="form-control"
           data-valid="required" data-tips="请输入正确的作者" >
	</div>
</div><!-- end item -->
          </div>
          <div class="form-actions" style="margin-bottom: 60px;">
            <hr>
            <div class="row">
              <div class="col-md-offset-3 col-md-7">
                <button class="btn default btn-lg" id="closePortletBtn" type="button" ><i class="fa fa-arrow-left"></i> 返回</button>
                <button class="btn red btn-lg" id="submitFormBtn" type="button"><i class="fa fa-check"></i> 提交</button>
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