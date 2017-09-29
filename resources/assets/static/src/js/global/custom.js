/**
 * 自定义 JS 全局方法
 */

var xhrError = function ( XHR ) {
  tips.error( XHR.status + ' ' + XHR.statusText );
};

//toastr 的 配置
toastr.options = {
  "closeButton" : true ,
  "debug" : false ,
  "positionClass" : "toast-top-center" ,
  "onclick" : null ,
  "showDuration" : "500" ,
  "hideDuration" : "500" ,
  "timeOut" : "3000" ,
  "extendedTimeOut" : "1000" ,
  "showEasing" : "swing" ,
  "hideEasing" : "linear" ,
  "showMethod" : "fadeIn" ,
  "hideMethod" : "fadeOut"
};

//提示
var tips = {
  //错误提示
  error : function ( msg ) {
    toastr.error( msg , '@_@' );
  } ,
  //成功提示
  success : function ( msg , callback ) {
    toastr.success( msg , '^_^' );
    if ( $.isFunction( callback ) ) {
      setTimeout( callback , 2000 );
    }
  }
};

//设置上传文件预览
var setImgPreview = {
  //设置图片预览
  set : function ( tagName , fileSavePath , type ) {
    var $preview = $( '#' + tagName + 'Preview' );
    if ( empty( fileSavePath ) ) {
      $preview.html( '' );
      return;
    }
    var abPath = Param.uri.img + fileSavePath;
    var tmp = '';
    if ( empty( type ) || type == 'img' ) {
      var clearBtn = "setImgPreview.clear('" + tagName + "')";
      tmp = '<span class="badge badge-danger" style="top: -5px; left: 125px; position: absolute; font-size: 22px ; cursor: pointer" onclick="' + clearBtn + '">X</span> <img src="{src}" /> ';
    } else if ( type == 'file' ) {
      tmp = '<a href="{src}" target="_blank" class="btn red" ><i class="icon-cloud-download"></i> 下载</a>';
    }

    $preview.html( tmp.replace( /\{src}/g , abPath ) );
    $( 'input[name="' + tagName + '"]' ).val( fileSavePath );
  } ,
  //清楚图片预览
  clear : function ( tagName ) {
    $( '#' + tagName + 'Preview' ).empty();
    $( 'input[name="' + tagName + '"]' ).val( '' );
  }
};

//显示或停止 加载动画
var loading = {
  //初始化
  initAjax : function () {
    var $fitLoading = this.insert();
    $( document ).ajaxStart( function () {
      $fitLoading.show();
    } ).ajaxComplete( function () {
      $fitLoading.fadeOut( 'fast' );
    } );
  } ,

  //开始显示
  start : function () {
    var $fitLoading = this.insert();
    $fitLoading.show();
  } ,
  //结束显示
  end : function () {
    $( '.fit-loading' ).hide();
  } ,
  //插入
  insert : function () {
    var $fitLoading = $( '.fit-loading' );
    if ( $fitLoading.length == 0 ) {
      $( 'body' ).append( '<div class="fit-loading"></div>' );
      $fitLoading = $( '.fit-loading' );
    }
    var top = ( $( window ).height() - $fitLoading.height() ) / 2;
    var left = ( $( window ).width() - $fitLoading.width() ) / 2;
    $fitLoading.css( {
      top : top + 'px' ,
      left : left + 'px' ,
      display : 'none'
    } );
    return $fitLoading;
  }
};

