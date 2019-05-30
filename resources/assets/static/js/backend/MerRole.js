/**
 * MerRole JS
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2016-09-12
 */

var MerRole = {
	token: $('input[name=_token]').val(),
	config: {},
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


	},
	//初始化查询form
	initSearchForm: function() {
		var $searchForm = $('#searchForm');
		$searchForm.reloadForm(Param.query);

		//查询按钮
		$('#searchBtn').on('click', function(e) {
			e.preventDefault();

			var $dataGrid = $('#dataGrid');
			var param = $dataGrid.TableGrid('getParam');

			param = $.extend({}, param, $('#searchForm').serializeObject());
			param.page = 1;
			$dataGrid.TableGrid('setParam', param);
			$dataGrid.TableGrid('reload');
			Param.defaultRow.module = param.module
		});
	},

	//显示 modal
	setPortletShow: function(type) {
		var $addEditModal = $('#addEditModal');

		$addEditModal.modal('show');
		if (type == 'add') {
			$addEditModal.find('.caption-subject').html('新增 ' + Param.pageTitle);
		} else if (type == 'edit') {
			$addEditModal.find('.caption-subject').html('编辑 ' + Param.pageTitle);
		}
	},

	//关闭 modal
	setPortletHide: function() {
		$('#addEditModal').modal('hide');
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
			var row = $('#dataGrid').TableGrid('getRow', id);
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

				$.post($form.attr('action'), data)
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
							$('#dataGrid').TableGrid('reload');
							self.setPortletHide();
						}
					});
			}
		});

		//打开权限窗口
		$(document).on('click', '.permissionBtn', function(e) {
			e.preventDefault();

			$('#permissionBody').empty();
			var id = $(this).data('id');
			var roleData = $('#dataGrid').TableGrid('getRow', id);
			self.config.roleId = id;

			var url = Param.uri.getPermission;
			var data = {
				roleId: id,
				module: roleData.module,
				_token: self.token
			};
			$.post(url, data, function(res) {
				$('#permissionLabel').html('[' + roleData.name + ']的权限');
				$('#permissionModal').modal('show');
				$('#permissionBody').html(res);
				var url = Param.uri.getPrivilegeData;

				$.post(url, data, function(res) {
					self.initPermission(res);
				});
			});
		});

		//关闭添加编辑窗
		$('#closePortletBtn').on('click', function(e) {
			e.preventDefault();
			self.setPortletHide();
		});

		//功能权限全选或取消
		$(document).on('click', '.func', function(e) {
			e.preventDefault();

			var this_node = $(this).parent('.func-node');
			var opt = this_node.find('.func-opt');
			var opt_i = this_node.find('.func-opt > i');

			if ($(this).hasClass('disabled') || $(this).hasClass('notall')) {
				//全选
				$(this).removeClass('active notall disabled').addClass('active');
				opt.removeClass('active notall disabled').addClass('active');
				opt_i.removeClass('fa-square-o').addClass('fa-check-square-o');

				//将下级权限置为可查看
				self.setSubOptCheck(this_node);
			} else {
				//全不选
				$(this).removeClass('active notall disabled').addClass('disabled');
				opt.removeClass('active notall disabled').addClass('disabled');
				opt_i.removeClass('fa-check-square-o').addClass('fa-square-o');

				//将下级权限置为全不选
				self.setSubOptDisabled(this_node);
			}
		});

		//功能权限单选或取消
		$(document).on('click', '.func-opt', function(e) {
			e.preventDefault();
			var this_i = $(this).find('i');
			var this_node = $(this).parents('.func-node');

			if ($(this).hasClass('disabled')) {
				$(this).removeClass('disabled').addClass('active');
				this_i.removeClass('fa-square-o').addClass('fa-check-square-o');
			} else if ($(this).hasClass('active')) {
				$(this).removeClass('active').addClass('disabled');
				this_i.removeClass('fa-check-square-o').addClass('fa-square-o');
			}

			if ($(this).html().indexOf('查看') > 0) {
				if ($(this).hasClass('disabled')) {
					self.setSubOptDisabled(this_node);
				} else {
					self.setSubOptCheck(this_node);
				}
			}

			this_node.find('.func').removeClass('active notall disabled');

			if (this_node.find('.func-opt-row > .active').length == 0) {
				this_node.find('.func').addClass('disabled'); //未选择
			} else if (this_node.find('.func-opt').length == this_node.find('.func-opt-row > .active').length) {
				this_node.find('.func').addClass('active'); //全选
			} else {
				this_node.find('.func').addClass('notall'); //部分选择
			}
		});

		//提交权限
		$(document).on('click', '#permissionSubmitBtn', function(e) {
			e.preventDefault();

			var data = {
				roleId: self.config.roleId,
				privilegeArr: [],
				_token: self.token
			};

			var $active = $('.func-opt-row .active');
			var len = $active.length;
			for (var i = 0; i < len; i++) {
				data.privilegeArr.push($active.eq(i).data('id'));
			}
			var url = Param.uri.updatePermission;

			$.post(url, data, function(ret) {
				if (ret.code != 0) {
					tips.error(ret.msg);
					return;
				}
				tips.success(ret.msg);
				$('#permissionBody').empty();
				$('#permissionModal').modal('hide');
			});
		});


	},

	delData: function(ids) {
		var self = this;
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
						$('#dataGrid').TableGrid('reload');
					}
				});
		});
	},

	//初始化grid
	initGrid: function() {
		var self = this;
		var uri = Param.uri.this + '?' + $.param(Param.query);
		history.replaceState(Param.query, '', uri);

		$('#dataGrid').TableGrid({
			uri: Param.uri.read,
			selectAll: true,
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
	},

	//将下级权限置为可查看
	setSubOptCheck: function(this_node) {
		var self_tree = this_node.parent('.func-tree');
		var _sub_func_node = self_tree.find('.sub-permission .func-node');
		_sub_func_node.each(function() {
			var self_sub_func_node = $(this);
			$(this).find('.func-opt').each(function() {
				if ($(this).html().indexOf('查看') > 0) {
					self_sub_func_node.find('.func').removeClass('active notall disabled').addClass('notall');
					$(this).removeClass('active notall disabled').addClass('active');
					var self_sub_func_opt_i = $(this).find('i');
					self_sub_func_opt_i.removeClass('fa-square-o').addClass('fa-check-square-o');
				}
			});
		});
	},

	//将下级权限置为全不选
	setSubOptDisabled: function(this_node) {
		var self_tree = this_node.parent('.func-tree');
		var _sub_func_node = self_tree.find('.sub-permission .func-node');
		_sub_func_node.each(function() {
			$(this).find('.func').removeClass('active notall disabled').addClass('disabled');
			$(this).find('.func-opt').removeClass('active notall disabled').addClass('disabled');
			$(this).find('.func-opt > i').removeClass('fa-check-square-o').addClass('fa-square-o');
		});
	},

	//初始化
	initPermission: function(privilegeData) {
		$('.func').addClass('disabled');
		$('.func-opt').addClass('disabled');

		for (var i = 0; i < privilegeData.length; i++) {
			$obj = $('.func-opt[data-id="' + privilegeData[i].privilege_id + '"]');
			$obj.removeClass('disabled').addClass('active');
			$obj.find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
		}

		$('.func-node').each(function(index) {
			var func_id = $(this).data('id');

			var total_len = $(this).find('.func-opt').length;
			var active_len = $(this).find('.active').length;
			if (total_len == active_len) {
				$(this).find('.func').removeClass('disabled').addClass('active');
			} else if (total_len > active_len && active_len > 0) {
				$(this).find('.func').removeClass('disabled').addClass('notall');
			}
		});
	},

};

//pop state 事件
window.onpopstate = function(event) {
	if (event && event.state) {
		$('#searchForm').reloadForm(event.state);
		var $dataGrid = $('#dataGrid');
		$dataGrid.TableGrid('setParam', event.state);
		$dataGrid.TableGrid('reload');
	}
};

var optPermission = function(value, row) {
	return '<a class="btn btn-sm btn-primary permissionBtn" data-id="' + row.id + '" href="javascript:;" >' +
		'<i class="fa fa-check-square-o"></i> 授权</a>';
};