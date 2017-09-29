(function($) {
	var doFill = function( $this , data ) {
		//查询 下级全部含有 data-field 的 dom
		$this.find('div[data-field],textarea[data-field],input[data-field]').each(function(){
			var field = $(this).data('field');
			var formatter = $(this).data('formatter');
			var value =  '' ;
			//如果有值
			if ( isset( data[field] ) ) {
				value = data[field] ;
			}
			//如果有格式化函数
			if ( !empty( formatter ) ) {
				value = eval(formatter)( value , data );
			}

			if( $(this)[0].type == 'text' || $(this)[0].type == 'textarea' ) {
				$(this).val( value );
			} else {
				$(this).html( value );
			}
		});
	};

	var methods = {
		init: function(options) {
			return this.each(function() {
				var $this = $(this);
				var settings = $this.data('AutoFill');

				if(typeof(settings) == 'undefined') {
					var defaults = {
						data: 'value',
						onSomeEvent: function() {}
					};
					settings = $.extend({}, defaults, options);
				} else {
					settings = $.extend({}, settings, options);
				}
				$this.data('AutoFill', settings);

				// 代码在这里运行
				doFill( $this , settings.data );
			});
		},

		reload: function( data ) {
			doFill( $(this) , data );
		},

		reset : function() {
			//清除
			$(this).find('div[data-field],textarea[data-field],input[data-field]').each(function(){
				if( $(this)[0].type == 'text' || $(this)[0].type == 'textarea') {
					$(this).val( '' );
				} else {
					$(this).html( '' );
				}
			});
		}
	};

	$.fn.AutoFill = function() {
		var method = arguments[0];

		if(methods[method]) {
			method = methods[method];
			arguments = Array.prototype.slice.call(arguments, 1);
		} else if( typeof(method) == 'object' || !method ) {
			method = methods.init;
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.pluginName' );
			return this;
		}

		return method.apply(this, arguments);

	}

})(jQuery);