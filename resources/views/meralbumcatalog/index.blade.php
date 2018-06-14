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
							<a class="btn btn-primary btn-circle" id="add_new_btn" href="javascript:;">
								<i class="fa fa-plus"></i> 新建
							</a>

						</div>
					</div>
					<div class="portlet-body">
						<div id="tiles"></div>
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
									<label class="col-md-3 control-label">目录名称</label>
									<div class="col-md-7">
										<div class="input-icon right">
											<input type="text" name="tag" placeholder="名称" class="form-control"
											       data-valid="required" data-tips="请输入正确的目录名称">
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
									<label class="col-md-3 control-label">封面</label>
									<div class="col-md-7">
										<div id="icon_preview" class="fit-img-preview"></div>
										<button id="icon_upload_btn"></button>
										<input type="text" name="icon" class="hide">
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