//显示
var sure = {
  config : {} ,
  //打开
  init : function ( msg , ok , cancel ) {
    var self = this;
    self.config.ok = ok;
    self.config.cancel = cancel;

    if ( $( '.fit-confirm' ).length < 1 ) {
      var html = [];
      html.push( '<div class="fit-confirm">' );
      html.push( '<div class="fit-confirm-body"></div>' );
      html.push( '<div class="fit-confirm-btn">' );
      html.push( '<button class="half" id="fit-confirm-cancel_btn" type="button" onclick="sure.cancel(); ">取消</button>' );
      html.push( '<button class="half" id="fit-confirm-ok_btn" type="button" onclick="sure.ok(); ">确定</button>' );
      html.push( '</div></div>' );
      $( 'body' ).append( html.join( '' ) );
    }
    if ( $( '.fit-modal' ).length < 1 ) {
      $( 'body' ).append( '<div class="fit-modal"></div>' );
    }
    $( '.fit-confirm-body' ).html( msg );
    self.show();
  } ,
  //ok 按钮
  ok : function () {
    var self = this;
    if ( $.isFunction( self.config.ok ) ) {
      self.config.ok();
    }
    self.hide();
  } ,
  //取消按钮
  cancel : function () {
    var self = this;
    if ( $.isFunction( self.config.cancel ) ) {
      self.config.cancel();
    }
    self.hide();
  } ,
  //显示窗口
  show : function () {
    //var self = this;
    var $fit_confirm = $( '.fit-confirm' );
    var height = $( document ).height();
    $( '.fit-modal' ).height( height - 1 ).show();
    var top = ( $( window ).height() - $fit_confirm.height() ) / 2 - 60;
    var left = ( $( window ).width() - $fit_confirm.width() ) / 2;
    $fit_confirm.css( {
      top : top + 'px' ,
      left : left + 'px' ,
      position : 'fixed'
    } ).show();
  } ,
  //关闭窗口
  hide : function () {
    //var self = this;
    $( '.fit-modal' ).hide();
    $( '.fit-confirm' ).hide();
  }
};

//变量是否为空
var empty = function ( val ) {
  if ( val == null ||
       val == 'undefined' ||
       typeof( val) == 'undefined' ||
       val == 0 ||
       val == '' ||
       val.length == 0 ) {
    return true;
  }
  return false
};

//变量是否存在
var isset = function ( val ) {
  if ( typeof( val ) == 'undefined' || val == null ) {
    return false;
  }
  return true
};

//字符串重复
var str_repeat = function ( str , num ) {
  return new Array( num + 1 ).join( str );
};

//取缩略图
var get_thumb = function ( icon , size ) {
  if ( empty( size ) ) {
    return icon;
  }
  return icon.substr( 0 , icon.lastIndexOf( "." ) + 1 ) + size + icon.substr( icon.lastIndexOf( "." ) );
};

//rows 转 select options(含树形结构)
var form_options_rows = function ( rows , settings ) {
  var html = '';
  $.each( rows , function ( index , row ) {
    var prefix = '';
    if ( isset( row[ 'level' ] ) ) {
      prefix = row[ 'level' ] - 1 > 0 ? str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;' , row[ 'level' ] - 1 ) + '└─ ' : '';
    }
    html += '<option value="' + row.id + '">' + prefix + row[ settings.field ] + '</option>';
    if ( isset( row[ settings.nodeField ] ) ) {
      html += form_options_rows( row[ settings.nodeField ] , settings );
    }
  } );
  return html;
};

var KE_OPTIONS = {
  items : [
    'source' ,
    '|' ,
    'undo' ,
    'redo' ,
    '|' ,
    'preview' ,
    'cut' ,
    'copy' ,
    'paste' ,
    'plainpaste' ,
    'wordpaste' ,
    '|' ,
    'justifyleft' ,
    'justifycenter' ,
    'justifyright' ,
    'justifyfull' ,
    'insertorderedlist' ,
    'insertunorderedlist' ,
    'indent' ,
    'outdent' ,
    'subscript' ,
    'superscript' ,
    'clearhtml' ,
    'quickformat' ,
    '|' ,
    'fullscreen' ,
    '/' ,
    'formatblock' ,
    'fontname' ,
    'fontsize' ,
    '|' ,
    'forecolor' ,
    'hilitecolor' ,
    'bold' ,
    'italic' ,
    'underline' ,
    'strikethrough' ,
    'lineheight' ,
    'removeformat' ,
    '|' ,
    'image' ,
    'flash' ,
    'media' ,
    'table' ,
    'hr' ,
    'pagebreak' ,
    'anchor' ,
    'link' ,
    'unlink'
  ] ,
  uploadJson : '' ,
  allowFileManager : false ,
  urlType : 'domain' ,
  resizeType : 1 ,
  themeType : 'simple' ,
  afterBlur : function () {
    this.sync();
  }
};


