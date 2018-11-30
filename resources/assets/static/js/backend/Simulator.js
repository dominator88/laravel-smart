/**
 * 接口调试 JS
 *
 * @author Zix
 * @version 2.0 ,  2016-09-13
 */

var Simulator = {
  config : {
    arrayCount : 0 ,
    method : ''
  } ,

  init : function ( config ) {
    var self = this;
    $.extend( this.config , config );
    self.initApiUri();
    self.initBtn();
    self.initVersion();
  } ,
  initVersion: function(){
    
      var ret = {"v1":[{"version":"v1","text":"v1"}],"v2":[{"version":"v2","text":"v2"}]}

      var options = [];
      for(var version in ret){

        if(! ret.hasOwnProperty(version)){
          continue;
        }
        var item = ret[ version ];
        console.log(item)
        options.push( '<optgroup label="' + version + '">' );
          if ( ret[ version ].length > 0 ) {
            for ( var i in item ) {
              if ( ! item.hasOwnProperty( i ) ) {
                continue;
              }
              var row = item[ i ];
              options.push( '<option value="' + row.version + '" data-directory="' + row.text + '">' + row.version + '/'  + ' (' + row.text + ')' + '</option>' );
            }
          }
          options.push( '</optgroup>' );
      }

      $( '#version' ).html( options.join( '' ) ).eq( 0 ).prop( 'selected' , true );

    },
  //初始化action
  initApiUri : function (version='') {
    $.get( Param.uri.readApi+"?version="+version , function ( ret ) {
      //console.log( ret ) ;
      var options = [];
      for ( var name in ret ) {
        if ( ! ret.hasOwnProperty( name ) ) {
          continue;
        }
        var item = ret[ name ];
        options.push( '<optgroup label="' + name + '">' );
        if ( ret[ name ].length > 0 ) {
          for ( var i in item ) {
            if ( ! item.hasOwnProperty( i ) ) {
              continue;
            }
            var row = item[ i ];
            options.push( '<option value="' + row.action + '" data-directory="' + row.directory + '">' +
                          row.directory + '/' + row.action + ' (' + row.text + ')' + '</option>' );
          }
        }
        options.push( '</optgroup>' );
      }

      $( '#actions' ).html( options.join( '' ) ).eq( 0 ).prop( 'selected' , true );
    } );
  } ,

  //初始化按钮
  initBtn : function () {
    var self = this;
    var $actions = $( '#actions' );
    var $headerForm = $( '#headerForm' );
    var $signatureStr = $( '#signatureStr' );
    var $apiResponse = $( '#apiResponse' );
    var $version = $('#version');

    //显示隐藏header
    $( '#showOrHideHeader' ).on( 'click' , function ( e ) {
      e.preventDefault();
      console.log( $headerForm.is( ':hidden' ) );
      if ( $headerForm.is( ':hidden' ) ) {
        $headerForm.show();
        $( this ).removeClass( 'green' ).addClass( 'grey' ).text( '隐藏' );
      } else {
        $headerForm.hide();
        $( this ).removeClass( 'grey' ).addClass( 'green' ).text( '显示' );
      }
    } );
    $version.on('change' , function(){
      var $version = $('#version');

      //version
     $ver = $version.find('option:selected').data( 'directory' )
      self.initApiUri($ver)
    });
    //action变化
    $actions.on( 'change' , function () {
      $( '#selectActionBtn' ).trigger( 'click' );
    } );

    //选择action
    $( '#selectActionBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      self.config.method = '';
      self.loadParams();
    } );


    //选择method
    $( document ).on( 'click' , 'input[name="requestMethod"]' , function () {
      var method = $( this ).val();
      if ( self.config.method == method ) {
        return false;
      }
      console.log( method );
      self.config.method = method;
      self.loadParams();
    } );

    //发送请求
    $( document ).on( 'click' , '#submitBtn' , function ( e ) {
      e.preventDefault();
      loading.start();

      var apiUri = Param.uri.api+self.config.version+'/' + self.config.directory + '/' + self.config.action;

      //时间戳
      var timestamp = Date.parse( new Date() ) / 1000;

      //头部固定参数
      var header = $( '#headerForm' ).serializeObject();
      header[ 'timestamp' ] = timestamp;
      $( '#timestamp' ).val( timestamp );

      //要传的参数
      var params = self.fixFormData( $( '#paramsForm' ).serializeJSON() );

      //请求方式
      var requestMethod = $( 'input[name="requestMethod"]:checked' ).val();

      //计算签名
      header[ 'signature' ] = signature( params , timestamp , Param.secret );
      $( '#signatureInp' ).val( header[ 'signature' ] );

      if ( self.config.directory == 'system' && self.config.action == 'upload' ) {
        //上传文件
        FileUpload.send( Param.uri.api + 'system/upload' , header , params , function ( ret ) {
          $apiResponse.show().html( jsonFormat( ret ) );
        } );
      } else {
        //普通请求
        $.ajax( {
          type : requestMethod ,
          dataType : 'json' ,
          url : apiUri ,
          data : params ,
          headers : header
        } )
         .fail( function ( res ) {
           loading.end();
           $apiResponse.show().html( '发生错误了' + res.responseText );
         } )
         .done( function ( res ) {
           loading.end();
           $apiResponse.show().html( jsonFormat( res ) );
         } );
      }
    } );

    //显示返回示例
    $( document ).on( 'click' , '#responseBtn' , function ( e ) {
      e.preventDefault();
      var $responseExample = $( '#responseExample' );

      if ( $responseExample.is( ':hidden' ) ) {
        var result = $responseExample.data( 'json' );
        $responseExample.show().find( 'pre' ).html( JSON.stringify( result , null , 2 ) );

      } else {
        $responseExample.hide();
      }
    } );

    //添加 array item 按钮
    $( document ).on( 'click' , '.addMoreBtn' , function ( e ) {
      e.preventDefault();
      var $row = $( this ).parent().parent();
      self.config.arrayCount ++;
      var rowHtml = $row.find( '.for_copy' ).html().replace( /0/g , self.config.arrayCount );
      var html = '<div class="row">' + rowHtml +
                 '<div class="col-sm-4"><button class="btn btn-default removeRowBtn"><i class="fa fa-minus"></i></button>' +
                 '</div></div>';
      $row.after( html );
    } );

    //删除 array item 按钮
    $( document ).on( 'click' , '.removeRowBtn' , function ( e ) {
      e.preventDefault();
      $( this ).parent().parent().remove();
    } )
  } ,

  //加载参数
  loadParams : function () {
    var self = this;
    var $actions = $( '#actions' );
    var $signatureStr = $( '#signatureStr' );
    var $apiResponse = $( '#apiResponse' );
     var $version = $('#version');

      //version
     $ver = $version.find('option:selected').data( 'directory' )
    

    self.config.arrayCount = 0;
    self.config.directory = $actions.find( 'option:selected' ).data( 'directory' );
    self.config.action = $actions.val();
    self.config.version = $ver

    var data = {
      directory : self.config.directory ,
      action : self.config.action ,
      method : self.config.method,
      version :  self.config.version
    };

    loading.start();
    $signatureStr.html( '' );
    $apiResponse.html( '' );

    $.get( Param.uri.readParams , data , function ( res ) {
      loading.end();
      $( '#params' ).html( res );
      //$('input[name="requestMethod"]:first').prop('checked' , true);
      self.addTestData();
    } );
  } ,

  addTestData : function () {
    var $form = $( '#paramsForm' );
    var $token = $form.find( 'input[name="api_token"]' );
    var $merId = $form.find( 'input[name="merId"]' );
    var html = '';
    console.log($token);
    if ( $token.length > 0 ) {
      html = '<select name="api_token" class="form-control">';

      for ( var i = 0 ; i < Param.testToken.length ; i ++ ) {
        var user = Param.testToken[ i ];
        html += '<option value="' + user.api_token + '">' + user.api_token + ' (' + user.username + ' - ' + user.phone + ' )' + '</option>';
      }
      html += '</select>';

      $token.after( html );
      $token.remove();
    }

    if ( $merId.length > 0 ) {
      html = '<select name="merId" class="form-control">';

      for ( var i = 0 ; i < Param.testMer.length ; i ++ ) {
        var mer = Param.testMer[ i ];
        html += '<option value="' + mer.id + '">' + mer.id + ' (' + mer.name + ')' + '</option>';
      }
      html += '</select>';

      $merId.after( html );
      $merId.remove();
    }
  } ,

  fixFormData : function ( data ) {
    //console.log( data )  ;
    for ( var key in data ) {
      if ( ! data.hasOwnProperty( key ) ) {
        continue;
      }
      if ( $.isArray( data[ key ] ) || $.isPlainObject( data[ key ] ) ) {
        var newArr = [];
        for ( var k in  data[ key ] ) {
          if ( ! data[ key ].hasOwnProperty( k ) ) {
            continue;
          }
          if ( ! empty( data[ key ][ k ] ) ) {
            newArr.push( data[ key ][ k ] )
          }
        }
        data[ key ] = JSON.stringify( newArr );
      }
    }
    return data;
  }


};

