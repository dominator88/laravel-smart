/**
 * 区域选择
 *
 * @author Zix
 * @version 1.0 2016-05-18
 */

;(function ($) {

	var areaSelectionTmp =
		'<input id="areaName{index}" class="form-control" type="text" placeholder="区域" disabled>' +
		'<span class="input-group-btn">' +
		'<button id="areaSelectionBtn{index}" class="btn blue" type="button">选择区域</button>' +
		'</span>';

	var areaSelectionModal
		= '<div class="area-selection" id="areaSelection{index}">' +
		  '<div class="pull-right"><a class="area-close-btn" href="#">关闭</a> </div>' +
		  '<div class="tabbable-line">' +
		  '<ul class="nav nav-tabs" id="areaTab{index}">' +
		  '<li class="active"><a id="areaTab1{index}" data-level="1" data-toggle="tab" href="#areaContent1{index}" aria-expanded="true"></a></li>' +
		  '<li><a id="areaTab2{index}" data-level="2" data-toggle="tab" href="#areaContent2{index}" aria-expanded="false">请选择</a></li>' +
		  '<li><a id="areaTab3{index}" data-level="3" data-toggle="tab" href="#areaContent3{index}" aria-expanded="false"></a></li>' +
		  '</ul><div class="tab-content">' +
		  '<div id="areaContent1{index}" class="tab-pane active">' +
		  '<ul class="list-inline list-unstyled">' +
		  '</ul></div>' +
		  '<div id="areaContent2{index}" class="tab-pane">' +
		  '<ul class="list-inline list-unstyled"></ul></div>' +
		  '<div id="areaContent3{index}" class="tab-pane">' +
		  '<ul class="list-inline list-unstyled"></ul></div></div></div></div>';

	var areaItemTmp = '<li><a class="area_item" data-level="{level}" data-id="{id}" href="#">{text}</a> </li>';

	//初始化页面
	var _initAreaSelection = function ($this , index) {

		var settings = $this.data('areaSelection');

		settings.area = [0 , 0 , 0];
		$this.data('areaSelection' , settings);

		//添加选择按钮
		$this.wrap('<div class="input-group"></div>');
		$this.after(areaSelectionTmp.replace(/\{index}/g , index));

		$('#areaName' + index).val(settings.fullAreaName);
		//添加选择弹窗
		var $areaSelection = $('.area-selection');
		if ( $areaSelection.length == 0 ) {
			$('body').append(areaSelectionModal.replace(/\{index}/g , index));
		}

		$areaSelection = $('#areaSelection' + index);

		//选择按钮事件
		var $areaSelectionBtn = $('#areaSelectionBtn' + index);
		$areaSelectionBtn.on('click' , function (e) {
			e.preventDefault();
			$('#areaSelection' + index).show();
			//定位
			var pos = $(this).offset();
			var width = $areaSelection.width();

			$areaSelection.css({
				top : (pos.top + $(this).height() + 15) + 'px' ,
				left : (pos.left - width + 60) + 'px' ,
			});
		});

		//关闭按钮事件
		$('.area-close-btn').on('click' , function (e) {
			e.preventDefault();
			$(this).parents('.area-selection').hide();
		});

		$(document).on('click' , '.area_item' , function (e) {
			e.preventDefault();
			var level = $(this).data('level');
			var id = $(this).data('id');
			var text = $(this).html();

			var $areaTab1 = $('#areaTab1' + index);
			var $areaTab2 = $('#areaTab2' + index);
			var $areaTab3 = $('#areaTab3' + index);

			if ( level == 1 ) {
				$areaTab1.html(text);
				$areaTab2.trigger('click');
				setContent2($this , index , id);
			}

			if ( level == 2 ) {
				$areaTab2.html(text);
				$areaTab3.tab('show');
				setContent3($this , index , id);
			}

			if ( level == 3 ) {
				settings.area[2] = id;
				$areaTab3.html(text);
				$(this).val(id);
				$('.area-close-btn').trigger('click');
				var name = $areaTab1.html() + $areaTab2.html() + $areaTab3.html();
				$('#areaName' + index).val(name);
				$this.val(id);
			}
		});

		var $areaTab = $('#areaTab' + index);
		$areaTab.find('a').on('click' , function (e) {
			e.preventDefault();
			var level = $(this).data('level');
			settings = $this.data('areaSelection');

			if ( level == 1 ) {
				setContent1($this , index);
			} else if ( level == 2 ) {
				setContent2($this , index , settings.area[0]);
				settings.area[1] = 0;
				settings.area[2] = 0;
			} else {
				setContent3($this , index , settings.area[1]);
				settings.area[2] = 0;
			}
			$this.data('areaSelection' , settings);
		});

		setContent1($this , index);
	};

	var setContent1 = function ($this , index) {
		var settings = $this.data('areaSelection');
		var $areaContent1 = $('#areaContent1' + index);
		var $areaContent2 = $('#areaContent2' + index);
		var $areaContent3 = $('#areaContent3' + index);
		var $areaTab1 = $('#areaTab1' + index);
		var $areaTab2 = $('#areaTab2' + index);
		var $areaTab3 = $('#areaTab3' + index);

		var id = settings.area[0];

		if ( $areaContent1.find('ul').html() == '' ) {
			//初始化数据
			$.get(settings.uri + 0)
			 .fail(function (res) {
				 tips.error(res.responseText);
			 })
			 .done(function (res) {
				 var html = [];
				 var defaultRow = {};
				 $.each(res.data , function (i , row) {
					 if ( id > 0 && row.id == id ) {
						 defaultRow = row;
					 } else if ( i == 0 ) {
						 defaultRow = row;
					 }

					 html.push(areaItemTmp.replace(/\{id}/g , row.id)
					                      .replace(/\{text}/g , row.text)
					                      .replace(/\{level}/g , row.level));
				 });

				 settings.area[0] = defaultRow.id;
				 $this.data('areaSelection' , settings);

				 $areaTab1.html(defaultRow.text);
				 $areaTab2.html('请选择');
				 $areaTab3.html('');
				 $areaContent1.find('ul').html(html.join(''));
				 $areaContent2.find('ul').html('');
				 $areaContent3.find('ul').html('');

				 setContent2($this , index , defaultRow.id);
			 });
		} else {
			$areaTab2.html('请选择');
			$areaTab3.html('');
			$areaContent2.find('ul').html('');
			$areaContent3.find('ul').html('');
		}
	};

	var setContent2 = function ($this , index , pid) {
		var settings = $this.data('areaSelection');

		var $areaContent2 = $('#areaContent2' + index);
		var $areaContent3 = $('#areaContent3' + index);

		var $areaTab2 = $('#areaTab2' + index);
		var $areaTab3 = $('#areaTab3' + index);

		var id = settings.area[0];
		if ( $areaContent2.find('ul').html() == '' || id != pid ) {
			//初始化数据
			$.get(settings.uri + pid)
			 .fail(function (res) {
				 tips.error(res.responseText);
			 })
			 .done(function (res) {
				 var html = [];
				 $.each(res.data , function (i , row) {
					 html.push(areaItemTmp.replace(/\{id}/g , row.id)
					                      .replace(/\{text}/g , row.text)
					                      .replace(/\{level}/g , row.level));
				 });
				 $areaTab2.html('请选择');
				 $areaTab3.html('');
				 $areaContent2.find('ul').html(html.join(''));
				 $areaContent3.find('ul').html('');
			 });
		}
	};

	var setContent3 = function ($this , index , pid) {
		var settings = $this.data('areaSelection');

		var $areaContent3 = $('#areaContent3' + index);
		var $areaTab3 = $('#areaTab3' + index);

		var id = settings.area[1];

		if ( $areaContent3.find('ul').html() == '' || id != pid ) {
			$.get(settings.uri + pid)
			 .fail(function (res) {
				 tips.error(res.responseText);
			 })
			 .done(function (res) {
				 var html = [];
				 $.each(res.data , function (i , row) {
					 html.push(areaItemTmp.replace(/\{id}/g , row.id)
					                        .replace(/\{text}/g , row.text)
					                        .replace(/\{level}/g , row.level));
				 });
				 $areaTab3.html('请选择');
				 $areaContent3.find('ul').html(html.join(''));
			 });
		}

	};

	//插件的方法
	var methods = {
		//初始化
		init : function (options) {
			return this.each(function (index) {
				var $this = $(this);
				var settings = $this.data('areaSelection');
				if ( typeof(settings) == 'undefined' ) {
					//默认值
					var defaults = {
						uri : '' ,
						fullAreaName : '' ,
						area : [0 , 0 , 0] ,
						onError : function (error) {
							tips.error(error);
						} ,
						onProgress : function (e) {
						} ,
						onSuccess : function () {
						}
					};
					settings = $.extend({} , defaults , options);
					$this.data('areaSelection' , settings);
				} else {
					settings = $.extend({} , settings , options);
				}

				//初始化area_selection
				_initAreaSelection($this , index);
			});
		} ,

		setAreaName : function (fullAreaName) {
			$(this).next().val(fullAreaName);
		}
	};

	//插件入口
	$.fn.AreaSelection = function () {
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
