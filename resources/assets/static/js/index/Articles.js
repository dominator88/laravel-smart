var Articles = {
  config : {
    maxCommentLength : 250 ,
    curCommentLength : 0 ,
    tipTemplate : '可写 {len} 个字' ,
    commentItemTemp : '<li class="comment-item"><div class="user-info">' +
                      '<a href="/user/{authorId}">{author}</a> @ <small class="text-muted">{timestamp}</small></div>' +
                      '<div class="content">{content}</div>' +
                      '<p class="text-right">' +
                      '{replyBtn}' +
                      '{reply}</li>' ,
    commentReplyBtnTemp : '<button class="btn btn-link replyBtn" data-id="{articleId}">回复</button></p>' ,
    commentItemReplyTemp : '<div class="reply"><blockquote>{replyContent}<footer>{timestamp}</footer></blockquote></div>' ,
    curComment : ''
  } ,
  init : function () {
    this.initImg();
    this.initLikes();
    this.initComments();
    this.initSendComment();
    this.initMenu();
  } ,

  //初始化图片
  initImg : function () {
    $( '.article-content img' ).addClass( 'img-responsive' );
  } ,

  initLikes : function () {
    var $likeBtn = $( '#likeBtn' );
    $likeBtn.on( 'click' , function ( e ) {
      e.preventDefault();

      $.post( Param.uri.likes )
       .fail( function ( res ) {
         tips.error( res.responseText );
       } )
       .done( function ( res ) {
         if ( res.code != 0 ) {
           tips.error( res.msg );
           return false;
         }

         tips.success( res.msg );
         var $likesCount = $( '#likesCount' );
         var count = parseInt( $likesCount.html() ) + 1;
         $likesCount.html( count );
         $likeBtn.animateCss( 'bounceIn' );
       } );
    } );
  } ,

  //初始化评论列表
  initComments : function () {
    var self = this;
    var data = {};

    $.get( Param.uri.comments , data )
     .fail( function ( res ) {
       tips.error( res.responseText );
     } )
     .done( function ( res ) {
       if ( res.code != 0 ) {
         tips.error( res.msg );
         return false;
       }

       var rows = res.data.rows;

       var html = '';
       for ( var i = 0 ; i < rows.length ; i ++ ) {
         var row = rows[ i ];
         var reply = '';
         var replyBtn = '';
         if ( ! empty( row[ 'reply' ] ) ) {
           reply = self.config.commentItemReplyTemp
                       .replace( /\{replyContent}/g , row[ 'reply' ] )
                       .replace( /\{timestamp}/g , row[ 'replied_at' ].substr( 2 , 14 ) );
         } else {
           replyBtn = self.config.commentReplyBtnTemp
                          .replace( /\{articleId}/g , row[ 'id' ] );
         }

         html += self.config.commentItemTemp
                     .replace( /\{author}/g , row[ 'nickname' ] )
                     .replace( /\{authorId}/g , row[ 'user_id' ] )
                     .replace( /\{timestamp}/g , row[ 'created_at' ].substr( 2 , 14 ) )
                     .replace( /\{replyBtn}/g , replyBtn )
                     .replace( /\{content}/g , row[ 'content' ] )
                     .replace( /\{reply}/g , reply );
       }

       $( '.comments-list' ).append( html );

     } );
  } ,

  //初始化发送评论
  initSendComment : function () {
    var self = this;
    $( '#sendCommentForm' )[ 0 ].reset();
    self.config.curComment = '';

    //控制字数
    self.initCommentLength();
    $( '.send-comment-content' ).on( 'keyup' , function ( e ) {

      var curComment = $.trim( $( this ).val() );
      var curCommentLength = curComment.length;

      if ( self.config.maxCommentLength - curCommentLength < 0 ) {
        e.preventDefault();
        $( this ).val( self.config.curComment );
        return false;
      }

      self.config.curCommentLength = curCommentLength;
      self.config.curComment = curComment;
      self.initCommentLength();
    } );

    //发表评论按钮
    $( '#sendCommentBtn' ).on( 'click' , function ( e ) {
      var data = {
        content : self.config.curComment
      };
      $.post( Param.uri.comments , data )
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

  } ,

  initCommentLength : function () {
    var self = this;
    var strLen = self.config.maxCommentLength - self.config.curCommentLength;
    var $sendCommentLength = $( '.send-comment-length' );
    $sendCommentLength.html( this.config.tipTemplate.replace( /\{len}/g , strLen ) );
  },
  initMenu : function(){
    $('.navbar-collapse li:eq(0)').addClass('active');
  }
};