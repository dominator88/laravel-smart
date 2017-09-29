/**
 * MerGoods JS
 *
 * @author Zix <zix2002@gmail.com>
 * @version 2.0 , 2016-10-11
 */

var MerGoods = {
  config : {
    id : 0 ,
    content : null
  } ,
  init : function () {
    //重新设置菜单
    if ( ! empty( Param.uri.menu ) ) {
      Layout.setSidebarMenuActiveLink( 'set' , 'a[data-uri="' + Param.uri.menu + '"]' );
    }

    //初始化ajax 提示框
    loading.initAjax();

    //初始化页面按钮
    this.initBtn();

    //初始化查询form
    this.initSearchForm();

    //初始化数据表
    this.initGrid();
    this.initIconGrid();
    this.initOrderGrid();
    this.initCommentsGrid();

    $( '.dtp' ).datetimepicker( DTP_DATETIME_OPTION );
    KE_OPTIONS.uploadJson = Param.uri.uploadKE;
    this.config.content = KindEditor.create( 'textarea[name="content"]' , KE_OPTIONS );
  } ,

  //初始化查询form
  initSearchForm : function () {
    var $searchForm = $( '#searchForm' );
    $searchForm.reloadForm( Param.query );

    //查询按钮
    $( '#searchBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();

      var $dataGrid = $( '#dataGrid' );
      var param = $dataGrid.TableGrid( 'getParam' );

      param = $.extend( {} , param , $( '#searchForm' ).serializeObject() );
      param.page = 1;

      $dataGrid.TableGrid( 'setParam' , param );
      $dataGrid.TableGrid( 'reload' );
    } );
  } ,

  //显示 portlet
  setPortletShow : function ( type ) {
    var $tablePortlet = $( '#tablePortlet' );
    var $addEditPortlet = $( '#addEditPortlet' );

    $tablePortlet.slideUp( 'fast' );
    if ( type == 'add' ) {
      if ( ! $addEditPortlet.hasClass( 'blue' ) ) {
        $addEditPortlet.removeClass( 'green-meadow' ).addClass( 'blue' );
      }

      $addEditPortlet.find( '.caption-subject' ).html( '新增 ' + Param.pageTitle );
    } else if ( type == 'edit' ) {
      if ( ! $addEditPortlet.hasClass( 'green-meadow' ) ) {
        $addEditPortlet.removeClass( 'blue' ).addClass( 'green-meadow' );
      }
      $addEditPortlet.find( '.caption-subject' ).html( '编辑 ' + Param.pageTitle );
    }

    //$('#data-table-portlet').slideUp('fast');
    $addEditPortlet.show();
  } ,

  //关闭 portlet
  setPortletHide : function () {
    $( '#tablePortlet' ).slideDown( 'fast' );
    $( '#addEditPortlet' ).slideUp( 'fast' );
  } ,


  //初始化各种按钮
  initBtn : function () {
    var self = this;

    //打开添加框
    $( '#addNewBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      self.setPortletShow( 'add' );

      var $form = $( '#addEditForm' );

      $form.reloadForm( Param.defaultRow );
      setImgPreview.clear( 'icon' );

      var $goodsTabs = $( '#goodsTabs' );
      $goodsTabs.find( 'a:eq(0)' ).tab( 'show' );
      $goodsTabs.find( '#goodsTabs li:gt(0)' ).hide();

      self.config[ 'content' ].html( '' );

      $form.attr( 'action' , Param.uri.insert );
    } );

    //编辑按钮
    $( document ).on( 'click' , '.editBtn' , function ( e ) {
      e.preventDefault();
      self.setPortletShow( 'edit' );

      //处理tab
      var $goodsTabs = $( '#goodsTabs' );
      $goodsTabs.find( 'a:eq(0)' ).tab( 'show' );
      $goodsTabs.find( '#goodsTabs li' ).show();

      var id = $( this ).data( 'id' );
      self.config[ 'id' ] = id;

      var row = $( '#dataGrid' ).TableGrid( 'getRow' , id );
      var $basicForm = $( '#addEditForm' );
      $basicForm.reloadForm( row );
      $basicForm.attr( 'action' , Param.uri.update + row.id );

      //处理seo form
      $( '#seoForm' ).reloadForm( row );

      //处理profile form
      $( '#profileForm' ).reloadForm( row );
      self.config[ 'content' ].html( row.content );

      //加载图片
      Param.queryIcon[ 'goodsId' ] = id;
      var $iconGrid = $( '#iconGrid' );
      $iconGrid.TableGrid( 'setParam' , Param.queryIcon );
      $iconGrid.TableGrid( 'reload' );

      //加载订单
      Param.queryOrder[ 'goodsId' ] = id;
      var $orderGrid = $( '#orderGrid' );
      $orderGrid.TableGrid( 'setParam' , Param.queryOrder );
      $orderGrid.TableGrid( 'reload' );

      //加载评论
      Param.queryComments[ 'typeId' ] = id;
      var $commentsGrid = $( '#commentsGrid' );
      $commentsGrid.TableGrid( 'setParam' , Param.queryComments );
      $commentsGrid.TableGrid( 'reload' );

    } );

    //提交seo信息
    $( '#submitSeoBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      var data = $( '#seoForm' ).serializeObject();
      $.post( Param.uri.update + self.config.id , data )
       .fail( function ( res ) {
         tips.error( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code == 1001 ) {
           //需要登录
           tips.error( '请先登录' );
         } else if ( res.code != 0 ) {
           tips.error( res.msg );
         } else {
           tips.success( res.msg );
           $( '#dataGrid' ).TableGrid( 'reload' );
         }
       } );

    } );

    //提交 profile 信息
    $( '#submitProfileBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      var data = $( '#profileForm' ).serializeObject();

      $.post( Param.uri.updateProfile + self.config.id , data )
       .fail( function ( res ) {
         tips.error( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code == 1001 ) {
           //需要登录
           tips.error( '请先登录' );
         } else if ( res.code != 0 ) {
           tips.error( res.msg );
         } else {
           tips.success( res.msg );
           $( '#dataGrid' ).TableGrid( 'reload' );
         }
       } );
    } );


    $( '#destroySelectBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      var ids = $( '.checker:checked' ).serializeJSON().selectChecker;
      if ( empty( ids ) ) {
        tips.error( '请选择要删除的记录' );
        return;
      }
      self.delData( ids );
    } );

    //提交添加编辑窗
    $( '#submitFormBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      var $form = $( '#addEditForm' );

      if ( $form.validForm() ) {
        var data = $form.serializeObject();

        $.post( $form.attr( 'action' ) , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code == 1001 ) {
             //需要登录
             tips.error( '请先登录' );
           } else if ( res.code != 0 ) {
             tips.error( res.msg );
           } else {
             tips.success( res.msg );
             $( '#dataGrid' ).TableGrid( 'reload' );
             self.setPortletHide();
           }
         } );
      }
    } );

    //关闭添加编辑窗
    $( '.closePortletBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      self.setPortletHide();
    } );

    //上传按钮
    $( '#iconUploadBtn' ).Uploader( {
      uri : Param.uri.upload , //上传文件
      param : Param.uploadParam ,
      album : true ,
      albumUri : Param.uri.album ,
      albumCatalogUri : Param.uri.albumCatalog ,
      albumParam : Param.albumParam ,
      onSuccess : function ( ret ) {
        tips.success( ret.msg );
        if ( ret.code == 0 ) {
          self.insertIcon( ret.data.savePath );
          //setImgPreview.set( 'icon' , ret.data.savePath );
        }
      } ,
      onChooseAlbum : function ( uri ) {
        self.insertIcon( uri );
        //setImgPreview.set( 'icon' , uri );
      }
    } );

    //设置 商品状态
    $( document ).on( 'click' , '.setStatusBtn' , function ( e ) {
      e.preventDefault();

      var id = $( this ).data( 'id' );
      var data = {
        status : $( this ).data( 'status' )
      };

      sure.init( '确定更新状态吗?' , function () {
        $.post( Param.uri.update + id , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code != 0 ) {
             tips.error( res.msg );
             return false;
           }
           tips.success( res.msg );
           $( '#dataGrid' ).TableGrid( 'reload' );
         } );
      } );
    } );

    $( document ).on( 'click' , '.setSelectStatusBtn' , function ( e ) {
      e.preventDefault();
      console.log( $( '.checker:checked' ).serializeJSON() );
      var ids = $( '.checker:checked' ).serializeJSON().selectChecker;
      if ( empty( ids ) ) {
        tips.error( '请选择要操作的商品' );
        return false;
      }

      var data = {
        ids : ids ,
        status : $( this ).data( 'status' )
      };

      $.post( Param.uri.updateByIds , data )
       .fail( function ( res ) {
         tips.error( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code != 0 ) {
           tips.error( res.msg );
           return false;
         }
         tips.success( res.msg );
         $( '#dataGrid' ).TableGrid( 'reload' );
       } );

    } );

    //设置为封面
    $( document ).on( 'click' , '.setCoverBtn' , function ( e ) {
      e.preventDefault();

      var data = {
        id : $( this ).data( 'id' )
      };
      $.get( Param.uri.setIconCover , data )
       .fail( function ( res ) {
         tips.error( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code != 0 ) {
           tips.error( res.msg );
           return false;
         }
         tips.success( res.msg );
         $( '#iconGrid' ).TableGrid( 'reload' );
       } );

    } );

    //删除图片
    $( document ).on( 'click' , '.destroyIconBtn' , function ( e ) {
      e.preventDefault();
      var data = {
        ids : $( this ).data( 'id' )
      };

      sure.init( '确认删除图片吗?' , function () {
        $.post( Param.uri.destroyIcon , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code != 0 ) {
             tips.error( res.msg );
             return false;
           }
           tips.success( res.msg );
           $( '#iconGrid' ).TableGrid( 'reload' );
         } );
      } );

    } );

    $( document ).on( 'click' , '.setCommentStatusBtn' , function ( e ) {
      e.preventDefault();

      var data = {
        status : $( this ).data( 'status' )
      };

      $.post( Param.uri.updateComments + $( this ).data( 'id' ) , data )
       .fail( function ( res ) {
         tips.error( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code != 0 ) {
           tips.error( res.msg );
           return false;
         }
         tips.success( res.msg );
         $( '#commentsGrid' ).TableGrid( 'reload' );
       } );

    } );

  } ,

  //添加图片
  insertIcon : function ( uri ) {
    var data = {
      goods_id : this.config.id ,
      uri : uri
    };
    $.post( Param.uri.insertIcon , data )
     .fail( function ( res ) {
       tips.error( res.responseText );
     } )
     .done( function ( res ) {
       if ( res.code != 0 ) {
         tips.error( res.msg );
         return false;
       }
       tips.success( res.msg );
       $( '#iconGrid' ).TableGrid( 'reload' );
     } );
  } ,

  //初始化grid
  initGrid : function () {
    var uri = Param.uri.this + '?' + $.param( Param.query );
    history.replaceState( Param.query , '' , uri );

    $( '#dataGrid' ).TableGrid( {
      uri : Param.uri.read ,
      selectAll : true ,
      param : Param.query ,
      rowStyle : function ( row ) {
        if ( row.status != 1 ) {
          return 'warning';
        }
      } ,
      loadSuccess : function ( rows , settings ) {
        var oldUri = window.location.href;
        var uri = Param.uri.this + '?' + $.param( settings.param );
        if ( oldUri == uri ) {
          return false;
        }

        var params = $.getUrlParams( window.location.href );
        history.pushState( params , '' , oldUri );
        history.replaceState( settings.param , '' , uri );
      }
    } );
  } ,

  initIconGrid : function () {
    var self = this;

    $( '#iconGrid' ).TableGrid( {
      uri : Param.uri.readIcon ,
      param : Param.queryIcon ,
      loadAfterInit : false ,
      pagination : false
    } );
  } ,

  initOrderGrid : function () {
    $( '#orderGrid' ).TableGrid( {
      uri : Param.uri.readOrder ,
      param : Param.queryOrder ,
      loadAfterInit : false
    } );
  } ,

  initCommentsGrid : function () {
    $( '#commentsGrid' ).TableGrid( {
      uri : Param.uri.readComments ,
      param : Param.queryComments ,
      loadAfterInit : false
    } );
  } ,
};