//签名
function signature( data , timestamp , secret ) {
  //data['timestamp'] = timestamp ;

  var metadata = [];
  for ( var name in data ) {
    if ( ! data.hasOwnProperty( name ) ) {
      continue;
    }
    var value = data[ name ];
    if ( $.isFunction( value ) ) {
      continue;
    }

    metadata.push( {
      name : name ,
      value : value
    } )
  }
  metadata.push( {
    name : 'timestamp' ,
    value : timestamp
  } );

  metadata.sort( function ( a , b ) {
    return a.name < b.name ? - 1 : 1;
  } );

  var url = [];
  for ( var i in metadata ) {
    if ( typeof (metadata[ i ][ 'value' ]) === 'undefined' ) {
      continue;
    }
    url.push( metadata[ i ][ 'name' ] + '=' + metadata[ i ][ 'value' ] );
  }
  url = url.join( '&' , url ) + '&secret=' + secret;
  $( '#signatureStr' ).html( htmlEncode( url ) );
  return $.md5( url );
}

var htmlEncode = function ( str ) {
  var s = "";
  s = str.replace( /&/g , "&amp;" );
  return s;
};

var FileUpload = {
  config : {
    callback : function () {
    }
  } ,
  send : function ( url , header , params , callback ) {
    var self = this;
    self.config.callback = callback;
    var file = $( 'input[name="fileData"]' )[ 0 ].files[ 0 ];
    var fd = new FormData();
    fd.append( "fileData" , file );

    var xhr = new XMLHttpRequest();

    for ( var key in params ) {
      if ( ! params.hasOwnProperty( key ) ) {
        continue;
      }
      if ( ! params[ key ] ) {
        continue;
      }
      fd.append( key , params[ key ] );
    }

    xhr.upload.addEventListener( "progress" , self.onProgress , false );
    xhr.addEventListener( "load" , self.onComplete , false );
    xhr.addEventListener( "error" , self.onFailed , false );
    xhr.addEventListener( "abort" , self.onCanceled , false );

    xhr.open( "POST" , url );
    for ( var k in header ) {
      if ( ! header.hasOwnProperty( k ) ) {
        continue;
      }
      xhr.setRequestHeader( k , header[ k ] );
    }
    xhr.send( fd );
  } ,

  onComplete : function ( e ) {
    loading.end();
    try {
      var jsonData = $.parseJSON( e.target.responseText );
      FileUpload.config.callback( jsonData );
    } catch ( e ) {
      FileUpload.config.callback( e.message );
    }
  } ,

  onProgress : function ( e ) {
    if ( e.lengthComputable ) {
      //console.log( e.loaded + ' - ' + e.total );
    } else {
      FileUpload.config.callback( e.message );
    }
  } ,

  onFailed : function ( e ) {
    loading.end();
    FileUpload.config.callback( e.message );
  } ,

  onCanceled : function ( e ) {
    loading.end();
    FileUpload.config.callback( e.message );
  }
};

