/**
 * SysMail JS
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2016-10-10
 */

var SysMail = {
  token : $('input[name=_token]').val(),
  config : {} ,
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


      $form.attr( 'action' , Param.uri.update + '/' +row.id );
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
    $( '#closePortletBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      self.setPortletHide();
    } );

    //发送按钮
    $( document ).on( 'click' , '.sendMailBtn' , function ( e ) {
      e.preventDefault();
      var data = {
        id : $( this ).data( 'id' ) ,
        _token : self.token
      };

      sure.init( '确定发送吗?' , function () {
        $.post( Param.uri.send , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code != 0 ) {
             tips.error( res.msg );
             return false;
           }
           tips.success( res.msg );
         } );
      } );
    } );

  } ,

  delData : function ( ids ) {
    var self = this;
    var data = {
      ids : ids ,
      _token : this.token
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
    var self = this;
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

var typeColor = { captcha : 'default' };
var formatType = function ( value ) {
  return '<span class="label label-' + typeColor[ value ] + '">' + Param.type[ value ] + '</span>';
};

var optSend = function ( value , row ) {
  var html = '';
  if ( row.status == 0 ) {
    html = '<a class="btn btn-sm blue sendMailBtn" data-id="' + row.id + '">' +
           '<i class="fa fa-paper-plane"></i> 发送</a>';
  }

  return html;
};