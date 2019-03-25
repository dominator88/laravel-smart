/**
 * FIT Tree Grid
 *
 * @author Zix
 * @version 2.0 , 2016-05-06
 */

;
(function($) {

	//保存数据
	//var fit_tree_grid = [];

	//构建页面数据
	var _initTreeGrid = function($this) {
		var settings = $this.data('tree_grid');

		//重新渲染表头
		var org_html = "<thead>" + $this.find('tr').html() + "</thead><tbody></tbody>";
		$this.html(org_html);

		$(document).on('click', '.level-fa', function(e) {
			e.preventDefault();
			var old_class = 'fa-chevron-down';
			var new_class = 'fa-chevron-right';
			var expand = false;
			if ($(this).hasClass('fa-chevron-right')) {
				old_class = 'fa-chevron-right';
				new_class = 'fa-chevron-down';
				expand = true;
			}
			$(this).removeClass(old_class).addClass(new_class);
			var $tr = $(this).parents('tr');
			var $tbody = $(this).parents('tbody');
			var id = $tr.data('id');
			if (expand) {
				expandTr($tbody, id);
			} else {
				closeTr($tbody, id);
			}
		});

		//是否有分页
		if (settings.pagination) {
			pagination($this);
		}

		//加载数据
		loadData($this);
	};

	function expandTr(tbody, pid) {
		if (tbody.find('tr[data-pid="' + pid + '"]').length > 0) {
			tbody.find('tr[data-pid="' + pid + '"]').each(function() {
				$(this).removeClass('hide')
					.find('.fa-chevron-right')
					.removeClass('fa-chevron-right')
					.addClass('fa-chevron-down');
				expandTr(tbody, $(this).data('id'));
			});
		}
	}

	function closeTr(tbody, pid) {
		if (tbody.find('tr[data-pid="' + pid + '"]').length > 0) {
			tbody.find('tr[data-pid="' + pid + '"]').each(function() {
				$(this).addClass('hide')
					.find('.fa-chevron-down')
					.removeClass('fa-chevron-down')
					.addClass('fa-chevron-right');
				closeTr(tbody, $(this).data('id'));
			});
		}
	}

	//加载并渲染
	function loadData($this) {
		var settings = $this.data('tree_grid');

		//取数据
		$.ajax({
			url: settings.uri,
			data: settings.param,
			type: "GET",
			dataType: 'JSON',
			error: function(XHR) {
				tips.error(XHR.status + ' ' + XHR.statusText);
			},
			success: function(ret) {
				var bodyHtml = [];
				var col_span = $this.find('th').length;

				if (ret.code == 401) {
					tips.error('请先登录');
					bodyHtml.push('<tr class="danger"><td align="center" colspan="' + col_span + '">请先登录 Orz</td></tr>');
				}
				if (ret.code == 0) {
					//保存值
					$this.data('rows', ret.data.rows);

					//生成显示的数据
					if (ret.data.rows.length == 0) {
						bodyHtml.push('<tr class="danger"><td align="center" colspan="' + col_span + '">暂无数据 @_@</td></tr>');
					} else {
						_makeBody($this, bodyHtml, ret.data.rows, settings);
					}

					//加载完成后的操作
					settings.loadSuccess(ret.data.rows, settings);


					//fit_grid[index].data = ret.data.rows;
					settings.total = ret.data.total;
					settings.totalPage = Math.ceil(ret.data.total / settings.param.pageSize);
					$this.data('table_grid', settings);
					if (settings.pagination == true) {
						set_pagination_info($this); //设置分页
					}
				}
				//渲染到页面
				$this.find('tbody').html(bodyHtml.join(''));
			}
		});
	}

	//分页
	function pagination($this) {
		var settings = $this.data('tree_grid');

		var range = [];

		for (var i in settings.pageSizeRange) {
			if (!settings.pageSizeRange.hasOwnProperty(i)) {
				continue;
			}
			var item = settings.pageSizeRange[i];
			var checked = i == 0 ? 'checked' : '';
			range.push('<option value="' + item + '" ' + checked + '>' + item + '</option>');
		}

		var html_tmp = '' +
			'<div class="row">' +
			'<div class="col-sm-3">第 ' +
			'<button class="btn btn-sm default pagination_prev" type="button"><i class="fa fa-angle-left"></i></button>' +
			'<input type="text" style="text-align:center; margin: 0 5px;" ' +
			'class="form-control input-inline input-sm input-mini pagination_page" value="{page}">' +
			'<button class="btn btn-sm default pagination_next" type="button"><i class="fa fa-angle-right"></i></button>' +
			' 页 , 共 <span class="pagination_total_page">{totalPage}</span> 页</div>' +
			'<div class="col-sm-4">显示 ' +
			'<select class="form-control input-sm input-inline pagination_page_size">{pageSize}</select>' +
			' 行 , 共 <span class="pagination_total">{total}</span> 行</div></div>';

		//渲染到页面
		$this.parent().after(html_tmp.replace(/\{page}/g, settings.param.page)
			.replace(/\{totalPage}/g, settings.totalPage)
			.replace(/\{total}/g, settings.total)
			.replace(/\{pageSize}/g, range.join('')));

		var $pagination = $this.parent().next('.row');

		var $pagination_page = $pagination.find('.pagination_page');
		var $pagination_page_size = $pagination.find('.pagination_page_size');
		var $pagination_prev = $pagination.find('.pagination_prev');
		var $pagination_next = $pagination.find('.pagination_next');
		//页面
		$pagination_page.on('keyup blur', function(e) {
			var page = $.trim($(this).val());
			var event_type = e.type;
			//检查是否填入的数组
			if (!$.isNumeric(page) ||
				page < 1 ||
				page == settings.param.page ||
				page > settings.total_page) {
				e.preventDefault();
				return;
			}
			//检查回车和失去焦点事件
			if ((event_type == 'keyup' && e.keyCode == 13) ||
				event_type == 'blur') {
				settings.param.page = page;
				loadData($this);
			}
			e.preventDefault();
		});

		//向后按钮
		$pagination_prev.on('click', function(e) {
			e.preventDefault();
			settings.param.page--;
			$pagination_page.val(settings.param.page);
			loadData($this);
		});

		//向前按钮
		$pagination_next.on('click', function(e) {
			e.preventDefault();
			settings.param.page++;
			$pagination_page.val(settings.param.page);
			loadData($this);
		});

		//页数输入
		$pagination_page_size.on('change', function() {
			settings.param.page = 1;
			settings.param.pageSize = $(this).val();
			loadData($this);
		});

		$pagination_page_size.val(settings.param.pageSize);
		$pagination_page.val(settings.param.page);
	}

	//设置分页按钮状态
	function set_pagination_info($this) {
		var settings = $this.data('tree_grid');
		var $pagination = $this.parent().next('.row');

		$pagination.find('.pagination_page').val(settings.param.page);
		$pagination.find('.pagination_page_size').val(settings.param.pageSize);
		$pagination.find('.pagination_total').html(settings.total);
		$pagination.find('.pagination_total_page').html(settings.totalPage);

		var $pagination_prev = $pagination.find('.pagination_prev');
		var $pagination_next = $pagination.find('.pagination_next');

		$pagination_prev.attr('disabled', false);
		$pagination_next.attr('disabled', false);

		if (settings.totalPage == 1) {
			$pagination_prev.attr('disabled', true);
			$pagination_next.attr('disabled', true);
			return;
		}

		if (settings.param.page == 1) {
			$pagination_prev.attr('disabled', true);
			return;
		}

		if (settings.param.page == settings.totalPage) {
			$pagination_next.attr('disabled', true);
		}
	}

	function _makeBody($this, bodyHtml, rows, settings) {
		var nodeField = settings['nodeField'];
		var expandAll = settings.expandAll;

		for (var i in rows) {
			if (!rows.hasOwnProperty(i)) {
				continue;
			}
			var row = rows[i];
			//行样式
			var rowStyle = settings.rowStyle(row, i);
			rowStyle = empty(rowStyle) ? '' : rowStyle;

			//是否隐藏
			var is_hide = '';
			if (row.level > 1 && !settings.expandAll) {
				is_hide = 'hide';
			}

			bodyHtml.push('<tr class="' + rowStyle + ' ' + is_hide + ' " data-id="' + row.id + '" ' +
				'data-pid="' + row.pid + '" data-level="' + row.level + '">');
			//每格数据及样式
			$this.find('th').each(function() {
				var self = $(this);
				var val = '';
				var td_class = '';
				var field = self.data('field');
				var formatter = self.data('formatter');
				var sort = self.data('sort');

				val = isset(row[field]) ? row[field] : '&nbsp;';
				//是否有格式化方法
				if (!empty(formatter)) {
					val = eval(formatter)(val, row, i);
				}
				if (settings.field == field) {
					td_class = 'level-' + row.level;
					if (isset(row[nodeField])) {
						var icon = settings.expandAll ? settings.expandIcon : settings.collapseIcon;
						val = '<i class="' + icon + ' level-fa font-blue"></i>' + val;
					} else {
						val = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + val;
					}
				}

				bodyHtml.push('<td class="' + td_class + '">' + val + '</td>');
			});
			bodyHtml.push('</tr>');
			if (isset(row.children)) {
				_makeBody($this, bodyHtml, row[nodeField], settings)
			}
		}
	}

	//插件的方法
	var methods = {
		//初始化
		init: function(options) {
			return this.each(function() {
				var $this = $(this);
				var settings = $this.data('tree_grid');
				if (typeof(settings) == 'undefined') {
					//默认值
					var defaults = {
						uri: '',
						field: 'name', //缩进的字段名
						nodeField: 'children', //下级的字段名
						expandAll: false, //初始化时是否展开
						collapseIcon: 'fa fa-chevron-right', //折叠的图标
						expandIcon: 'fa fa-chevron-down', //展开的图标
						param: {
							keyword: '',
							status: '',
							page: 1,
							pageSize: 10,
						},
						total: 0,
						totalPage: 0,
						loadAfterInit: true,
						pagination: true,
						pageSizeRange: [10, 20, 50, 100],
						rowStyle: function() {
							return '';
						},
						loadSuccess: function() {}
					};
					settings = $.extend({}, defaults, options);

				} else {
					settings = $.extend({}, settings, options);
				}

				$this.data('tree_grid', settings);
				//初始化tree grid
				_initTreeGrid($this);
			});
		},

		//重新加载
		reload: function() {
			loadData($(this));
		},

		getParam: function() {
			var settings = $(this).data('tree_grid');
			return settings['param'];
		},

		setParam: function(param) {
			var settings = $(this).data('tree_grid');
			settings['param'] = param;
			$(this).data('tree_grid', settings);
		},

		//取单行数据
		getRow: function(id) {
			var rows = $(this).data('rows');
			var settings = $(this).data('tree_grid');
			return getRowById(id, rows, settings);
		},

		//取多行数据
		getRows: function() {
			return $(this).data('rows');
		}

	};

	function getRowById(id, rows, settings) {
		for (var i = 0; i < rows.length; i++) {
			var row = rows[i];

			if (row.id == id) {
				return row;
			}
			if (isset(row[settings.nodeField])) {
				var result = getRowById(id, row[settings.nodeField], settings);
				if (!empty(result)) {
					return result;
				}
			}
		}
	}

	//插件入口
	$.fn.TreeGrid = function() {
		var method = arguments[0];
		if (methods[method]) {
			method = methods[method];
			arguments = Array.prototype.slice.call(arguments, 1);
		} else if (typeof(method) == 'object' || !method) {
			method = methods.init;
		} else {
			return this;
		}

		return method.apply(this, arguments);
	}
})(jQuery);