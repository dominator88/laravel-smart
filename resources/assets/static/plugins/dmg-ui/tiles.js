/**
 * JQuery Tiles
 */

;(function ($) {

	//初始化页面
	var _init_tiles = function ($this) {
		var settings = $this.data('tiles');


		load_data($this);
	};

	var load_data = function ($this) {
		var settings = $this.data('tiles');
		//console.log( settings );

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
				if ( ret.code == 0 ) {
					$this.data('rows' , ret.data.rows);

					render($this , ret.data.rows);
					settings.onSuccess( ret.data.rows , settings.param );
				} else if ( ret.code = 401 ) {
					tips.error('请先登录');
					body_html.push('<tr class="danger"><td align="center" colspan="' + col_span + '">请先登录 Orz</td></tr>');
				} else {
					tips.error(ret.msg);
				}
			}
		});
	};

	var render = function ($this , data) {
		var settings = $this.data('tiles');
		var tiles_container = '<div class="tiles ">{tile}</div>';
		var tile_tmp = '<div class="tile {is_image} {color}">' +
                   '<a href="{link}"><div class="tile-body">{icon}</div></a>' +
                   '<div class="tile-object">' +
                   '<div class="name"> {name} </div>' +
                   '<div class="number">{action}</div>' +
                   '</div></div>';
		var html = [];

		for ( var i = 0 ; i < data.length ; i ++ ) {
			var row = data[i];
			var tmp_html = tile_tmp;

			tmp_html = tmp_html.replace('{is_image}' , settings.is_image( row ) ? 'image selected' : '');
			tmp_html = tmp_html.replace('{color}' , settings.color);
			tmp_html = tmp_html.replace('{icon}' , eval(settings.icon)(row , i));
			tmp_html = tmp_html.replace('{name}' , eval(settings.name)(row , i));

			var link = eval(settings.link)(row , i);
			if ( empty(link) ) {
				link = 'javascript:;';
			}
			tmp_html = tmp_html.replace('{link}' , link);

			var action = '';
			for ( var j = 0 ; j < settings.action.length ; j ++ ) {
				action += eval(settings.action[j])(row , i);
			}
			tmp_html = tmp_html.replace('{action}' , action);
			//填充
			html.push(tmp_html);
		}
		$this.html( tiles_container.replace( '{tile}' , html.join('') ));
	};

	//插件的方法
	var methods = {
		//初始化
		init : function (options) {
			return this.each(function () {
				var $this = $(this);
				var settings = $this.data('tiles');
				if ( typeof(settings) == 'undefined' ) {
					//默认值
					var defaults = {
						uri : '' ,
						param : {} ,
						is_image : '' ,
						color : '' ,
						icon : function () { } ,
						name : function () { } ,
						link : function () { } ,
						action : [] ,
						onError : function (error) {
							tips.error(error);
						} ,
						onSuccess : function () { }
					};
					settings = $.extend({} , defaults , options);
					$this.data('tiles' , settings);
				} else {
					settings = $.extend({} , settings , options);
				}

				//初始化grid
				_init_tiles($this);
			});
		} ,

		//重新加载
		reload : function () {
			load_data($(this));
		} ,

		get_param : function () {
			return $(this).data('tiles').param;
		} ,

		set_param : function (param) {
			var settings = $(this).data('tiles');
			settings['param'] = param;
			$(this).data('tiles' , settings);
		} ,

		//取单行数据
		get_row : function (id) {
			var rows = $(this).data('rows');
			for ( var i = 0 ; i < rows.length ; i ++ ) {
				var row = rows[i];
				if ( row.id == id ) {
					return row;
				}
			}
		} ,

		//取多行数据
		get_rows : function () {
			return $(this).data('rows');
		}
	};

	//插件入口
	$.fn.Tiles = function () {
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
