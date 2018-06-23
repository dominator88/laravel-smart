@extends('backend::public.layout')
@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
	<!-- BEGIN PAGE BAR -->
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li><a href="<?=$param['uri']['module']?>">首页</a> <i class="fa fa-circle"></i></li>
			<li><span><?=$param['pageTitle']?></span></li>
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
				<nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">

                        <ul class="nav navbar-nav">
                            <li id='diy_nav' data-nav='backend.modulerole.index' ><a href="{{ $param['role_uri'] }}">角色</a></li>
                            <li data-nav='backend.modulefunc.index'><a href="{{ $param['func_uri'] }}">功能</a></li>

                        </ul>

                    </div>
                </nav>
				<div class="table-scrollable">
					<table id="dataGrid" class="table table-hover">
						<tr>
							<th width="40" data-field="id">ID</th>
							<th width="40" data-field="sort">排序</th>
							<th width="180" data-field="name">名称</th>
							<th width="80" data-field="status" data-formatter="formatStatus">状态</th>
							<th width="80" data-field="rank">等级</th>
							<th width="60" data-formatter="optPermission"></th>
							<th width="60" data-formatter="optEdit"></th>
							<th width="60" data-formatter="optDelete"></th>
							<th>&nbsp;</th>
						</tr>
					</table>
				</div>
			</div>
		</div> <!-- Main Portlet Start -->
	</div>
</div>

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
							{!! csrf_field() !!}
							<div class="form-body">

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
										<?=form_radios('status', $param['status'])?>
									</div>
								</div><!-- end item -->
								<!-- start item -->
								<div class="form-group">
									<label class="col-md-3 control-label">描述</label>
									<div class="col-md-7">
										<input type="text" name="desc" placeholder="描述" class="form-control">
									</div>
								</div><!-- end item -->
								<!-- start item -->
								<div class="form-group">
									<label class="col-md-3 control-label">等级</label>
									<div class="col-md-7">
										<select class="form-control" name="rank">
											<?=form_options($param['rank'])?>
										</select>
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
<!-- END CONTENT BODY -->

<!-- START PERMISSION MODAL -->
<div class="modal fade" id="permissionModal">
	<input type="hidden" name='module' value='{{ $param['module_name'] }}' />
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="permissionLabel"></h4>
			</div>
			<div class="modal-body" id="permissionBody">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> 关闭</button>
				<button type="button" class="btn btn-primary" id="permissionSubmitBtn"><i class="fa fa-check"></i> 提交</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /#END PERMISSION MODAL -->
	@stop