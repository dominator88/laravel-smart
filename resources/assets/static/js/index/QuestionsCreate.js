var QuestionsCreate = {
  config : {
    mde : null,
  } ,
  init : function () {

    this.initSendQuestion();
    this.initCategory();
    this.initPrice();
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

  initCategory : function(){
    $.get(Param.uri.questioncategory , {} , function(res){
      var options = '<option value="0" selected>请选择分类</option>';

      for(var i=0 ; i < res.data.length ; i++){
        options += '<option value="'+res.data[i].id+'">'+res.data[i].text+'</option>';
      }
    //  options = form_options_rows(res);
      $('#category').html(options);
     // console.log(res);
    });
  },

  initPrice : function(){
    var options = '<option value="0" selected>0</option>';
    for(var i=0 ; i < Param.prices.length ; i++){
      options += '<option value="'+Param.prices[i]+'">'+Param.prices[i]+'</option>';
    }
    $('#price').html(options);
  },

  initSendQuestion: function(){
    var self = this;

    $('#sendQuestionBtn').on( 'click' , function(e){
      e.preventDefault();
      console.log($('#content').text());
      var data = {
        title : $('#title').val(),
        content : self.config.mde.value(),
        category : $('#category').val(),
        price : $('#price').val(),
        hide : $('#hide').is(':checked') ? 1 : 0 ,
      };
      $.post(Param.uri.questioncreate , data )
          .done(function( res ){
            tips.success(res.msg);
      }).fail(function( res ){
            tips.error(res.msg);
          });

    });
  },

  initMenu : function(){
    $('.navbar-collapse li:eq(0)').addClass('active');
  }
};