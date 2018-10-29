/**
 * SysModules JS
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2016-09-27
 */

var SysModules = {
  token : $('input[name=_token]').val(),
  init : function () {
    //重新设置菜单
    if ( ! empty( Param.uri.menu ) ) {
      Layout.setSidebarMenuActiveLink( 'set' , 'a[data-uri="' + Param.uri.menu + '"]' );
    }

    //初始化ajax 提示框
    loading.initAjax();

    this.initBtn();
    this.initSearchForm();
    this.initGrid();
  } ,

  //初始化查询form
  initSearchForm : function () {
    var $searchForm = $( '#searchForm' );
    $searchForm.reloadForm( Param.query );

    //点击查询按钮
    $( '#searchBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();

      var $dataGrid = $( '#dataGrid' );
      var param = $dataGrid.TableGrid( 'getParam' );

      param.keyword = $.trim( $( '#keyword' ).val() );
      param.status = $( '#status' ).val();
      param.page = 1;

      //console.log( param );
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
      if ( ! $addEditPortlet.hasClass( 'red' ) ) {
        $addEditPortlet.removeClass( 'green-meadow' ).addClass( 'red' );
      }
      $addEditPortlet.find( '.caption-title' ).html( '新建' + Param.pageTitle );
    } else if ( type == 'edit' ) {
      if ( ! $addEditPortlet.hasClass( 'green-meadow' ) ) {
        $addEditPortlet.removeClass( 'red' ).addClass( 'green-meadow' );
      }
      $addEditPortlet.find( '.caption-title' ).html( '编辑' + Param.pageTitle );
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

      $form.attr( 'action' , Param.uri.insert );
    } );

    //编辑按钮
    $( document ).on( 'click' , '.editBtn' , function ( e ) {
      e.preventDefault();
      self.setPortletShow( 'edit' );

      var id = $( this ).data( 'id' );
      var row = $( '#dataGrid' ).TableGrid( 'getRow' , id );
      var $form = $( '#addEditForm' );

      $form.reloadForm( row );
      setImgPreview.set( 'icon' , row.icon );

      //console.log( row.roles );
      $.each( row.roles , function ( index , item ) {
        $( 'input[name="roles[]"][value="' + item.role_id + '"]' ).prop( 'checked' , true );
      } );

      $form.attr( 'action' , Param.uri.update + '/' + row.id );
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
        data._token = self.token;
        $.post( $form.attr( 'action' ) , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code == 401 ) {
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
    $( '#closePortletBtn' ).on( 'click' , function ( e ) {
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
          setImgPreview.set( 'icon' , ret.data.uri );
        }
      } ,
      onChooseAlbum : function ( uri ) {
        setImgPreview.set( 'icon' , uri );
      }
    } );

    //密码重置
    $( document ).on( 'click' , '.reset-pwd-btn' , function ( e ) {
      e.preventDefault();
      var id = $( this ).data( 'id' );
      sure.init( '将密码重置为 ' + Param.defaultPwd + " ?" , function () {
        $.get( Param.uri.resetPwd +'/'+ id , function ( ret ) {
          if ( ret.code != 0 ) {
            tips.error( ret.msg );
            return;
          }
          tips.success( '重置成功' );
        } );
      } );
    } );
  } ,

  delData : function ( ids ) {
    var data = {
      ids : ids,
      _token : this.token
    };

    sure.init( '是否删除?' , function () {

      $.post( Param.uri.destroy , data )
       .fail( function ( res ) {
         tips( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code == 401 ) {
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
    var self = this;
    var uri = Param.uri.this + '?' + $.param( Param.query );
    history.replaceState( Param.query , '' , uri );

    //console.log( uri );

    $( '#dataGrid' ).TableGrid( {
      uri : Param.uri.read ,
      selectAll : false ,
      param : Param.query ,
      rowStyle : function ( row ) {
        if ( row.status == 0 ) {
          return 'warning';
        }
      } ,
      loadSuccess : function ( rows , settings ) {
        var old_uri = window.location.href;
        var uri = Param.uri.this + '?' + $.param( settings.param );
        if ( old_uri == uri ) {
          return false;
        }

        var params = $.getUrlParams( window.location.href );
        history.pushState( params , '' , old_uri );
        history.replaceState( settings.param , '' , uri );
      }
    } );
  }

};

//pop state 事件
window.onpopstate = function ( event ) {
  if ( event && event.state ) {
    $( '#searchForm' ).reloadForm( event.state );
    var $dataGrid = $( '#data_grid' );
    $dataGrid.TableGrid( 'setParam' , event.state );
    $dataGrid.TableGrid( 'reload' );
  }
};

var formatUsername = function ( value , row ) {
  var html = value + '<br>';
  $.each( row.roles , function ( index , role ) {
    html += '<span class="badge badge-default ">' + role.role_name + '</span>';
  } );
  return html;
};

var optResetPwd = function ( value , row ) {
  return '<a href="#" data-id="' + row.id + '" class="btn default btn-sm reset-pwd-btn">' +
         '<i class="fa fa-key"></i> 重置密码</a>';
};