//pop state 事件
window.onpopstate = function ( event ) {
  if ( event && event.state ) {
    $( '#searchForm' ).reloadForm( event.state );
    var $dataGrid = $( '#dataGrid' );
    $dataGrid.TableGrid( 'setParam' , event.state );
    $dataGrid.TableGrid( 'reload' );
  }
};
var statusColor = { "-1" : 'bg-red' , "0" : 'label-primary' , "1" : 'label-default' };
var formatStatus = function ( value ) {
  return '<span class="label label-sm ' + statusColor[ value ] + '">' + Param.status[ value ] + '</span>';
};

var formatGoodsIcon = function ( value ) {
  if ( empty( value ) ) {
    return '';
  }

  var uri = Param.uri.img + get_thumb( value , 160 );
  return '<a href="' + uri + '" target="_blank"><img src="' + uri + '" width="60"></a>';
};

var formatName = function ( value , row ) {
  var html = '[' + row.catalog_text + ']';

  html += '<a href="#' + row.id + '">' + row.name + '</a> ';

  if ( ! empty( row.pid ) ) {
    html += '<span class="badge badge-default"><a href="#">#' + row.pid + '</a></span>';
  }

  if ( row.recommend == 1 ) {
    html += '<span class="badge badge-primary">荐</span> ';
  }

  if ( row.hot == 1 ) {
    html += '<span class="badge badge-danger">热</span> ';
  }

  if ( row.cheap == 1 ) {
    html += '<span class="badge badge-warning">惠</span> ';
  }

  return html;
};

