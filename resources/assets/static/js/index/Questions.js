var Questions = {
  config : {
    mde : null,
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
    commentReplyTemp : '<div class="reply" style="display: none"><blockquote><textarea class="form-control"></textarea><footer><button  type="button" class="btn btn-primary send-comment-reply" >提交</button></footer></blockquote></div>' ,
    curComment : '',


    answerItemTemp : '<li class="answer-item"><div class="user-info">' +
    '<a href="/user/{authorId}">{author}</a> @ <small class="text-muted">{timestamp}</small></div>' +
    '<div class="content">{content}</div>' +
    '<p class="text-right">' +
    '{adoptBtn}' +
    '{replyBtn}' +
    '{reply}</li>' ,
    answerAdoptBtnTemp : '<button class="btn btn-link adoptBtn" data-id="{articleId}">采纳为最佳答案</button>',
    answerReplyBtnTemp :'<button class="btn btn-link replyBtn" data-id="{articleId}">回复</button></p>' ,
    answerItemReplyTemp : '<div class="reply"><blockquote>{replyContent}<footer>{timestamp}</footer></blockquote></div>' ,
  } ,
  init : function () {
    this.initQuestion();
    this.initImg();
    this.initLikes();
  //  this.initAnswers();
    this.initBtn();
    this.initSendAnswer();
    this.initReplyComment();
    this.initSendCommentReply();
    this.initPriceModal();
  //  this.initSendComment();
    this.initMenu();

    this.config.mde = new SimpleMDE( {
      element : $( ".editor" )[ 0 ] ,
      toolbar : [
        'link' ,

        'table' ,
        '|' ,
        'preview' ,
        'side-by-side' ,
        'fullscreen'
      ] ,
      spellChecker : false
    } );
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
  initAnswers : function(){
    var self = this;
  //  var data = {question_status:self.config.question.status};

    $.get( Param.uri.answers ).fail( function(res){
      tips.error( res.msg );
    }).done(function( res ){
      if( res.code != 0 ){
        tips.error( res.msg );
        return false;
      }

      var rows = res.data.rows;
      var html = '';

      for( var i= 0; i<rows.length ; i++){
        var row = rows[ i ];
        var reply = '';
        var replyBtn = '';
        if ( ! empty( row[ 'reply' ] ) ) {
          reply = self.config.answerItemReplyTemp
              .replace( /\{replyContent}/g , row[ 'reply' ] )
              .replace( /\{timestamp}/g , row[ 'replied_at' ].substr( 2 , 14 ) );
        } else {
          if(self.config.question.status != 2){
            adoptBtn = self.config.answerAdoptBtnTemp
              .replace( /\{articleId}/g , row[ 'id' ] );
          }else{
            adoptBtn = '';
          }

          replyBtn = self.config.answerReplyBtnTemp
              .replace( /\{articleId}/g , row[ 'id' ] );
        }

        html += self.config.answerItemTemp
            .replace( /\{author}/g , row[ 'nickname' ] )
            .replace( /\{authorId}/g , row[ 'user_id' ] )
            .replace( /\{timestamp}/g , row[ 'created_at' ].substr( 2 , 14 ) )
            .replace( /\{replyBtn}/g , replyBtn )
            .replace( /\{adoptBtn}/g , adoptBtn )
            .replace( /\{content}/g , row[ 'content' ] )
            .replace( /\{reply}/g , reply );
      }
      $( '.answers-list' ).append( html );
    });
  },


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
  initSendCommentReply : function(){
    var self = this;
    $('body').on( 'click' ,'.send-comment-reply', function(e){

      var data = {
        content : $(this).closest('div').find('textarea').val(),
        rec_id : $(this).data('id'),
      };

      $.post( Param.uri.answercomments , data).fail(function(res){
        tips.error(res.msg);
      }).done( function( res ){
        if( res.code != 0){
          tips.error(res.msg);
          return false;
        }
        tips.success( res.msg );
      });
    });
  },

  initReplyComment : function(){
    var self = this;

    $('body').on('click' , '.replyBtn' , function(e){
      var id = $(this).data('id');
      self.initReplyCommentList(this , id);
      e.preventDefault();
        if($(this).hasClass('show-comment')){
          $(this).removeClass('show-comment');
          $(this).html('回复');

          $(this).next('.reply').slideToggle('fast' , function(){
            $(this).remove();
          });

        }else{

          $(this).html('关闭');
         $(this).addClass('show-comment' );

         $(this).parent().append(self.config.commentReplyTemp);
          $(this).closest('div').find('.send-comment-reply').data('id' , id );

          $(this).next('.reply').slideToggle("fast");

        }
    });

  },

  initReplyCommentList : function(node , id){
    var self = this;
    var data = {rec_id : id ,  module : 'answers'};

    $.get( Param.uri.answercomments ,data ).fail( function(res){
      tips.error( res.msg );
    }).done(function( res ){
      if( res.code != 0 ){
        tips.error( res.msg );
        return false;
      }

      var rows = res.data.rows;
      var html = '';

      for( var i= 0; i<rows.length ; i++){
        var row = rows[ i ];
        var reply = '';
        var replyBtn = '';
        /*if ( ! empty( row[ 'reply' ] ) ) {
          reply = self.config.commentItemReplyTemp
              .replace( /\{replyContent}/g , row[ 'reply' ] )
              .replace( /\{timestamp}/g , row[ 'replied_at' ].substr( 2 , 14 ) );
        } else {
          replyBtn = self.config.answerReplyBtnTemp
              .replace( /\{articleId}/g , row[ 'id' ] );
        }*/

        html += self.config.commentItemTemp
            .replace( /\{author}/g , row[ 'nickname' ] )
            .replace( /\{authorId}/g , row[ 'user_id' ] )
            .replace( /\{timestamp}/g , row[ 'created_at' ].substr( 2 , 14 ) )
            .replace( /\{replyBtn}/g , replyBtn )
            .replace( /\{content}/g , row[ 'content' ] )
            .replace( /\{reply}/g , reply );
      }

      $( node ).closest('li').find('.reply').append( html );
    });

  },

  initSendAnswer : function(){
    var self = this;

    $('#sendAnswerBtn').on( 'click' , function( e ){
      e.preventDefault();
      var data = {
        content : self.config.mde.value(),
      }
      $.post(Param.uri.answers ,data)
          .fail(function (res) {
            tips.error(res.responseText);
          }).done( function (res){
        if( res.code != 0){
          tips.error( res.msg );
          return false;
        }
        tips.success( res.msg);
      });
    });
  },

  initCommentLength : function () {
    var self = this;
    var strLen = self.config.maxCommentLength - self.config.curCommentLength;
    var $sendCommentLength = $( '.send-comment-length' );
    $sendCommentLength.html( this.config.tipTemplate.replace( /\{len}/g , strLen ) );
  },
  initMenu : function(){
    $('.navbar-collapse li:eq(0)').addClass('active');
  },

  initQuestion : function () {
    var self  = this;
    var data = {from:'api'};
    $.get(Param.uri.this , data)
        .done(function(res){
          if(res.code == 0 ){
            self.config.question = res.data;
            if(self.config.question.status == 2){
              $('#sendAnswer').hide();

            }
            self.initAnswers();
          }else{
            tips.error(res.msg);
          }
        })
        .fail(function(res){
          tips.error(res.msg);
        });
  },
  initBtn : function(){
    var self = this;
    $('body').on('click' ,'.adoptBtn' , function(e){
        e.preventDefault();
      var id = $(this).data('id');
        var data = {
          rec_id : id
        };
        $.post(Param.uri.adopt , data )
            .done(function(res){
              if(res.code == 0){
                tips.success(res.msg);
              }else{
                tips.error(res.msg);
              }

        }).fail(function(res){
              tips.error(res.msg);
            });
    });

    $('.editBtn').on('click' , function(e){
      e.preventDefault();
      id = $(this).data('id');
      var url = '/question/create/' + id;
      window.location.href = url;
    });

    $('.addPriceBtn').on( 'click' , function(e){
      e.preventDefault();
      var id = $(this).data('id');

    });

    $('#sendPriceBtn').on( 'click' , function(e){
      e.preventDefault();
      var id = $(this).data('id');
      var price = $('#addprice').find('option:selected').text();

      data = {price : price , id : id};
      $.post( Param.uri.addPrice , data).done(function (res) {
        if( res.code == 0){
          tips.success(res.msg);
          $('#addPriceModal').modal('hide')
        }else{
          tips.error(res.msg);
        }

      }).fail(function (res){
        tips.error(res.msg);
      });
    });


  },

  initPriceModal : function(){
    var data = {};
    $.get(Param.uri.pricearr , data ).done(function(res){
      var html = '<select>';
  //    var data = JSON.parse(res.data);
      for(var i=0;i < res.data.length ; i++){
        html += '<option>'+res.data[i]+'</option>';
      }
      html += '</select>';
    //  tips.success(res.msg);

      $('#addPriceModal .modal-body #addprice').html(html);
    }).fail(function(res){
      tips.error(res.msg);
    });

  }
};