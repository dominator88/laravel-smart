/**
 * Menu JS
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2016-09-12
 */

var MerFunc = {
	token: $('input[name=_token]').val(),
	init: function() {
		//重新设置菜单
		if (!empty(Param.uri.menu)) {
			Layout.setSidebarMenuActiveLink('set', 'a[data-uri="' + Param.uri.menu + '"]');
		}

		//初始化ajax 提示框
		loading.initAjax();

		//初始化页面按钮
		this.initBtn();

		//初始化查询form
		this.initSearchForm();

		//初始化数据表
		this.initGrid();

		this.initGrid2();

	},
	//初始化查询form
	initSearchForm: function() {
		var $searchForm = $('#searchForm');
		$searchForm.reloadForm(Param.query);

		//查询按钮
		$('#searchBtn').on('click', function(e) {
			e.preventDefault();

			var $treeGrid = $('#treeGrid');
			var param = $treeGrid.TreeGrid('getParam');

			param = $.extend({}, param, $('#searchForm').serializeObject());
			param.page = 1;
			$treeGrid.TreeGrid('setParam', param);
			$treeGrid.TreeGrid('reload');
			Param.defaultRow.module = param.module
		});
	},

	//显示 modal 
	setPortletShow: function(type) {
		var $tablePortlet = $('#tablePortlet');
		var $addEditModal = $('#addEditModal');
		var $nodePortlet = $('#nodePortlet');
		var $addNodePorlet = $('#addNodePorlet');

		if (type == 'add') {
			$addEditModal.modal('show');
			$addEditModal.find('.caption-subject').html('新建' + Param.pageTitle);
		} else if (type == 'edit') {
			$addEditModal.modal('show');
			$addEditModal.find('.caption-subject').html('编辑' + Param.pageTitle);
		} else if (type == 'node') {
			$tablePortlet.slideUp('fast');
			$nodePortlet.show();
		} else if (type == 'addNode') {
			$addNodePorlet.show();
		}
	},

	//关闭 modal
	setPortletHide: function() {
		$('#tablePortlet').slideDown('fast');
		$('#addEditModal').modal('hide');
		$('#nodePortlet').slideUp('fast');
	},

	//初始化各种按钮
	initBtn: function() {
		var self = this;

		//打开添加框
		$('#addNewBtn').on('click', function(e) {
			e.preventDefault();
			self.setPortletShow('add');

			var $form = $('#addEditForm');
			$form.reloadForm(Param.defaultRow);


			$form.attr('action', Param.uri.insert);
		});

		//编辑按钮
		$(document).on('click', '.editBtn', function(e) {
			e.preventDefault();
			self.setPortletShow('edit');

			var id = $(this).data('id');
			var row = $('#treeGrid').TreeGrid('getRow', id);
			var $form = $('#addEditForm');

			$form.reloadForm(row);


			$form.attr('action', Param.uri.update + '/' + row.id);
		});

		//删除一行
		$(document).on('click', '.destroyBtn', function(e) {
			e.preventDefault();
			var id = $(this).data('id');
			self.delData(id);
		});

		$('#destroySelectBtn').on('click', function(e) {
			e.preventDefault();
			var ids = $('.checker:checked').serializeJSON().selectChecker;
			if (empty(ids)) {
				tips.error('请选择要删除的记录');
				return;
			}
			self.delData(ids);
		});

		//提交添加编辑窗
		$('#submitFormBtn').on('click', function(e) {
			e.preventDefault();
			var $form = $('#addEditForm');

			if ($form.validForm()) {
				var data = $form.serializeObject();

				data._token = self.token;
				$.post($form.attr('action'), data)
					.fail(function(res) {
						tips.error(res.responseText);
					})
					.done(function(res) {
						if (res.code == 1001) {
							//需要登录
							tips.error('请先登录');
						} else if (res.code != 0) {
							tips.error(res.msg);
						} else {
							tips.success(res.msg);
							$('#treeGrid').TreeGrid('reload');
							self.setPortletHide();
						}
					});
			}
		});

		$('#submitFormBtn3').on('click', function(e) {
			e.preventDefault();
			var $form = $('#addNodeForm');

			if ($form.validForm()) {
				var data = $form.serializeObject();

				data._token = self.token;
				$.post($form.attr('action'), data)
					.fail(function(res) {
						tips.error(res.responseText);
					})
					.done(function(res) {
						if (res.code == 1001) {
							//需要登录
							tips.error('请先登录');
						} else if (res.code != 0) {
							tips.error(res.msg);
						} else {
							tips.success(res.msg);
							$('#dataGrid2').TableGrid('reload');
							self.setPortletHide();
						}
					});
			}
		});

		//关闭添加编辑窗
		$('#closePortletBtn').on('click', function(e) {
			e.preventDefault();
			self.setPortletHide();
		});

		$('#closePortletBtn2').on('click', function(e) {
			e.preventDefault();
			self.setPortletHide();
		});

		$('#closePortletBtn3').on('click', function(e) {
			e.preventDefault();
			self.setPortletHide();
		});

		//打开权限窗口
		$(document).on('click', '.privilegeBtn', function(e) {
			e.preventDefault();

			var id = $(this).data('id');
			var data = $('#treeGrid').TreeGrid('getRow', id);
			var privilege = data.privilege;
			var $form = $('#privilegeForm');
			$form[0].reset();

			//获取当前页面下所有的权限节点
			data = {
				'menu_id': id,
				'module': data.module,
				_token: self.token
			}

			$('#privilegeNode').empty();
			$.post(Param.uri.permissionNodeGetPrivilege, data)
				.fail(function(res) {
					tips.error(res.responseText);
				})
				.done(function(res) {
					rows = res.data.rows;

					$.each(rows, function(index, item) {

						node = '<div class="checkbox"><label><input type="checkbox" name="node_id[]" value="' + item.id + '">' + item.name + '</label> </div>';
						$(node).appendTo('#privilegeNode');
					});

					if (!empty(privilege)) {
						$.each(privilege, function(index, item) {
							$form.find('input[value="' + item.node_id + '"]').prop('checked', true);
						});
					}

					$form.attr('action', Param.uri.updatePrivilege + '/' + id);
					$('#privilegeModal').modal('show');
				});


		});

		$(document).on('click', '.nodeBtn', function(e) {
			e.preventDefault();
			self.setPortletShow('node');
			var id = $(this).data('id')
			var row = $('#treeGrid').TreeGrid('getRow', id);
			data = {
				source_id: row.id,
				module: row.module,
				symbol: row.id + '.'
			}
			console.log(row);
			var $form = $('#addNodeForm')
			$form.reloadForm(data)

			$dataGrid2 = $('#dataGrid2');
			$dataGrid2.TableGrid('setParam', {
				//应用id
				source_id: id,
				type: 'func',
				getall: true
			});
			$dataGrid2.TableGrid('reload');


		});

		//打开添加框
		$('#addNodeBtn').on('click', function(e) {
			e.preventDefault();
			self.setPortletShow('addNode');

			var $form = $('#addNodeForm');
			//	$form.reloadForm(Param.defaultRow);


			$form.attr('action', Param.uri.nodeInsert);
		});

		$(document).on('click', '.editNodeBtn', function(e) {
			e.preventDefault();
			self.setPortletShow('addNode');
			var id = $(this).data('id')
			var row = $('#dataGrid2').TableGrid('getRow', id);
			var $form = $('#addNodeForm')
			$form.reloadForm(row)
			$form.attr('action', Param.uri.nodeUpdate + '/' + id)
		});


		//更新权限
		$('#submitPrivilegeFormBtn').on('click', function(e) {
			e.preventDefault();

			var $form = $('#privilegeForm');
			var uri = $form.attr('action');
			var data = $form.serializeObject();

			$.post(uri, data)
				.fail(function(res) {
					tips.error(res.responseText);
				})
				.done(function(res) {
					if (res.code == 403) {
						//需要登录
						tips.error('请先登录');
					} else if (res.code != 0) {
						tips.error(res.msg);
					} else {
						tips.success(res.msg);
						$('#treeGrid').TreeGrid('reload');
						$('#privilegeModal').modal('hide');
					}
				});
		})
	},

	delData: function(ids) {
		var data = {
			ids: ids,
			_token: this.token
		};

		sure.init('是否删除?', function() {
			$.post(Param.uri.destroy, data)
				.fail(function(res) {
					tips.error(res.responseText);
				})
				.done(function(res) {
					if (res.code == 1001) {
						//需要登录
						tips.error('请先登录');
					} else if (res.code != 0) {
						tips.error(res.msg);
					} else {
						tips.success(res.msg);
						$('#treeGrid').TreeGrid('reload');
					}
				});
		});
	},


	//初始化tree grid
	initGrid: function() {
		var self = this;
		var uri = Param.uri.this + '?' + $.param(Param.query);
		history.replaceState(Param.query, '', uri);

		$('#treeGrid').TreeGrid({
			uri: Param.uri.read,
			field: 'name', //显示箭头的字段
			param: Param.query, //查询参数
			expandAll: true,
			rowStyle: function(row) {
				if (row.status == 0) {
					return 'warning';
				}
			},
			loadSuccess: function(rows, settings) {
				var options = '<option value="0" selected>根目录</option>';
				options += form_options_rows(rows, settings);
				$('select[name="pid"]').html(options);

				var oldUri = window.location.href;
				var uri = Param.uri.this + '?' + $.param(settings.param);
				if (oldUri == uri) {
					return false;
				}

				var params = $.getUrlParams(window.location.href);
				history.pushState(params, '', oldUri);
				history.replaceState(settings.param, '', uri);
			}
		});
	},
	initGrid2: function() {
		var self = this;
		var uri = Param.uri.this + '?' + $.param(Param.query);
		history.replaceState(Param.query, '', uri);

		$('#dataGrid2').TableGrid({
			uri: Param.uri.nodeRead,
			selectAll: true,
			pagination: false,
			param: Param.query,
			rowStyle: function(row) {
				if (row.status == 0) {
					return 'warning';
				}
			},
			loadSuccess: function(rows, settings) {
				var oldUri = window.location.href;
				var uri = Param.uri.this + '?' + $.param(settings.param);
				if (oldUri == uri) {
					return false;
				}

				var params = $.getUrlParams(window.location.href);
				history.pushState(params, '', oldUri);
				history.replaceState(settings.param, '', uri);
			}
		});
	}


};

//pop state 事件
window.onpopstate = function(event) {
	if (event && event.state) {
		$('#searchForm').reloadForm(event.state);
		var $treeGrid = $('#treeGrid');
		$treeGrid.TreeGrid('setParam', event.state);
		$treeGrid.TreeGrid('reload');
	}
};

var formatName = function(value, row) {
	var html = value;
	if (!empty(row.icon)) {
		html = '<i class="' + row.icon + '"></i> ' + html;
	}
	return html;
};

var formatIsFunc = function(value) {
	return Param.isFunc[value];
};

var formatIsMenu = function(value) {
	return Param.isMenu[value];
};

var optPrivilege = function(value, row) {
	return '<a href="#" data-id="' + row.id + '" class="btn btn-sm blue privilegeBtn"><i class="fa fa-key"></i> 权限</a>';
};

var optNode = function(value, row) {
	return '<a href="javascript:void" data-id="' + row.id + '" class="btn btn-sm green nodeBtn"><i class="fa fa-key"></i> 节点</a>';
}

var optEditNode = function(value, row) {
	return '<a href="#" data-id="' + row.id + '" class="btn btn-sm grey-cascade editNodeBtn"><i class="fa fa-key"></i> 编辑</a>';
}