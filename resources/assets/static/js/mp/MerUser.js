/**
 * MerUser JS
 *
 * @author Zix <zix2002@gmail.com>
 * @version 2.0 , 2016-09-16
 */

var MerUser = {
  config : {
    id : 0
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
    this.initAddressGrid();
    $( 'input[name="area_id"]' ).AreaSelection( {
      uri : Param.uri.area
    } );
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
      //setImgPreview.clear( 'icon' );

      var $userTabs = $( '#userTabs' );
      $userTabs.find( 'a:eq(0)' ).tab( 'show' );
      $userTabs.find( '#userTabs li:gt(0)' ).hide();

      $form.attr( 'action' , Param.uri.insert );
    } );

    //编辑按钮
    $( document ).on( 'click' , '.editBtn' , function ( e ) {
      e.preventDefault();
      self.setPortletShow( 'edit' );

      //处理tab
      var $userTabs = $( '#userTabs' );
      $userTabs.find( 'a:eq(0)' ).tab( 'show' );
      $userTabs.find( '#userTabs li:gt(0)' ).show();

      var id = $( this ).data( 'id' );
      self.config[ 'id' ] = id;

      var row = $( '#dataGrid' ).TableGrid( 'getRow' , id );
      var $form = $( '#addEditForm' );
      $form.reloadForm( row );
      $form.attr( 'action' , Param.uri.update + row.id );

      //加载地址
      Param.queryAddress[ 'userId' ] = id;
      var $addressGrid = $( '#addressGrid' );
      $addressGrid.TableGrid( 'setParam' , Param.queryAddress );
      $addressGrid.TableGrid( 'reload' );

    } );

    //删除一行
    $( document ).on( 'click' , '.destroyBtn' , function ( e ) {
      e.preventDefault();
      var id = $( this ).data( 'id' );
      self.delData( id );
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
          setImgPreview.set( 'icon' , ret.data.savePath );
        }
      } ,
      onChooseAlbum : function ( uri ) {
        setImgPreview.set( 'icon' , uri );
      }
    } );

    $( document ).on( 'click' , '.resetPwdBtn' , function ( e ) {
      e.preventDefault();

      var id = $( this ).data( 'id' );
      sure.init( '重置密码为' + Param.resetPwd , function () {
        $.get( Param.uri.resetPwd + id )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code != 0 ) {
             tips.error( res.msg );
           }
           tips.success( res.msg );
         } );
      } )
    } );

    //新增收货地址
    $( '#addNewAddressBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();

      var $form = $( '#addEditAddressForm' );
      $form.attr( 'action' , Param.uri.insertAddress );
      $form.reloadForm( Param.addressDefaultRow );
      $( 'input[name="area_id"]' ).AreaSelection( 'setAreaName' , '' );

      var $addEditAddressModal = $( '#addEditAddressModal' );
      $addEditAddressModal.find( '.caption-subject' ).html( '新增 用户收获地址' );
      $addEditAddressModal.modal( 'show' );
    } );

    //编辑收货地址
    $( document ).on( 'click' , '.editAddressBtn' , function ( e ) {
      e.preventDefault();

      var id = $( this ).data( 'id' );
      var row = $( '#addressGrid' ).TableGrid( 'getRow' , id );
      var $form = $( '#addEditAddressForm' );
      $form.reloadForm( row );
      $( 'input[name="area_id"]' ).AreaSelection( 'setAreaName' , row.area_text );
      $form.attr( 'action' , Param.uri.updateAddress + row.id );

      var $addEditAddressModal = $( '#addEditAddressModal' );
      $addEditAddressModal.find( '.caption-subject' ).html( '编辑 用户收货地址' );
      $addEditAddressModal.modal( 'show' );
    } );

    //删除地址
    $( document ).on( 'click' , '.destroyAddressBtn' , function ( e ) {
      e.preventDefault();
      var data = {
        id : $( this ).data( 'id' ) ,
        userId : self.config.id
      };

      sure.init( '确认删除收货地址吗?' , function () {
        $.post( Param.uri.destroyAddress , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code != 0 ) {
             tips.error( res.msg );
             return false;
           }
           tips.success( res.msg );
           $( '#addressGrid' ).TableGrid( 'reload' );
         } );
      } );
    } );

    //提交收货地址
    $( '#submitAddressFormBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();

      var $form = $( '#addEditAddressForm' );
      if ( $form.validForm() ) {
        var data = $form.serializeObject();
        data[ 'user_id' ] = self.config.id;

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
             $( '#addressGrid' ).TableGrid( 'reload' );
             $( '#addEditAddressModal' ).modal( 'hide' );
           }
         } );
      }

    } );

  } ,

  delData : function ( ids ) {
    var self = this;
    var data = {
      ids : ids
    };

    sure.init( '是否删除?' , function () {

      $.post( Param.uri.destroy , data )
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
        if ( row.status == 0 ) {
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

  initAddressGrid : function () {

    $( '#addressGrid' ).TableGrid( {
      uri : Param.uri.readAddress ,
      loadAfterInit : false ,
      param : Param.queryAddress ,
      rowStyle : function ( row ) {
        if ( row.status == 0 ) {
          return 'warning';
        }
      }
    } );
  }

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

var optResetPwd = function ( value , row ) {
  return '<button class="btn btn-sm default resetPwdBtn" data-id="' + row.id + '">' +
         '<i class="fa fa-lock"></i> 重置密码</button>';
};

var formatName = function ( value , row ) {
  var gender = ' ';
  if ( row.sex == 1 ) {
    gender += '<i class="fa fa-male"></i>';
  }
  if ( row.sex == 2 ) {
    gender += '<i class="fa fa-female"></i>';
  }
  return row.nickname + gender + '<br>' +
         row.phone + '';
};

var formatRegFrom = function ( value ) {
  return Param.regFrom[ value ];
};

var formatArea = function ( value , row ) {
  return value + '<br>' + row.address;
};

var optEditAddress = function ( _ , row ) {
  return '<a class="btn btn-sm grey-cascade editAddressBtn" data-id="' + row.id + '" href="javascript:;">' +
         '<i class="fa fa-edit"></i> 编辑</a>';
};

var optDestroyAddress = function ( _ , row ) {
  return '<a class="btn btn-sm red destroyAddressBtn" data-id="' + row.id + '" href="javascript:;">' +
         '<i class="fa fa-trash"></i> 删除</a>';
};