/**
 *
 */

;(function ( $ ) {

  var token = $('input[name=_token]').val();
  //var settings = {};
  var thisUploadObj = null;

  //初始化页面
  var _initUpload = function ( $this ) {
    var settings = $this.data( 'uploader' );

    //设置上传按钮样式和文本
    $this.addClass( settings.btnStyle ).html( settings.btnText );

    //模态框的模板
    var modalTmp = '<div class="modal fade" id="uploaderModal">' +
                   '<div class="modal-dialog"><div class="modal-content">' +
                   '<div class="modal-header">' +
                   '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                   '<h4 class="modal-title">文件上传</h4>' +
                   '</div><div class="modal-body">' +
                   '<div class="row">' +
                   '<div class="col-md-12"><div id="uploaderPreview">' +
                   '</div></div></div></div>' +
                   '<div class="modal-footer">' +
                   '<form id="uploaderForm"><input type="file" id="uploaderFile" style="display: none;"></form>' +
                   '<button type="button" class="btn default" data-dismiss="modal"><i class="fa fa-times"></i> 关闭</button>' +
                   '<button type="button" class="btn btn-primary" id="uploaderPickerBtn"><i class="fa fa-image"></i> 选择文件</button>' +
                   '<button type="button" class="btn btn-danger" id="uploaderUploadBtn"><i class="fa fa-upload"></i> 上传</button>' +
                   '</div></div></div></div>';

    var previewImageTmp = '<img class="uploader-img" id="uploaderPreviewImg" src="" alt="图片预览">';
    var preview_file_tmp = '<h4>{filename}</h4>';
    /*
     //上传内容模板
     var image_tmp = '<img class="uploader-img" id="uploader_img_{index}" src="" alt="图片预览">' +
     '<div class="uploader-holder-markup" id="uploader_holder_markup_{index}">' +
     '<div class="uploader-holder-tracker" id="uploader_holder_tracker_{index}">' +
     '<img class="uploader-holder-tracker-img" src="" id="uploader_holder_tracker_img_{index}">' +
     '</div>' +
     '<img class="uploader-holder-img-bg" src="" id="uploader_holder_img_bg_{index}"></div>';
     */

    //添加模态框到页面
    var $modal = $( '#uploaderModal' );
    var $uploaderPreview = $( '#uploaderPreview' );
    var $uploaderPreviewImg = null;
    var $uploaderFile = $( '#uploaderFile' );
    if ( $modal.length == 0 ) {
      $( 'body' ).append( modalTmp );
      $modal = $( '#uploaderModal' );
      $uploaderPreview = $( '#uploaderPreview' );
      $uploaderPreviewImg = null;
      $uploaderFile = $( '#uploaderFile' );

      //选择文件按钮事件
      $( '#uploaderPickerBtn' ).on( 'click' , function ( e ) {
        e.preventDefault();
        if ( settings.cropApi ) {
          settings.cropApi.destroy();
          settings.cropApi = null;
        }
        $uploaderPreview.empty();
        $uploaderFile.trigger( 'click' );
      } );

      //如果选中的文件 则预览
      $uploaderFile.on( 'change' , function ( e ) {
        var settings = thisUploadObj.data( 'uploader' );
        //检查是否支持HTML5上传
        if ( ! window.FileReader ) {
          tips.error( '浏览器不支持HTML5' );
          return;
        }

        //检查是否是图片
        var file = e.target.files[ 0 ];
        if ( ! file.type.match( 'image.*' ) ) {
          //如果是文件
          //$uploader_holder.html(file_tmp.replace( /\{filename}/g , file.name));
        } else {
          //如果是图片开始预览
          $uploaderPreview.html( previewImageTmp );
          $uploaderPreviewImg = $( '#uploaderPreviewImg' );
          var reader = new FileReader();
          reader.onload = (function ( theFile ) {
            return function ( ev ) {
              //获取显示尺寸
              $uploaderPreviewImg.show();
              $uploaderPreviewImg[ 0 ].src = ev.target.result;
              $uploaderPreviewImg[ 0 ].onload = function () {
                var cur_width = $modal.find( '.modal-body' ).width();
                var org_width = $uploaderPreviewImg[ 0 ].width;
                var org_height = $uploaderPreviewImg[ 0 ].height;

                if ( cur_width > org_width ) {
                  $uploaderPreviewImg.width( org_width );
                  cur_width = org_width;
                } else {
                  $uploaderPreviewImg.width( cur_width );
                }

                //检查是否限制了宽和高
                //console.log( settings.param );
                if ( settings.param.width > 0 && settings.param.height > 0 ) {
                  //检查图片尺寸
                  if ( org_width < settings.param.width ) {
                    uploaderError( $this , '宽度小于' + settings.param.width );
                    return;
                  }

                  if ( org_height < settings.param.height ) {
                    uploaderError( $this , '高度小于' + settings.param.height );
                    return;
                  }
                  //启用 jcrop
                  settings.ratio = cur_width / org_width;
                  var w = parseInt( settings.param.width * settings.ratio );
                  var h = parseInt( settings.param.height * settings.ratio );

                  $uploaderPreviewImg.Jcrop( {
                    setSelect : [ 0 , 0 , w , h ] ,
                    minSize : [ w , h ] ,
                    aspectRatio : settings.param.width / settings.param.height ,
                    onChange : setCrop ,
                    onSelect : setCrop ,
                    onRelease : resetCrop
                  } , function () {
                    settings.cropApi = this;
                  } );
                }
              };

              function setCrop( c ) {
                settings.crop = [];
                settings.crop.push( c.x );
                settings.crop.push( c.y );
                settings.crop.push( c.w );
                settings.crop.push( c.h );
              }

              function resetCrop() {
                settings.crop = [];
              }
            };
          })( file );
          reader.readAsDataURL( file );
        }
      } );

      //上传按钮
      $( '#uploaderUploadBtn' ).on( 'click' , function ( e ) {
        var settings = thisUploadObj.data( 'uploader' );
        e.preventDefault();
        var file = $uploaderFile[ 0 ].files[ 0 ];
        if ( ! file ) {
          uploaderError( $this , '请选择上传文件' );
          return;
        }

        if ( file.size > settings.fileSizeLimit * 1024 ) {
          uploaderError( $this , '文件超过' + settings.fileSizeLimit + 'KB' );
          return;
        }

        var fd = new FormData();
        fd.append( settings.fileObjName , file );
        for ( var key in settings.param ) {
          if ( ! settings.param.hasOwnProperty( key ) ) {
            continue;
          }
          fd.append( key , settings.param[ key ] );
        }
        fd.append('_token' ,token);
        //如果是图片
        if ( file.type.match( 'image.*' ) ) {
          //如果是图片,则计算裁剪
          if ( settings.param.width > 0 && settings.param.height > 0 ) {
            if ( settings.crop.length == 0 ) {
              settings.onError( '请选择裁剪图片' );
              return false;
            }

            //计算缩放比
            var crop = [];
            var w = parseInt( settings.crop[ 2 ] / settings.ratio );
            w = w < settings.param.width ? settings.param.width : w;
            var h = parseInt( settings.crop[ 3 ] / settings.ratio );
            h = h < settings.param.height ? settings.param.height : h;

            crop.push( parseInt( settings.crop[ 0 ] / settings.ratio ) );
            crop.push( parseInt( settings.crop[ 1 ] / settings.ratio ) );
            crop.push( w );
            crop.push( h );

            fd.append( 'crop' , crop.join( ',' ) );
          }
        }

        var xhr = new XMLHttpRequest();
        xhr.open( 'POST' , settings.uri , true );

        xhr.upload.addEventListener( "progress" , function ( e ) {
          loading.start();
          settings.onProgress( e );
        } );
        xhr.addEventListener( "load" , function ( e ) {
          loading.end();
          $( '#uploaderModal' ).modal( 'hide' );
          if ( settings.album ) {
            loadAlbum( thisUploadObj );
          }
          settings.onSuccess( $.parseJSON( e.target.responseText ) );
        } );
        xhr.addEventListener( "error" , function ( e ) {
          loading.end();
          settings.onError( e.message );
        } );
        xhr.addEventListener( "abort" , function ( e ) {
          loading.end();
          settings.onError( e.message );
        } );
        xhr.send( fd );
      } );

      if ( settings.album ) {
        initAlbum( $this );
      }
    }

    //显示模态框
    $this.on( 'click' , function ( e ) {
      $uploaderFile.val('')
      settings = $( this ).data( 'uploader' );
      thisUploadObj = $this;
      e.preventDefault();
      if ( settings.cropApi ) {
        //如果有裁剪对象 就先销毁
        settings.cropApi.destroy();
        settings.cropApi = null;
      }

      $uploaderPreview.empty();
      $modal.modal( 'show' );
    } );
  };

  //初始化相册
  var initAlbum = function ( $this ) {
    var settings = $this.data( 'uploader' );

    $this.after( '<button type="button" class="btn btn-info btn-sm open_album_btn"><i class="fa fa-image"></i> 相册</button>' );
    var $openAlbumBtn = $this.next( 'button' );
    var albumModal = '<div class="modal fade" id="album_modal">' +
                     '<div class="modal-dialog">' +
                     '<div class="modal-content">' +
                     '<div class="modal-header">' +
                     '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                     '<span aria-hidden="true">&times;</span></button>' +
                     '<h4 class="modal-title"><i class="icon-picture"></i> 相册</h4></div>' +
                     '<div class="modal-body">' +
                     '<ul class="nav nav-tabs">{tab}</ul>' +
                     '<div class="tab-content">{tabContent}</div></div>' +
                     '<div class="modal-footer">' +
                     '<button type="button" class="btn default" data-dismiss="modal">关闭</button>' +
                     '</div></div></div></div>';
    var albumTab = '<li><a data-toggle="tab" href="#{tabContentId}" data-catalog="{catalog}" data-tag="{tag}"> {tag} </a></li>';
    var albumTabContent = '<div id="{tabContentId}" class="tab-pane fade">' +
                          '<div class="row img_content"></div>' +
                          '<div class="row">' +
                          '<div class="col-md-12 text-right" id="{albumPagId}"></div></div></div>';


    var $albumModal = $( '#album_modal' );
    if ( $albumModal.length == 0 ) {
      $.get( settings.albumCatalogUri , function ( ret ) {
        var tab = '';
        var content = '';

        for ( var i = 0 ; i < ret.length ; i ++ ) {
          var tabContentId = 'album_tab_content_' + ret[ i ][ 'id' ];
          var albumPagId = 'album_pag_' + ret[ i ][ 'id' ];
          tab += albumTab.replace( /\{tabContentId}/g , tabContentId )
                         .replace( /\{catalog}/g , ret[ i ][ 'id' ] )
                         .replace( /\{tag}/g , ret[ i ][ 'tag' ] );
          content += albumTabContent.replace( /\{tabContentId}/g , tabContentId )
                                    .replace( /\{catalog}/g , ret[ i ][ 'id' ] )
                                    .replace( /\{albumPagId}/g , albumPagId )
                                    .replace( /\{tag}/g , ret[ i ][ 'tag' ] );

        }
        var html = albumModal.replace( /\{tab}/g , tab )
                             .replace( /\{tabContent}/g , content );
        $( 'body' ).append( html );
        $albumModal = $( '#album_modal' );

        //打开album选择 modal
        $openAlbumBtn.on( 'click' , function ( e ) {
          e.preventDefault();
          $albumModal.modal( 'show' );
        } );

        //切换tab
        $albumModal.find( 'a[data-toggle="tab"]' ).on( 'shown.bs.tab' , function ( e ) {
          settings.albumParam.cur_tab = e.target;
          $this.data( 'uploader' , settings );
          var $target = $( e.target );
          var page = $target.data( 'page' );

          if ( empty( page ) ) {
            loadAlbum( $this , $target );
          }
        } );

        //设置默认的tab
        //console.log(settings.param.default_tag);
        var len = $albumModal.find( 'a[data-toggle="tab"][data-tag="' + settings.albumParam.defaultTag + '"]' ).length;
        if ( len == 0 ) {
          $albumModal.find( 'a[data-toggle="tab"]' ).eq( 0 ).tab( 'show' );
        } else {
          $albumModal.find( 'a[data-toggle="tab"][data-tag="' + settings.albumParam.defaultTag + '"]' )
                     .tab( 'show' );
        }
      } );
    }

    $( document ).on( 'click' , '.album_item img' , function ( e ) {
      var img_src = $( this ).data( 'src' );
      sure.init( '确认选择吗?' , function () {
        $albumModal.modal( 'hide' );
        settings.onChooseAlbum( img_src );
      } );
    } );
  };

  //加载相册
  function loadAlbum( $this , $target ) {
    var settings = $this.data( 'uploader' );

    if ( ! settings.album ) {
      return false;
    }
    if ( empty( $target ) ) {
      $target = $( settings.albumParam.cur_tab );
    }
    var page = $target.data( 'page' );
    page = empty( page ) ? 1 : page;

    var catalog = $target.data( 'catalog' );
    var albumPagId = 'album_pag_' + $target.data( 'catalog' );
    var $albumPag = $( '#' + albumPagId );

    var data = {
      page : page ,
      page_size : settings.albumParam.page_size ,
      catalog : catalog
    };

    loading.start();
    $.get( settings.albumUri , data , function ( ret ) {
      loading.end();
      $target.data( 'rows' , ret.rows );
      $target.data( 'total' , ret.total );
      $target.data( 'page' , page );
      //tab_content_id
      renderImg( $target , ret.rows );
      if ( $albumPag.html() == '' ) {
        var totalPage = Math.ceil( ret.total / settings.albumParam.pageSize );
        //console.log( total_page );
        $albumPag.bootpag( {
          total : totalPage ,          // total pages
          page : 1 ,            // default page
          maxVisible : 5 ,     // visible pagination
          leaps : true         // next/prev leaps through maxVisible
        } ).on( "page" , function ( event , num ) {
          $target.data( 'page' , num );
          loadAlbum( $this , $target );
        } );
      }
    } );
  }

  //渲染相册
  function renderImg( $target , rows ) {
    var albumImg = '<div class="col-sm-6 col-md-3">' +
                   '<a class="thumbnail album_item" href="javascript:;"><img src="{icon}" data-src="{savePath}"></a>' +
                   '<div class="caption album_item">{imgSize}</div>' +
                   '</div>';

    var imgHtml = '';
    for ( var i = 0 ; i < rows.length ; i ++ ) {
      imgHtml += albumImg.replace( /\{icon}/g , Param.uri.img + rows[ i ][ 'uri' ] )
                         .replace( /\{savePath}/g , rows[ i ][ 'uri' ] )
                         .replace( /\{imgSize}/g , rows[ i ][ 'img_size' ] );
    }

    var tabContentId = $target.attr( 'href' );
    $( tabContentId ).find( '.img_content' ).html( imgHtml );
  }

  var uploaderError = function ( $this , error ) {
    var settings = $this.data( 'uploader' );
    $( '#uploaderForm' )[ 0 ].reset();
    settings.onError( error );
  };

  //插件的方法
  var methods = {
    //初始化
    init : function ( options ) {
      return this.each( function () {
        var $this = $( this );
        var settings = $this.data( 'uploader' );
        if ( typeof(settings) == 'undefined' ) {
          //默认值
          var defaults = {
            uri : '' ,
            btnStyle : 'btn btn-primary btn-sm' ,
            btnText : '<i class="fa fa-upload"></i> 上传' ,
            fileObjName : 'imgFile' ,
            fileSizeLimit : 2048 ,
            ratio : 1 ,
            crop : [] ,
            param : {
              width : 0 ,
              height : 0
            } ,
            saveAlbum : false ,
            album : false ,
            albumUri : '' ,
            albumCatalogUri : '' ,
            albumParam : {
              defaultTag : '' ,
              pageSize : 12
            } ,
            onChooseAlbum : function () {
            } ,
            onError : function ( error ) {
              tips.error( error );
            } ,
            onProgress : function ( e ) {
            } ,
            onSuccess : function () {
            }
          };
          settings = $.extend( {} , defaults , options );
          $this.data( 'uploader' , settings );
        } else {
          settings = $.extend( {} , settings , options );
        }

        //console.log( settings );
        //初始化插件
        _initUpload( $this );
      } );
    } ,

    getParam : function () {
      return $( this ).data( 'uploader' ).param;
    } ,

    setParam : function ( param ) {
      var settings = $( this ).data( 'uploader' );
      settings[ 'param' ] = param;
      $( this ).data( 'uploader' , settings );
    }

  };

  //插件入口
  $.fn.Uploader = function () {
    var method = arguments[ 0 ];
    if ( methods[ method ] ) {
      method = methods[ method ];
      arguments = Array.prototype.slice.call( arguments , 1 );
    } else if ( typeof(method) == 'object' || ! method ) {
      method = methods.init;
    } else {
      return this;
    }

    return method.apply( this , arguments );
  }
})( jQuery );