//获取 url 参数
$.extend( {
  getUrlParams : function () {
    var vars = [] , hash;
    var hashes = window.location.href.slice( window.location.href.indexOf( '?' ) + 1 ).split( '&' );
    for ( var i = 0 ; i < hashes.length ; i ++ ) {
      hash = hashes[ i ].split( '=' );
      //vars.push(hash[0]);
      vars[ hash[ 0 ] ] = hash[ 1 ];
    }
    return vars;
  } ,
  getUrlParam : function ( name ) {
    return $.getUrlParams()[ name ];
  }
} );

var FLAT_BG_COLOR = {
  blue : 'blue' ,
  blue_hoki : 'blue-hoki' ,
  blue_steel : 'blue-steel' ,
  blue_madison : 'blue-madison' ,
  blue_chambray : 'blue-chambray' ,
  blue_ebonyclay : 'blue-ebonyclay' ,

  green : 'green' ,
  green_meadow : 'green-meadow' ,
  green_seagreen : 'green-seagreen' ,
  green_turquoise : 'green-turquoise' ,
  green_haze : 'green-haze' ,
  green_jungle : 'green-jungle' ,

  red : 'red' ,
  red_pink : 'red-pink' ,
  red_sunglo : 'red-sunglo' ,
  red_intense : 'red-intense' ,
  red_thunderbird : 'red-thunderbird' ,
  red_flamingo : 'red-flamingo' ,

  yellow : 'yellow' ,
  yellow_gold : 'yellow-gold' ,
  yellow_casablanca : 'yellow-casablanca' ,
  yellow_crusta : 'yellow-crusta' ,
  yellow_lemon : 'yellow-lemon' ,
  yellow_saffron : 'yellow-saffron' ,

  purple : 'purple' ,
  purple_plum : 'purple-plum' ,
  purple_medium : 'purple-medium' ,
  purple_studio : 'purple-studio' ,
  purple_wisteria : 'purple-wisteria' ,
  purple_seance : 'purple-seance' ,

  grey : 'grey' ,
  grey_cascade : 'grey-cascade' ,
  grey_silver : 'grey-silver' ,
  grey_steel : 'grey-steel' ,
  grey_cararra : 'grey-cararra' ,
  grey_gallery : 'grey-gallery'
};

var DTP_DATE_OPTION = {
  language : 'zh-CN' ,
  format : 'yyyy-mm-dd' ,
  startView : 2 ,
  minView : 2 ,
  autoclose : 1 ,
  todayBtn : true ,
  todayHighlight : true ,
  pickerPosition : 'bottom-right'
};

var DTP_TIME_OPTION = {
  language : 'zh-CN' ,
  format : 'hh:ii' ,
  startView : 0 ,
  minView : 0 ,
  minuteStep : 15 ,
  autoclose : 1 ,
  // forceParse : false ,
  //pickerPosition: 'bottom-left'
};

var DTP_DATETIME_OPTION = {
  language : 'zh-CN' ,
  format : 'yyyy-mm-dd hh:ii' ,
  autoclose : 1 ,
  todayBtn : true ,
  todayHighlight : true ,
  pickerPosition : 'bottom-right'
};

/**
 * 处理页面图片延时加载
 *
 * 需要延时加载的图片 格式为:
 * <img src="defaultImgUri" data-src="imgUri">
 *
 * 调用:
 * ImageLazyLoaded.init(); 或者
 *
 * ImageLazyLoaded.init({
 *  defaultImgUri : '默认图片的uri'
 * });
 */
var ImageLazyLoaded = {
  config : {
    defaultImgUri : 'defaultImgUri'
  } ,

  init : function ( config ) {
    $.extend( this.config , config );

    var self = this;
    self.imageLoading();
    $( document ).on( 'scroll' , function () {
      self.imageLoading();
    } );
  } ,

  imageLoading : function () {
    var defaultImgUri = this.config.defaultImgUri;
    $( 'img[src="' + defaultImgUri + '"]' ).each( function () {
      var $this = $( this );
      var offset = $this.offset();
      if ( $( window ).scrollTop() + window.height + 50 <= offset.top ) {
        $this.attr( 'src' , $this.data( 'src' ) );
      }
    } );
  }
};

