/**
 * MerFunc JS
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2016-09-12
 */

var MerFunc = {
	token : $('input[name=_token]').val(),
	init : function () {
		//重新设置菜单
		if ( ! empty(Param.uri.menu) ) {
			Layout.setSidebarMenuActiveLink('set' , 'a[data-uri="' + Param.uri.menu + '"]');
		}

		//初始化ajax 提示框
		loading.initAjax();

		//初始化页面按钮
		this.initBtn();


		//初始化数据表
		this.initGrid();

	} ,

	//显示 modal
	setPortletShow : function (type) {
		var $addEditModal = $('#addEditModal');

		$addEditModal.modal('show');
		if ( type == 'add' ) {
			$addEditModal.find('.caption-subject').html('新建' + Param.pageTitle);
		} else if ( type == 'edit' ) {
			$addEditModal.find('.caption-subject').html('编辑' + Param.pageTitle);
		}
	} ,

	//关闭 modal
	setPortletHide : function () {
		$('#addEditModal').modal('hide');
	} ,

	//初始化各种按钮
	initBtn : function () {
		var self = this;

		//打开添加框
		$('#addNewBtn').on('click' , function (e) {
			e.preventDefault();
			self.setPortletShow('add');

			var $form = $('#addEditForm');

			$form.reloadForm(Param.defaultRow);


			$form.attr('action' , Param.uri.insert);
		});

		//编辑按钮
		$(document).on('click' , '.editBtn' , function (e) {
			e.preventDefault();
			self.setPortletShow('edit');

			var id = $(this).data('id');
			var row = $('#treeGrid').TreeGrid('getRow' , id);
			var $form = $('#addEditForm');

			$form.reloadForm(row);


			$form.attr('action' , Param.uri.update + '/' +row.id);
		});

		//删除一行
		$(document).on('click' , '.destroyBtn' , function (e) {
			e.preventDefault();
			var id = $(this).data('id');
			self.delData(id);
		});

		$('#destroySelectBtn').on('click' , function (e) {
			e.preventDefault();
			var ids = $('.checker:checked').serializeJSON().selectChecker;
			if ( empty(ids) ) {
				tips.error('请选择要删除的记录');
				return;
			}
			self.delData(ids);
		});

		//提交添加编辑窗
		$('#submitFormBtn').on('click' , function (e) {
			e.preventDefault();
			var $form = $('#addEditForm');

			if ( $form.validForm() ) {
				var data = $form.serializeObject();
				data['module'] = Param.module;
				data._token = self.token;
				$.post($form.attr('action') , data)
				 .fail(function (res) {
					 tips.error(res.responseText);
				 })
				 .done(function (res) {
					 if ( res.code == 1001 ) {
						 //需要登录
						 tips.error('请先登录');
					 } else if ( res.code != 0 ) {
						 tips.error(res.msg);
					 } else {
						 tips.success(res.msg);
						 $('#treeGrid').TreeGrid('reload');
						 self.setPortletHide();
					 }
				 });
			}
		});

		//关闭添加编辑窗
		$('#closePortletBtn').on('click' , function (e) {
			e.preventDefault();
			self.setPortletHide();
		});

		//打开权限窗口
		$(document).on('click' , '.privilegeBtn' , function (e) {
			e.preventDefault();

			var id = $(this).data('id');
			var data = $('#treeGrid').TreeGrid('getRow' , id);
			var privilege = data.privilege;
			var $form = $('#privilegeForm');
			$form[0].reset();

			if ( ! empty(privilege) ) {
				$.each(privilege , function (index , item) {
					$form.find('input[value="' + item.name + '"]').prop('checked' , true);
				});
			}

			$form.attr('action' , Param.uri.updatePrivilege +'/'+ id);
			$('#privilegeModal').modal('show');
		});

		//更新权限
		$('#submitPrivilegeFormBtn').on('click' , function (e) {
			e.preventDefault();

			var $form = $('#privilegeForm');
			var uri = $form.attr('action');
			var data = $form.serializeObject();

			$.post(uri , data)
			 .fail(function (res) {
				 tips.error(res.responseText);
			 })
			 .done(function (res) {
				 if ( res.code == 403 ) {
					 //需要登录
					 tips.error('请先登录');
				 } else if ( res.code != 0 ) {
					 tips.error(res.msg);
				 } else {
					 tips.success(res.msg);
					 $('#treeGrid').TreeGrid('reload');
					 $('#privilegeModal').modal('hide');
				 }
			 });
		})
	} ,

	delData : function (ids) {
		var data = {
			ids : ids,
			_token : this.token
		};

		sure.init('是否删除?' , function () {
			$.post(Param.uri.destroy , data)
			 .fail(function (res) {
				 tips.error(res.responseText);
			 })
			 .done(function (res) {
				 if ( res.code == 1001 ) {
					 //需要登录
					 tips.error('请先登录');
				 } else if ( res.code != 0 ) {
					 tips.error(res.msg);
				 } else {
					 tips.success(res.msg);
					 $('#treeGrid').TreeGrid('reload');
				 }
			 });
		});
	} ,


	//初始化tree grid
	initGrid : function () {
		var self = this;
		var uri = Param.uri.this + '?' + $.param(Param.query);
		history.replaceState(Param.query , '' , uri);

		$('#treeGrid').TreeGrid({
			uri : Param.uri.read ,
			field : 'name' , //显示箭头的字段
			param : Param.query ,  //查询参数
			rowStyle : function (row) {
				if ( row.status == 0 ) {
					return 'warning';
				}
			} ,
			loadSuccess : function (rows , settings) {
				var options = '<option value="0" selected>根目录</option>';
				options += form_options_rows(rows , settings);
				$('select[name="pid"]').html(options);

				var oldUri = window.location.href;
				var uri = Param.uri.this + '?' + $.param(settings.param);
				if ( oldUri == uri ) {
					return false;
				}

				var params = $.getUrlParams(window.location.href);
				history.pushState(params , '' , oldUri);
				history.replaceState(settings.param , '' , uri);
			}
		});
	}


};

//pop state 事件
window.onpopstate = function (event) {
	if ( event && event.state ) {
		$('#searchForm').reloadForm(event.state);
		var $treeGrid = $('#treeGrid');
		$treeGrid.TreeGrid('setParam' , event.state);
		$treeGrid.TreeGrid('reload');
	}
};

var formatName = function (value , row) {
	var html = value;
	if ( ! empty(row.icon) ) {
		html = '<i class="' + row.icon + '"></i> ' + html;
	}
	return html;
};

var formatIsFunc = function (value) {
	return Param.isFunc[value];
};

var formatIsMenu = function (value) {
	return Param.isMenu[value];
};

var optPrivilege = function (value , row) {
	return '<a href="#" data-id="' + row.id + '" class="btn btn-sm blue privilegeBtn"><i class="fa fa-key"></i> 权限</a>';
};