/**
 * json 格式化
 */
function jsonFormat( txt , compress ) {
  if ( ! txt ) {
    return txt;
  }
  var indentChar = '  ';
  if ( /^\s*$/.test( txt ) ) {
    //alert('数据为空,无法格式化! ');
    return txt;
  }
  var data = txt;
  if ( ! $.isPlainObject( data ) ) {
    try {
      data = eval( '(' + txt + ')' );
    } catch ( e ) {
      //alert('数据源语法错误,格式化失败! 错误信息: ' + e.description, 'err');
      return txt;
    }
  }
  var draw      = [] ,
      last      = false ,
      This      = this ,
      line      = compress ? '' : '\n' ,
      nodeCount = 0 ,
      maxDepth  = 0;
  var notify = function ( name , value , isLast , indent /* 缩进 */ , formObj ) {
    nodeCount ++;
    /* 节点计数 */
    for ( var i = 0 , tab = '' ; i < indent ; i ++ ) {
      tab += indentChar;
    }
    /* 缩进HTML */
    tab = compress ? '' : tab;
    /* 压缩模式忽略缩进 */
    maxDepth = ++ indent;
    /* 缩进递增并记录 */
    if ( value && value.constructor == Array ) { /* 处理数组 */
      draw.push( tab + (
          formObj ? (
                 '"' + name + '":'
          ) : ''
        ) + '[' + line );
      /*
       * 缩进'['
       * 然后换行
       */
      for ( var i = 0 ; i < value.length ; i ++ ) {
        notify( i , value[ i ] , i == value.length - 1 , indent , false );
      }
      draw.push( tab + ']' + (
          isLast ? line : (
                 ',' + line
          )
        ) );
      /* 缩进']'换行,若非尾元素则添加逗号 */
    } else if ( value && typeof value == 'object' ) { /* 处理对象 */
      draw.push( tab + (
          formObj ? (
                 '"' + name + '":'
          ) : ''
        ) + '{' + line );
      /*
       * 缩进'{'
       * 然后换行
       */
      var len = 0 ,
          i   = 0;
      for ( var key in value ) {
        len ++;
      }
      for ( var key in value ) {
        notify( key , value[ key ] , ++ i == len , indent , true );
      }
      draw.push( tab + '}' + (
          isLast ? line : (
                 ',' + line
          )
        ) );
      /* 缩进'}'换行,若非尾元素则添加逗号 */
    } else {
      if ( typeof value == 'string' ) {
        value = '"' + value + '"';
      }
      draw.push( tab + (
          formObj ? (
                 '"' + name + '":'
          ) : ''
        ) + value + (
                   isLast ? '' : ','
                 ) + line );
    }
    ;
  };
  var isLast = true ,
      indent = 0;
  notify( '' , data , isLast , indent , false );
  return draw.join( '' );
}