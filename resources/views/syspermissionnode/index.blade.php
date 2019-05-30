@extends('backend::public.layout')
@section('content')
<!-- BEGIN CONTENT BODY -->
{!! csrf_field() !!}
<div class="page-content">
	<!-- BEGIN PAGE BAR -->
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li> <a href="<?= $param['uri']['module'] ?>">首页</a> <i class="fa fa-circle"></i> </li>
			<li> <span><?= $param['pageTitle'] ?></span> </li>
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
							<th width="40" data-field="id" ></th>
							<th width="200" data-field="name" >节点名称</th>
							<th width="80" data-field="module" >模块</th>
							<th width="80" data-field="type" >权限类型</th>
							<th width="80" data-field="symbol" >标识</th>
							<th width="80" data-field="status" data-formatter="formatStatus">状态</th>
							<th width="180" data-field="created_at" data-formatter="formatDatetime"></th>
							<th width="80" data-field="updated_at" ></th>
							<th width="60" data-formatter="optEdit"></th>
							<th width="60" data-formatter="optDelete"></th>
							<th>&nbsp;</th>
						</tr>
					</table>
				</div>

			</div>
		</div> <!-- Main Portlet Start -->

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
											<label class="col-md-3 control-label">类型</label>
											<div class="col-md-7">
												<input type="text" name="type" placeholder="类型" class="form-control"
												data-valid="required" data-tips="请输入正确的类型">
											</div>
										</div><!-- end item -->
										<!-- start item -->
										<div class="form-group">
											<label class="col-md-3 control-label">symbol</label>
											<div class="col-md-7">
												<input type="text" name="symbol" placeholder="symbol" class="form-control">
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

	</div>
</div>


<!-- END CONTENT BODY -->
@stop