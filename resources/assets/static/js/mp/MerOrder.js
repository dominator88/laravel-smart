/**
 * MerOrder JS
 *
 * @author Zix <zix2002@gmail.com>
 * @version 2.0 , 2016-09-18
 */

var MerOrder = {
  config : {
    row : null ,
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
    this.initItemsGrid();
    this.initLogsGrid();

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

    //编辑按钮
    $( document ).on( 'click' , '.editBtn' , function ( e ) {
      e.preventDefault();
      self.setPortletShow( 'edit' );

      var id = $( this ).data( 'id' );
      var row = $( '#dataGrid' ).TableGrid( 'getRow' , id );
      self.config.row = row;
      //var $form = $( '#addEditForm' );

      $( '.orderTab a:first' ).tab( 'show' );
      $( '.orderDetail' ).FillValue( 'reload' , row );

      Param.queryItems[ 'orderId' ] = id;
      var $itemsGrid = $( '#itemsGrid' );
      $itemsGrid.TableGrid( 'setParam' , Param.queryItems );
      $itemsGrid.TableGrid( 'reload' );

      Param.queryLogs[ 'orderId' ] = id;
      var $logsGrid = $( '#logsGrid' );
      $logsGrid.TableGrid( 'setParam' , Param.queryLogs );
      $logsGrid.TableGrid( 'reload' );
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


    $( document ).on( 'click' , '.changeStatusBtn' , function ( e ) {
      e.preventDefault();

      var id = $( this ).data( 'id' );
      var data = {
        status : $( this ).data( 'status' )
      };
      sure.init( '确定修改订单状态吗?' , function () {
        $.post( Param.uri.changeStatus + id , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code != 0 ) {
             tips.error( res.msg );
             return false;
           }
           $( '#dataGrid' ).TableGrid( 'reload' );
           self.config.row.status = data.status;
           $( '.orderDetail' ).FillValue( 'reload' , self.config.row );
         } );
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
  } ,

  //初始化itemsGrid
  initItemsGrid : function () {
    $( '#itemsGrid' ).TableGrid( {
      uri : Param.uri.read_items ,
      param : Param.queryItems ,
      loadAfterInit : false ,
      pagination : false
    } );
  } ,

  //初始化logsGrid
  initLogsGrid : function () {
    $( '#logsGrid' ).TableGrid( {
      uri : Param.uri.read_logs ,
      param : Param.queryItems ,
      loadAfterInit : false ,
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

var formatOrderNo = function ( value , row ) {
  return value + '<br><small class="text-muted">' + row.created_at + '</small>';
};

var formatUser = function ( value , row ) {
  return row.nickname + '<br><small class="text-muted">' + row.phone + '</small>'
};

var formatAddress = function ( value , row ) {
  return row.address_name + '<br>' +
         '<small class="text-muted">' + row.address_phone + '</small><br>' +
         '<small class="text-muted">' + row.address_area_text + row.address + '</small> ';
};

var formatChannel = function ( value ) {
  return Param.payChannel[ value ];
};

var formatCurrency = function ( value ) {
  return Param.currency[ value ];
};

var colorStatus = {
  0 : 'default' ,
  10 : FLAT_BG_COLOR.green_jungle ,

  20 : FLAT_BG_COLOR.blue ,
  21 : FLAT_BG_COLOR.blue_madison ,
  22 : FLAT_BG_COLOR.blue_hoki ,
  29 : FLAT_BG_COLOR.blue_steel ,

  30 : FLAT_BG_COLOR.red ,
  31 : FLAT_BG_COLOR.red_thunderbird ,
  32 : FLAT_BG_COLOR.grey_silver ,
  39 : FLAT_BG_COLOR.yellow ,

  99 : "dark"
};

var formatStatus = function ( value ) {
  return '<span class="label label-sm bg-' + colorStatus[ value ] + '">' + Param.status[ value ] + '</span>';
};

var formatRemark = function ( value , row ) {
  var userRemark = empty( row.user_remark ) ? '' : row.user_remark;
  var sysRemark = empty( row.sys_remark ) ? '' : row.sys_remark;
  return '<span class="badge badge-primary"><i class="fa fa-user"></i></span>' + userRemark + '<br>' +
         '<span class="badge badge-danger"><i class="fa fa-comment"></i></span>' + sysRemark;
};

var optDetail = function ( _ , row ) {
  return '<a class="btn btn-sm grey-cascade editBtn" data-id="' + row.id + '" href="javascript:;">' +
         '<i class="fa fa-edit"></i> 详情</a>';
};

var formatChangeStatus = function ( value , row ) {
  var flowTo = Param.flow[ value ].flow;
  if ( empty( flowTo ) ) {
    return '';
  }

  console.log( flowTo );
  var html = '';
  for ( var index in flowTo ) {
    if ( ! flowTo.hasOwnProperty( index ) ) {
      continue;
    }
    var statusTo = flowTo[ index ];
    var statusText = Param.status[ statusTo ];
    var color = colorStatus[ statusTo ];

    html += '<a href="#" ' +
            'data-status="' + statusTo + '" ' +
            'class="btn btn-sm ' + color + ' changeStatusBtn" ' +
            'data-id="' + row.id + '">' +
            statusText + '</a> ';
  }

  return html;
};

