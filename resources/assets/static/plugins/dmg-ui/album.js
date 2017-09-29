/**
 *
 */

;(function ($) {

	var settings = {};

	//初始化页面
	var _init_album = function ($this) {
		settings = $this.data('album');

		var album_btn = '<button type="button" class="btn btn-info open_album_btn"><i class="fa fa-image"></i> 相册选取</button>';

		$this.after(album_btn);
		var $open_album_btn = $this.next('button');
		var album_modal = '<div class="modal fade" id="album_modal">' +
		                  '<div class="modal-dialog">' +
		                  '<div class="modal-content">' +
		                  '<div class="modal-header">' +
		                  '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
		                  '<span aria-hidden="true">&times;</span></button>' +
		                  '<h4 class="modal-title"><i class="icon-picture"></i> 相册</h4></div>' +
		                  '<div class="modal-body">' +
		                  '<ul class="nav nav-tabs">{tab}</ul>' +
		                  '<div class="tab-content">{tab_content}</div></div>' +
		                  '<div class="modal-footer">' +
		                  '<button type="button" class="btn default" data-dismiss="modal">关闭</button>' +
		                  '</div></div></div></div>';
		var album_tab = '<li><a data-toggle="tab" href="#{tab_content_id}" data-catalog="{catalog}" data-tag="{tag}"> {tag} </a></li>';
		var album_tab_content = '<div id="{tab_content_id}" class="tab-pane fade">' +
		                        '<div class="row img_content"></div>' +
		                        '<div class="row">' +
		                        '<div class="col-md-12 text-right" id="{album_pag_id}"></div></div></div>';



		var $album_modal = $('#album_modal');
		if ( $album_modal.length == 0 ) {
			$.get(settings.album_catalog_uri , function (ret) {
				var tab = '';
				var content = '';

				for ( var i = 0 ; i < ret.length ; i ++ ) {
					var tab_content_id = 'album_tab_content_' + ret[i]['id'];
					var album_pag_id = 'album_pag_' + ret[i]['id'];
					tab += album_tab.replace(/\{tab_content_id}/g , tab_content_id)
					               .replace(/\{catalog}/g , ret[i]['id'])
					               .replace(/\{tag}/g , ret[i]['tag']);
					content += album_tab_content.replace(/\{tab_content_id}/g , tab_content_id)
					                           .replace(/\{catalog}/g , ret[i]['id'])
					                            .replace(/\{album_pag_id}/g , album_pag_id)
					                           .replace(/\{tag}/g , ret[i]['tag']);

				}
				var html = album_modal.replace(/\{tab}/g , tab)
				                      .replace(/\{tab_content}/g , content);
				$('body').append( html );
				$album_modal = $('#album_modal');

				//打开album选择 modal
				$open_album_btn.on('click' , function(e){
					e.preventDefault();
					//$album_modal.find('[data-toggle="tab"][data-tag="'+ settings.param.default_album +'"]').tab('show');
					$album_modal.modal('show');
				});

				//切换tab
				$album_modal.find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
					//e.relatedTarget // previous active tab
					//e.target // newly activated tab
					//console.log( e.target );
					//console.log( $(e.target).data('catalog') );
					settings.param.cur_tab = e.target ;
					$this.data('album' , settings );
					var $target = $(e.target);
					var page = $target.data('page');

					if ( empty(page) ) {
						load_album( $this , $target );
					}
				});

				//设置默认的tab
				//console.log(settings.param.default_tag);
				var len = $album_modal.find('a[data-toggle="tab"][data-tag="'+ settings.param.default_tag +'"]').length ;
				if ( len == 0  ) {
					$album_modal.find('a[data-toggle="tab"]').eq(0).tab('show');
				} else {
					$album_modal.find('a[data-toggle="tab"][data-tag="'+ settings.param.default_tag +'"]')
					            .tab('show');
				}


			});
		}

		$(document).on('click' , '.album_item img' , function (e) {
			var img_src =  $(this).attr('src') ;
			sure.init('确认选择吗?' , function(){
				$album_modal.modal('hide');
				settings.onChoose( img_src );
			});
		});
	};

	function load_album( $this , $target ) {
		settings = $this.data('album');
		if ( empty($target) ) {
			$target = $(settings.param.cur_tab);
		}
		var page = $target.data('page');
		page = empty(page) ? 1 : page ;

		var catalog = $target.data('catalog');
		var album_pag_id = 'album_pag_' + $target.data('catalog');
		var $album_pag = $('#' + album_pag_id );

		var data = {
			page : page ,
			page_size : settings.param.page_size ,
			catalog : catalog
		};

		loading.start();
		$.get( settings.album_uri , data , function(ret){
			loading.end();
			$target.data('rows' , ret.rows );
			$target.data('total' , ret.total );
			$target.data('page' , page );
			//tab_content_id
			render_img( $target , ret.rows  );
			if ( $album_pag.html() == '' ) {
				var total_page = Math.ceil( ret.total / settings.param.page_size );
				$album_pag.bootpag({
					total: total_page ,          // total pages
					page: 1,            // default page
					maxVisible: 5,     // visible pagination
					leaps: true         // next/prev leaps through maxVisible
				}).on("page", function(event, num){

					$target.data('page' , num);
					load_album( $this , $target );
					//$("#content").html("Page " + num); // or some ajax content loading...
					// ... after content load -> change total to 10
					//$(this).bootpag({total: 10, maxVisible: 10});
				});
			}
		});
	}

	function render_img( $target , rows ) {
		var album_img = '<div class="col-sm-6 col-md-3">' +
		                '<a class="thumbnail album_item" href="javascript:;"><img src="{icon}"></a>' +
		                '<div class="caption album_item">{img_size}</div>' +
		                '</div>';

		var img_html = '';
		for( var i = 0 ; i < rows.length ; i++ ) {
			img_html += album_img.replace(/\{icon}/g , rows[i]['uri'])
			                     .replace(/\{img_size}/g , rows[i]['img_size']);
		}
		
		var tab_content_id = $target.attr('href');
		$(tab_content_id).find('.img_content').html(img_html);
	}

	//插件的方法
	var methods = {
		//初始化
		init : function (options) {
			return this.each(function () {
				var $this = $(this);
				var settings = $this.data('album');
				if ( typeof(settings) == 'undefined' ) {
					//默认值
					var defaults = {
						uri : '' ,
						btnStyle : 'btn btn-info' ,
						btnText : '<i class="fa fa-image"></i> 相册选取' ,
						album_uri : '' ,
						album_catalog_uri : '' ,
						param : {
							default_tag : ''
						} ,
						onChoose : function () {}
					};
					settings = $.extend({} , defaults , options);
					$this.data('album' , settings);
				} else {
					settings = $.extend({} , settings , options);
				}

				//console.log( settings );
				//初始化grid
				_init_album($this);
			});
		} ,

		reload : function () {

		},

		get_param : function () {
			return $(this).data('album').param;
		} ,

		set_param : function (param) {
			var settings = $(this).data('album');
			settings['param'] = param;
			$(this).data('album' , settings);
		}

	};

	//插件入口
	$.fn.Album = function () {
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
