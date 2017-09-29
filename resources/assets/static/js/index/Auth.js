var Auth = {
  config : {
    bg : [
      'static/themes/index/img/auth_1.jpg' ,
      'static/themes/index/img/auth_2.jpg' ,
      'static/themes/index/img/auth_3.jpg' ,
      'static/themes/index/img/auth_4.jpg' ,
      'static/themes/index/img/auth_5.jpg'
    ] ,
    coolDownTime : null
  } ,
  init : function () {

    loading.initAjax();

    var self = this;
    //背景幻灯片
    this.config.bg.sort( function () {return Math.random() > 0.5 ? - 1 : 1;} );
    $( '.auth-left' ).backstretch( this.config.bg , {
        fade : 1000 ,
        duration : 8000
      }
    );

    self.setHeight();
    $( window ).resize( function () {
      self.setHeight();
    } );

    self.initBtn();
  } ,

  setHeight : function () {
    var $authLeft = $( '.auth-left' );
    var $authRight = $( '.auth-right' );

    var winWidth = $( window ).width();
    if ( winWidth < 992 ) {
      $authLeft.height( $( window ).height() / 2 );
      $authRight.height( $( window ).height() / 2 );
    } else {
      $authLeft.height( $( window ).height() );
      $authRight.height( $( window ).height() );
    }
  } ,

  initBtn : function () {
    var self = this;

    $( '#signInBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      var data = $( '#signInForm' ).serializeObject();

      $.post( Param.uri.doSignIn , data )
       .fail( function ( res ) {
         tips.error( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code != 0 ) {
           tips.error( res.msg );
           return false;
         }
         console.log( res.data );
         tips.success( res.msg , function () {
           if ( ! empty( res.data.redirect ) ) {
             window.location.href = res.data.redirect;
           }
         } );
       } );
    } );

    if ( typeof ( Param.coolDownTime ) != 'undefined' ) {
      this.initCaptcha();
      $( '#sendCaptchaBtn' ).on( 'click' , function ( e ) {
        e.preventDefault();

        var data = {
          email : $.trim( $( 'input[name="email"]' ).val() )
        };

        if ( empty( data.email ) ) {
          tips.error( '请填写email' );
          return false;
        }

        $.post( Param.uri.sendCaptcha , data )
         .fail( function ( res ) {
           tips.error( res.responseText );
         } )
         .done( function ( res ) {
           if ( res.code != 0 ) {
             tips.error( res.msg );
             return false;
           }
           tips.success( res.msg );
           self.config.coolDownTime = moment().format( 'X' );
           self.coolDown();
         } );
      } );
    }

    //注册按钮
    $( '#signUpBtn' ).on( 'click' , function ( e ) {
      e.preventDefault();
      $( this ).prop( 'disabled' , true );
      var data = $( '#signUpForm' ).serializeObject();
      if ( empty( data.email ) ) {
        tips.error( '请填写email' );
        $( this ).prop( 'disabled' , false );
        return false;
      }

      if ( empty( data.pwd ) ) {
        tips.error( '请设置密码' );
        $( this ).prop( 'disabled' , false );
        return false;
      }

      if ( empty( data.captcha ) ) {
        tips.error( '请填写验证码' );
        $( this ).prop( 'disabled' , false );
        return false;
      }

      $.post( Param.uri.doSignUp , data )
       .fail( function ( res ) {
         $( this ).prop( 'disabled' , false );
         tips.error( res.responseText );
       } )
       .done( function ( res ) {

         if ( res.code != 0 ) {
           tips.error( res.msg );
           $( this ).prop( 'disabled' , false );
           return false;
         }
         tips.success( res.msg , function () {
           window.location.href = Param.uri.base;
         } );
       } );
    } )
  } ,

  //初始化验证码
  initCaptcha : function () {
    var self = this;

    if ( empty( Param.coolDownTime ) ) {
      $( '#sendCaptchaBtn' ).html( '获取' ).prop( 'disabled' , false );
      return true;
    }

    //禁止发送验证码 并倒数
    self.config.coolDownTime = Param.coolDownTime;
    self.coolDown();
  } ,

  //设置倒数
  coolDown : function () {
    var self = this;
    var currentTime = moment().format( 'X' );

    var leftTime = parseInt( self.config.coolDownTime ) + parseInt( Param.coolDownGap ) - currentTime;
    if ( leftTime <= 0 ) {
      $( '#sendCaptchaBtn' ).html( '获取' ).prop( 'disabled' , false );
      self.config.coolDownTime = 0;
      return true;
    } else {
      $( '#sendCaptchaBtn' ).html( leftTime + '秒' ).prop( 'disabled' , true );
      setTimeout( function () {
        self.coolDown()
      } , 1000 );
    }
  }
};