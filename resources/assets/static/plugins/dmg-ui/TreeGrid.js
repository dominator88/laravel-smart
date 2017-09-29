/**
 * FIT Tree Grid
 *
 * @author Zix
 * @version 2.0 , 2016-05-06
 */

;(function ($) {

	//保存数据
	//var fit_tree_grid = [];

	//构建页面数据
	var _initTreeGrid = function ($this) {
		var settings = $this.data('tree_grid');

		//重新渲染表头
		var org_html = "<thead>" + $this.find('tr').html() + "</thead><tbody></tbody>";
		$this.html(org_html);

		$(document).on('click' , '.level-fa' , function (e) {
			e.preventDefault();
			var old_class = 'fa-chevron-down';
			var new_class = 'fa-chevron-right';
			var expand = false;
			if ( $(this).hasClass('fa-chevron-right') ) {
				old_class = 'fa-chevron-right';
				new_class = 'fa-chevron-down';
				expand = true;
			}
			$(this).removeClass(old_class).addClass(new_class);
			var $tr = $(this).parents('tr');
			var $tbody = $(this).parents('tbody');
			var id = $tr.data('id');
			if ( expand ) {
				expandTr($tbody , id);
			} else {
				closeTr($tbody , id);
			}
		});

		//加载数据
		loadData($this);
	};

	function expandTr(tbody , pid) {
		if ( tbody.find('tr[data-pid="' + pid + '"]').length > 0 ) {
			tbody.find('tr[data-pid="' + pid + '"]').each(function () {
				$(this).removeClass('hide')
				       .find('.fa-chevron-right')
				       .removeClass('fa-chevron-right')
				       .addClass('fa-chevron-down');
				expandTr(tbody , $(this).data('id'));
			});
		}
	}

	function closeTr(tbody , pid) {
		if ( tbody.find('tr[data-pid="' + pid + '"]').length > 0 ) {
			tbody.find('tr[data-pid="' + pid + '"]').each(function () {
				$(this).addClass('hide')
				       .find('.fa-chevron-down')
				       .removeClass('fa-chevron-down')
				       .addClass('fa-chevron-right');
				closeTr(tbody , $(this).data('id'));
			});
		}
	}

	//加载并渲染
	function loadData( $this ) {
		var settings = $this.data('tree_grid');

		//取数据
		$.ajax({
			url : settings.uri ,
			data : settings.param ,
			type : "GET" ,
			dataType : 'JSON' ,
			error : function (XHR) {
				tips.error(XHR.status + ' ' + XHR.statusText);
			} ,
			success : function (ret) {
				var bodyHtml = [];
				var col_span = $this.find('th').length;

				if ( ret.code == 401 ) {
					tips.error('请先登录');
					bodyHtml.push('<tr class="danger"><td align="center" colspan="' + col_span + '">请先登录 Orz</td></tr>');
				}
				if ( ret.code == 0 ) {
					//保存值
					$this.data('rows' , ret.data.rows );

					//生成显示的数据
					if ( ret.data.rows.length == 0 ) {
						bodyHtml.push('<tr class="danger"><td align="center" colspan="' + col_span + '">暂无数据 @_@</td></tr>');
					} else {
						_makeBody( $this , bodyHtml , ret.data.rows , settings );
					}

					//加载完成后的操作
					settings.loadSuccess( ret.data.rows , settings);
				}
				//渲染到页面
				$this.find('tbody').html(bodyHtml.join(''));
			}
		});
	}

	function _makeBody( $this , bodyHtml , rows , settings ) {
		var nodeField = settings['nodeField'];
		var expandAll = settings.expandAll;

		for ( var i in rows ) {
			if ( ! rows.hasOwnProperty(i) ) {
				continue;
			}
			var row = rows[i];
			//行样式
			var rowStyle = settings.rowStyle(row , i);
			rowStyle = empty(rowStyle) ? '' : rowStyle;

			//是否隐藏
			var is_hide = '';
			if ( row.level > 1 && ! settings.expandAll ) {
				is_hide = 'hide';
			}

			bodyHtml.push('<tr class="' + rowStyle + ' ' + is_hide + ' " data-id="' + row.id + '" ' +
			               'data-pid="' + row.pid + '" data-level="' + row.level + '">');
			//每格数据及样式
			$this.find('th').each(function () {
				var self = $(this);
				var val = '';
				var td_class = '';
				var field = self.data('field');
				var formatter = self.data('formatter');
				var sort = self.data('sort');

				val = isset(row[field]) ? row[field] : '&nbsp;';
				//是否有格式化方法
				if ( ! empty(formatter) ) {
					val = eval(formatter)(val , row , i);
				}
				if ( settings.field == field ) {
					td_class = 'level-' + row.level;
					if ( isset(row[nodeField]) ) {
						var icon = settings.expandAll ? settings.expandIcon : settings.collapseIcon;
						val = '<i class="' + icon + ' level-fa font-blue"></i>' + val;
					} else {
						val = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + val;
					}
				}

				bodyHtml.push('<td class="' + td_class + '">' + val + '</td>');
			});
			bodyHtml.push('</tr>');
			if ( isset(row.children) ) {
				_makeBody( $this , bodyHtml , row[nodeField] , settings )
			}
		}
	}

	//插件的方法
	var methods = {
		//初始化
		init : function (options) {
			return this.each(function () {
				var $this = $(this);
				var settings = $this.data('table_grid');
				if ( typeof(settings) == 'undefined' ) {
					//默认值
					var defaults = {
						uri : '' ,
						field : 'name' ,  //缩进的字段名
						nodeField : 'children' , //下级的字段名
						expandAll : true , //初始化时是否展开
						collapseIcon : 'fa fa-chevron-right' , //折叠的图标
						expandIcon : 'fa fa-chevron-down' , //展开的图标
						param : {
							keyword : '' ,
							status : ''
						} ,
						rowStyle : function () {
							return '';
						} ,
						loadSuccess : function () {
						}
					};
					settings = $.extend({} , defaults , options);

				} else {
					settings = $.extend({} , settings , options);
				}

				$this.data('tree_grid' , settings);
				//初始化tree grid
				_initTreeGrid($this);
			});
		} ,

		//重新加载
		reload : function () {
			loadData( $(this) );
		} ,

		getParam : function () {
			var settings = $(this).data('tree_grid');
			return settings['param'];
		} ,

		setParam : function (param ) {
			var settings = $(this).data('tree_grid');
			settings['param'] = param ;
			$(this).data('tree_grid' , settings );
		} ,

		//取单行数据
		getRow : function (id) {
			var rows = $(this).data('rows');
			var settings = $(this).data('tree_grid');
			return getRowById(id , rows , settings);
		} ,

		//取多行数据
		getRows : function () {
			return $(this).data('rows');
		}

	};

	function getRowById(id , rows , settings) {
		for ( var i = 0 ; i < rows.length ; i ++ ) {
			var row = rows[i];

			if ( row.id == id ) {
				return row;
			}
			if ( isset(row[settings.nodeField]) ) {
				var result = getRowById(id , row[settings.nodeField] , settings);
				if ( ! empty(result) ) {
					return result;
				}
			}
		}
	}

	//插件入口
	$.fn.TreeGrid = function () {
		var method = arguments[0];
		if ( methods[method] ) {
			method = methods[method];
			arguments = Array.prototype.slice.call(arguments , 1);
		} else if ( typeof(method) == 'object' || ! method ) {
			method = methods.init;
		} else {
			return this;
		}

		return method.apply(this , arguments);
	}
})(jQuery);
