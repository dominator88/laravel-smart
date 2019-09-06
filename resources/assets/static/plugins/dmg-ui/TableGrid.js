/**
 * FIT Table Grid
 *
 * @author Zix
 * @version 2.0 , 2016-05-03
 */

;(function ( $ ) {

  //保存数据
  //var fit_grid = [];

  //构建页面数据
  var _initGrid = function ( $this ) {
    var settings = $this.data( 'table_grid' );

    //是否有全选
    if ( settings.selectAll ) {
      var checker_html = '<th class="checker_handle" width="20"><input type="checkbox" id="selectAllChecker" value="all"></th>';
      $this.find( 'tr' ).prepend( checker_html );
    }

    //重新渲染表头
    var org_html = "<thead>" + $this.find( 'tr' ).html() + "</thead><tbody></tbody>";
    $this.html( org_html );

    //检查是否可排序
    $this.find( 'th' ).each( function () {
      if ( ! empty( $( this ).data( 'sorting' ) ) ) {
        $( this ).addClass( 'sorting' );
      }
    } );

    //排序事件
    $( document ).on( 'click' , '.sorting , .sort_asc , .sort_desc' , function () {
      settings.param.sort = $( this ).data( 'field' );

      if ( $( this ).hasClass( 'sorting' ) ) {
        settings.param.order = 'asc';
      } else if ( $( this ).hasClass( 'sort_asc' ) ) {
        settings.param.order = 'desc';
      } else if ( $( this ).hasClass( 'sort_desc' ) ) {
        settings.param.order = '';
      }
      $this.data( 'table_grid' , settings );
      loadData( $this );
    } );

    //检查是否有全选
    $( '#selectAllChecker' ).on( 'click' , function () {
      $( '.checker' ).prop( 'checked' , $( this ).prop( 'checked' ) );
    } );

    //是否有分页
    if ( settings.pagination ) {
      pagination( $this );
    }

    //加载数据
    if ( settings.loadAfterInit ) {
      loadData( $this );
    }
  };

  //加载并渲染
  function loadData( $this ) {
    var settings = $this.data( 'table_grid' );

    //取数据
    $.ajax( {
      url : settings.uri ,
      data : settings.param ,
      type : "GET" ,
      dataType : 'JSON' ,
      error : function ( XHR ) {
        tips.error( XHR.status + ' ' + XHR.statusText );
      } ,
      success : function ( ret ) {
        if ( ret.code == 0 ) {
          //渲染到页面
          render( $this , ret );
        } else if ( ret.code == 401 ) {
          tips.error( '请先登录' );
          var col_span = $this.find( 'th' ).length;
          $this.find( 'tbody' ).html( '<tr class="danger"><td align="center" colspan="' + col_span + '">请先登录 Orz</td></tr>' );
        } else {
          tips.error( ret.msg );
        }
      }
    } );
  }

  function render( $this , ret ) {
    var settings = $this.data( 'table_grid' );
    var body_html = [];
    var col_span = $this.find( 'th' ).length;

    //保存值
    $this.data( 'rows' , ret.data.rows );

    //fit_grid[index].data = ret.data.rows;
    settings.total = ret.data.total;
    settings.totalPage = Math.ceil( ret.data.total / settings.param.pageSize );
    $this.data( 'table_grid' , settings );

    if ( settings.pagination == true ) {
      set_pagination_info( $this ); //设置分页
    }

    var has_sort = false;
    //生成显示的数据
    if ( ret.data.rows.length == 0 ) {
      body_html.push( '<tr class="danger"><td align="center" colspan="' + col_span + '">暂无数据 @_@</td></tr>' );
    } else {
      for ( var i in ret.data.rows ) {
        if ( ! ret.data.rows.hasOwnProperty( i ) ) {
          continue;
        }
        var row = ret.data.rows[ i ];
        //行样式
        var rowStyle = settings.rowStyle( row , i );
        rowStyle = empty( rowStyle ) ? '' : rowStyle;

        body_html.push( '<tr class="' + rowStyle + '">' );
        //每格数据及样式
        $this.find( 'th' ).each( function () {
          var self = $( this );
          var val = '';
          if ( self.hasClass( 'checker_handle' ) ) {
            //是否有选中
            val = '<input class="checker" type="checkbox" name="selectChecker[]" value="' + row[ 'id' ] + '" >';
          } else {
            var field = self.data( 'field' );
            var formatter = self.data( 'formatter' );

            //判断是否有排序
            if ( isset( self.data( 'sort' ) ) ) {
              has_sort = true;
            }

            val = isset( row[ field ] ) ? row[ field ] : '';
            //是否有格式化方法
            if ( ! empty( formatter ) ) {
              val = eval( formatter )( val , row , i );
            }
          }

          body_html.push( '<td>' + val + '</td>' );
        } );
        body_html.push( '</tr>' );
      }
    }

    //设置排序
    if ( has_sort ) {
      setSort( $this );
    }

    $this.find( 'tbody' ).html( body_html.join( '' ) );
    //加载完成后的操作
    settings.loadSuccess( ret.data.rows , settings );
  }

  //分页
  function pagination( $this ) {
    var settings = $this.data( 'table_grid' );

    var range = [];

    for ( var i in settings.pageSizeRange ) {
      if ( ! settings.pageSizeRange.hasOwnProperty( i ) ) {
        continue;
      }
      var item = settings.pageSizeRange[ i ];
      var checked = i == 0 ? 'checked' : '';
      range.push( '<option value="' + item + '" ' + checked + '>' + item + '</option>' );
    }

    var html_tmp = '' +
                   '<div class="row">' +
                   '<div class="col-sm-3">第 ' +
                   '<button class="btn btn-sm default pagination_prev" type="button"><i class="fa fa-angle-left"></i></button>' +
                   '<input type="text" style="text-align:center; margin: 0 5px;" ' +
                   'class="form-control input-inline input-sm input-mini pagination_page" value="{page}">' +
                   '<button class="btn btn-sm default pagination_next" type="button"><i class="fa fa-angle-right"></i></button>' +
                   ' 页 , 共 <span class="pagination_total_page">{totalPage}</span> 页</div>' +
                   '<div class="col-sm-4">显示 ' +
                   '<select class="form-control input-sm input-inline pagination_page_size">{pageSize}</select>' +
                   ' 行 , 共 <span class="pagination_total">{total}</span> 行</div></div>';

    //渲染到页面
    $this.parent().after( html_tmp.replace( /\{page}/g , settings.param.page )
                                  .replace( /\{totalPage}/g , settings.totalPage )
                                  .replace( /\{total}/g , settings.total )
                                  .replace( /\{pageSize}/g , range.join( '' ) ) );

    var $pagination = $this.parent().next( '.row' );

    var $pagination_page = $pagination.find( '.pagination_page' );
    var $pagination_page_size = $pagination.find( '.pagination_page_size' );
    var $pagination_prev = $pagination.find( '.pagination_prev' );
    var $pagination_next = $pagination.find( '.pagination_next' );
    //页面
    $pagination_page.on( 'keyup blur' , function ( e ) {
      var page = $.trim( $( this ).val() );
      var event_type = e.type;
      //检查是否填入的数组
      if ( ! $.isNumeric( page ) ||
           page < 1 ||
           page == settings.param.page ||
           page > settings.total_page ) {
        e.preventDefault();
        return;
      }
      //检查回车和失去焦点事件
      if ( ( event_type == 'keyup' && e.keyCode == 13) ||
           event_type == 'blur' ) {
        settings.param.page = page;
        loadData( $this );
      }
      e.preventDefault();
    } );

    //向后按钮
    $pagination_prev.on( 'click' , function ( e ) {
      e.preventDefault();
      settings.param.page --;
      $pagination_page.val( settings.param.page );
      loadData( $this );
    } );

    //向前按钮
    $pagination_next.on( 'click' , function ( e ) {
      e.preventDefault();
      settings.param.page ++;
      $pagination_page.val( settings.param.page );
      loadData( $this );
    } );

    //页数输入
    $pagination_page_size.on( 'change' , function () {
      settings.param.page = 1;
      settings.param.pageSize = $( this ).val();
      loadData( $this );
    } );

    $pagination_page_size.val( settings.param.pageSize );
    $pagination_page.val( settings.param.page );
  }

  //设置分页按钮状态
  function set_pagination_info( $this ) {
    var settings = $this.data( 'table_grid' );
    var $pagination = $this.parent().next( '.row' );

    $pagination.find( '.pagination_page' ).val( settings.param.page );
    $pagination.find( '.pagination_page_size' ).val( settings.param.pageSize );
    $pagination.find( '.pagination_total' ).html( settings.total );
    $pagination.find( '.pagination_total_page' ).html( settings.totalPage );

    var $pagination_prev = $pagination.find( '.pagination_prev' );
    var $pagination_next = $pagination.find( '.pagination_next' );

    $pagination_prev.attr( 'disabled' , false );
    $pagination_next.attr( 'disabled' , false );

    if ( settings.totalPage == 1 ) {
      $pagination_prev.attr( 'disabled' , true );
      $pagination_next.attr( 'disabled' , true );
      return;
    }

    if ( settings.param.page == 1 ) {
      $pagination_prev.attr( 'disabled' , true );
      return;
    }

    if ( settings.param.page == settings.totalPage ) {
      $pagination_next.attr( 'disabled' , true );
    }
  }

  function getSortClass( order ) {
    switch ( order ) {
      case '' :
        return 'sorting';
      case 'asc' :
        return 'sort_asc';
      case 'desc' :
        return 'sort_desc';
      default:
        return 'sorting';
    }
  }

  //设置排序标示
  function setSort( $this ) {
    var settings = $this.data( 'table_grid' );

    var field = empty( settings.param.sort ) ? 'id' : settings.param.sort;
    var order = empty( settings.param.order ) ? '' : settings.param.order.toLocaleLowerCase();

    $this.find( 'th[data-sort]' ).each( function ( index ) {
      var f = $( this ).data( 'field' );
      var orderClass = (f == field) ? getSortClass( order ) : 'sorting';
      $( this ).removeClass( 'sorting sort_asc sort_desc' ).addClass( orderClass );
    } );
  }

  //插件的方法
  var methods = {
    //初始化
    init : function ( options ) {
      return this.each( function () {
        var $this = $( this );
        var settings = $this.data( 'table_grid' );
        if ( typeof(settings) == 'undefined' ) {
          //默认值
          var defaults = {
            uri : '' ,
            selectAll : false ,
            param : {
              page : 1 ,
              pageSize : 10 ,
              keyword : ''
            } ,
            total : 0 ,
            totalPage : 0 ,
            loadAfterInit : true ,
            pagination : true ,
            pageSizeRange : [ 10 , 20 , 50 , 100 ] ,
            rowStyle : function () {
              return '';
            } ,
            loadSuccess : function () {}
          };
          settings = $.extend( {} , defaults , options );
          $this.data( 'table_grid' , settings );
        } else {
          settings = $.extend( {} , settings , options );
        }

        //初始化grid
        _initGrid( $this );
      } );
    } ,

    //重新加载
    reload : function () {
      loadData( $( this ) );
    } ,

    getParam : function () {
      return $( this ).data( 'table_grid' ).param;
    } ,

    setParam : function ( param ) {
      var settings = $( this ).data( 'table_grid' );
      settings[ 'param' ] = param;
      $( this ).data( 'table_grid' , settings );
    } ,

    settings : function ( settings ) {
      var oldSettings = $( this ).data( 'table_grid' );
      var newSettings = $.extend( {} , settings , oldSettings );
      $( this ).data( 'table_grid' , newSettings );
    } ,

    //取单行数据
    getRow : function ( id ) {
      var rows = $( this ).data( 'rows' );
      for ( var i = 0 ; i < rows.length ; i ++ ) {
        var row = rows[ i ];
        if ( row.id == id ) {
          return row;
        }
      }
    } ,

    //取多行数据
    getRows : function () {
      return $( this ).data( 'rows' );
    },
    clear : function(){
      var ret = {
        message : 'success',
        code : 0,
        data : {
          rows : []
        }
      }
      render( this , ret );
    }

  };

  //插件入口
  $.fn.TableGrid = function () {
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