var optSetStatus = function ( _ , row ) {
  if ( row.status != 1 ) {
    return '<a href="" data-id="' + row.id + '" data-status="1" class="btn btn-sm default setStatusBtn">' +
           '<i class="fa fa-arrow-up"></i> 上架</a>';
  }

  return '<a href="" data-id="' + row.id + '" data-status="-1" class="btn btn-sm red setStatusBtn">' +
         '<i class="fa fa-arrow-down"></i> 下架</a>';
};

var formatCover = function ( value ) {
  if ( value == 1 ) {
    return '<i class="fa fa-check"></i>';
  }
  return '';
};

var optIconDefault = function ( _ , row ) {
  if ( row.is_cover == 0 ) {
    return '<a href="#" class="btn btn-sm default setCoverBtn" data-id="' + row.id + '">' +
           '<i class="fa fa-check"></i> 设为封面</a> ';
  }
  return '';
};

var optDestroy = function ( _ , row ) {
  return '<a href="#" class="btn btn-sm red destroyIconBtn" data-id="' + row.id + '">' +
         '<i class="fa fa-trash"></i> 删除</a> ';
};


var formatCommentStatus = function ( value ) {
  return '<span class="label label-sm ' + statusColor[ value ] + '">' +
         Param.commentStatus[ value ] + '</span>';
};

var optCommentStatus = function ( _ , row ) {
  if ( row.status == 0 ) {
    return '<a href="#" data-id="' + row.id + '" data-status="1" class="btn btn-sm red setCommentStatusBtn">' +
           '<i class="fa fa-check"></i> 审核通过</a>';
  } else {
    return '<a href="#" data-id="' + row.id + '" data-status="0" class="btn btn-sm default setCommentStatusBtn">' +
           '<i class="fa fa-check"></i> 取消审核</a>';
  }

};