(function ( $ ) {
  //填充表单
  $.fn.reloadForm = function ( data ) {
    this[ 0 ].reset();
    this.clearFormValid();
    this.find( ':input' ).each( function () {
      var name = $( this ).attr( 'name' );
      if ( empty( name ) ) {
        name = $( this ).attr( 'id' );
      }
      if ( isset( name ) && isset( data ) && isset( data[ name ] ) ) {
        var val = data[ name ];
        switch ( this.type ) {
          case 'checkbox':
          case 'radio':
            $( 'input[name="' + name + '"][value="' + val + '"]' ).prop( 'checked' , true );
            break;
          default:
            $( this ).val( val );
            break;
        }
      }
    } );
  };

  //扩展 animateCss 方法
  $.fn.extend( {
    animateCss : function ( animationName ) {
      var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
      this.addClass( 'animated ' + animationName ).one( animationEnd , function () {
        $( this ).removeClass( 'animated ' + animationName );
      } );
    }
  } );

  //验证表单
  $.fn.validForm = function () {
    var ret = true;
    this.find( ':input' ).each( function () {
      var val = $.trim( $( this ).val() );
      if ( $( this ).data( 'valid' ) ) {
        switch ( $( this ).data( 'valid' ) ) {
          case 'required':
            if ( val == '' || val == 0 || val.length == 0 ) {
              ret = false;
            }
            break;
          case 'number':
            if ( ! $.isNumeric( val ) || val < 0 ) {
              ret = false;
            }
            break;
          case 'positive':
            if ( ! $.isNumeric( val ) || val <= 0 ) {
              ret = false;
            }
            break;
          default:
            break;
        }
        if ( ! ret ) {
          $( this ).focus();
          tips.error( $( this ).data( 'tips' ) );
          $( this ).parents( '.form-group' ).addClass( 'has-error' );
          $( this ).before( '<i class="fa fa-warning valid-icon"></i>' );
          return ret;
        } else {
          $( this ).parents( '.form-group' ).removeClass( 'has-error' );
          $( this ).prev( 'i' ).remove();
        }
      }
    } );
    return ret;
  };

  //清除验证样式
  $.fn.clearFormValid = function () {
    this.find( '.has-error' ).removeClass( 'has-error' );
    this.find( '.valid-icon' ).remove();
  };

  //序列化表单返回 对象
  $.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each( a , function () {
      if ( o[ this.name ] !== undefined ) {
        if ( ! o[ this.name ].push ) {
          o[ this.name ] = [
            o[ this.name ]
          ];
        }
        o[ this.name ].push( this.value || '' );
      } else {
        o[ this.name ] = this.value || '';
      }
    } );
    return o;
  };

  //序列号表单返回 JSON
  $.fn.serializeJSON = function () {
    var self          = this ,
        json          = {} ,
        push_counters = {} ,
        patterns      = {
          "validate" : /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/ ,
          "key" : /[a-zA-Z0-9_]+|(?=\[\])/g ,
          "push" : /^$/ ,
          "fixed" : /^\d+$/ ,
          "named" : /^[a-zA-Z0-9_]+$/
        };
    this.build = function ( base , key , value ) {
      base[ key ] = value;
      return base;
    };

    this.push_counter = function ( key ) {
      if ( push_counters[ key ] === undefined ) {
        push_counters[ key ] = 0;
      }
      return push_counters[ key ] ++;
    };

    $.each( $( this ).serializeArray() , function () {
      // skip invalid keys
      if ( ! patterns.validate.test( this.name ) ) {
        return;
      }
      var k ,
          keys        = this.name.match( patterns.key ) ,
          merge       = this.value ,
          reverse_key = this.name;
      while ( (k = keys.pop()) !== undefined ) {
        // adjust reverse_key
        reverse_key = reverse_key.replace( new RegExp( "\\[" + k + "\\]$" ) , '' );
        // push
        if ( k.match( patterns.push ) ) {
          merge = self.build( [] , self.push_counter( reverse_key ) , merge );
        }
        // fixed
        else if ( k.match( patterns.fixed ) ) {
          merge = self.build( [] , k , merge );
        }
        // named
        else if ( k.match( patterns.named ) ) {
          merge = self.build( {} , k , merge );
        }
      }
      json = $.extend( true , json , merge );
    } );

    return json;
  };
})( jQuery );