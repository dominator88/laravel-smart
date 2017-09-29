//渲染类型
var RenderType = {
  APPEND : 'append' , //添加
  REPLACE : 'replace' //替换
};

var Index = {
  //页面配置文件
  config : {
    bg : [
      'static/themes/index/img/home_1.jpg' ,
      'static/themes/index/img/home_2.jpg' ,
      'static/themes/index/img/home_3.jpg' ,
      'static/themes/index/img/home_4.jpg' ,
      'static/themes/index/img/home_5.jpg'
    ] ,
    keyword : '' , //关键字
    catalog : '' , //分类
    tag : '' , //标签
    page : 1 , //页码
    total : 1 , //总页数
    loadingTemp : '<div class="text-center loading">' +
                  '<img src="static/themes/global/img/loading.gif" width="16">' +
                  '<p>正在加载中...</p></div>' ,
    //文章列表模板
    articleTemp : '<div class="list-item">' +
                  '<div class="list-item-row list-sub">' +
                  '<a href="user/1">DMG</a> - {timestamp}' +
                  '</div>' +
                  '<div class="list-item-row">' +
                  '<a href="article/{id}" class="list-title">{title}</a>' +
                  '</div>' +
                  '<div class="list-item-row list-sub">' +
                  '阅读 {pv} - <a class="link" href="article/{id}#comments">评论 {comments}</a> - 喜欢 {likes}' +
                  '</div></div>'

  } ,

  //初始化页面
  init : function () {
    var self = this;

    self.initHeight();
    self.initBtn();
    self.loadList( RenderType.REPLACE );

    //背景幻灯片
    this.config.bg.sort( function () {return Math.random() > 0.5 ? - 1 : 1;} );
    $( '.page-cover' ).backstretch( this.config.bg , {
        fade : 1000 ,
        duration : 8000
      }
    );
  } ,

  //初始化页面高度
  initHeight : function () {
    var self = this;

    self._setHeight();
    $( window ).on( 'resize' , function () {
      self._setHeight();
    } );
  } ,

  //设置页面高度
  _setHeight : function () {
    var $pageCover = $( '.page-cover' );
    var $pageContent = $( '.page-content' );

    $pageContent.height( $( window ).height() );
    $pageCover.height( $( window ).height() );
  } ,

  initBtn : function () {
    var self = this;
    //分类点击
    $( '.catalog' ).on( 'click' , function ( e ) {
      e.preventDefault();
      var catalog = $( this ).data( 'catalog' );
      var text = $( this ).text();
      if ( catalog == self.config.catalog ) {
        return false;
      }

      if ( catalog == '' ) {
        console.log( 'here' );
        $( this ).parent().addClass( 'active' ).next().removeClass( 'active' );
      } else {
        $( this ).parents( 'li.dropdown' ).find( '.dropdown-toggle' ).text( text );
        $( this ).parents( 'li.dropdown' ).addClass( 'active' ).prev().removeClass( 'active' );
      }

      self.config.catalog = catalog;
      self.config.page = 1;
      self.config.total = 1;
      self.setKeyword( '' );
      self.loadList( RenderType.REPLACE );
    } );

    //标签点击
    $( '.tags > a' ).on( 'click' , function ( e ) {
      e.preventDefault();
      var tag = $( this ).data( 'tag' );
      if ( tag == self.config.tag ) {
        return false;
      }

      $( '.tags > a' ).removeClass( 'active' );
      $( this ).addClass( 'active' );
      self.config.tag = tag;
      self.config.page = 1;
      self.config.total = 1;
      self.setKeyword( '' );
      self.loadList( RenderType.REPLACE );
    } );

    //加载更多
    $( '.getMoreBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      if ( self.config.total >= self.config.page ) {
        return false;
      }
      self.config.page ++;
      self.loadList( RenderType.APPEND );
    } )
  } ,

  //设置关键字
  setKeyword : function ( keyword ) {
    this.config.keyword = $.trim( keyword );
    $( 'input[name="keyword"]' ).val( keyword );

  } ,

  //加载列表
  loadList : function ( renderType ) {
    var self = this;
    var data = {
      catalog : self.config.catalog ,
      tag : self.config.tag ,
      page : self.config.page ,
      keyword : self.config.keyword
    };

    if ( renderType == RenderType.APPEND ) {
      $( '.content-list' ).append( self.config.loadingTemp );
    } else if ( renderType == RenderType.REPLACE ) {
      $( '.content-list' ).html( self.config.loadingTemp );
    }

    $.get( Param.uri.articles , data )
     .fail( function ( res ) {
       //tips.error(res.responseText);
       console.log( 'error' , res );
     } )
     .done( function ( res ) {
       console.log( res );
       if ( res.code != 0 ) {
         console.log( res.msg );
         return false;
       }
       self.config.total = res.data.total;
       self.renderList( res.data.rows , renderType );
     } );
  } ,

  //渲染列表
  renderList : function ( data , type ) {
    var temp = this.config.articleTemp;
    var html = [];
    for ( var i = 0 ; i < data.length ; i ++ ) {
      var item = data[ i ];
      var timestamp = getTimestamp( item.created_at );
      html.push( temp.replace( /\{id}/g , item.id )
                     .replace( /\{title}/g , item.title )
                     .replace( /\{pv}/g , item.pv )
                     .replace( /\{comments}/g , item.comments )
                     .replace( /\{likes}/g , item.likes )
                     .replace( /\{timestamp}/g , timestamp ) );
    }


    if ( type == RenderType.REPLACE ) {
      $( '.content-list' ).html( html.join( "\n" ) );
    } else if ( type == RenderType.APPEND ) {
      $( '.loading' ).remove();
      $( '.content-list' ).append( html.join( "\n" ) );
    }
  }
};

var getTimestamp = function ( timeString ) {
  var unit = [ 31104000 , 2592000 , 86400 , 3600 , 60 , 3600 ];
  var unitText = [ '年前' , '个月前' , '天前' , '小时之前' , '分钟之前' , '秒之前' ];
  var diff = moment().unix() - moment( timeString ).unix();

  for ( var i = 0 ; i < unit.length ; i ++ ) {
    if ( diff > unit[ i ] ) {
      diff = Math.round( diff / unit[ i ] );
      break;
    }
  }

  return diff + unitText[ i ];
};