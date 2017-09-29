/**
 * 自动生成代码 JS
 *
 * @author Zix
 * @version 2.0 ,  2016-09-11
 */

var Generate = {
	token : $('input[name=_token]').val(),
	config : {
		fileExists : null
	} ,
	init : function () {
		//初始化ajax 提示框
		loading.initAjax();

		//初始化查询form
		this.initBtn();

		$('#systemForm').reloadForm(Param.systemDefault);
		$('#systemComponentsForm').reloadForm(Param.systemComponentsDefault);
		$('#apiForm').reloadForm(Param.apiDefault);

		this.getSystemInfo();

		$(".select2").select2({
			tags : true ,
			tokenSeparators : [',' , ' ']
		});
	} ,


	//初始化各种按钮
	initBtn : function () {
		var self = this;

		$('input[name="module"]').change(function () {
			self.getSystemInfo();
		});

		$('select[name="tableName"]').change(function () {
			self.getSystemInfo();
		});

		$('#getSystemInfoBtn').on('click' , function (e) {
			e.preventDefault();
			self.getSystemInfo();
		});

		//创建系统页面
		$('.createSystemBtn').on('click' , function (e) {
			e.preventDefault();

			var $thisBtn = $(this);
			var temp = $(this).data('temp');
			var systemData = $('#systemForm').serializeObject();
			var componentsData = $('#systemComponentsForm').serializeObject();
			var data = {
				temp : temp ,
				module : systemData.module ,
				tableName : systemData.tableName ,
				components : componentsData ,
				_token : self.token
			};

			$.post(Param.uri.createSystem , data)
			 .fail(function (res) {
			 	tips.error(res.responseText);
			 })
			 .done(function (res) {
				 if ( res.code == 401 ) {
					 //需要登录
					 tips.error('请先登录');
				 } else if ( res.code != 0 ) {
				 	 tips.error(res.msg);
				 } else {
				 	 tips.success(res.msg);
					 if ( data.temp == 'all' ) {
					 	 for( var key in self.config.fileExists ) {
					 	   if ( !self.config.fileExists.hasOwnProperty( key ) ) {
								 continue;
					     }
						   self.config.fileExists[key] = true ;
					   }
					 	 $('.createSystemBtn').prop('disabled' , true);
						 $('.deleteSystemBtn').prop('disabled' , false);
					 } else {
						 $('.deleteSystemBtn[data-temp="' + temp + '"]').prop('disabled' , false);
						 self.config.fileExists[data.temp] = true ;
						 $thisBtn.prop('disabled' , true);
					 }
				 }
			 });
		});

		//创建接口
		$('#createApiBtn').on('click' , function (e) {
			e.preventDefault();
			var data = $('#apiForm').serializeObject();
			data._token = self.token;
			if ( empty(data.desc) ) {
				tips.error('描述不能为空');
			}
			if ( empty(data.directory) ) {
				tips.error('目录不能为空');
			}
			if ( empty(data.name) ) {
				tips.error('名称不能为空');
			}

			$.post(Param.uri.createApi , data)
			 .fail(function (res) {

			 })
			 .done(function (res) {
				 if ( res.code == 0 ) {
					 tips.success(res.msg);
				 } else {
					 tips.error(res.msg);
				 }
			 });
		});


		$('.deleteSystemBtn').on('click' , function (e) {
			e.preventDefault();

			var $thisBtn = $(this);
			var temp = $(this).data('temp');

			if ( ! self.config.fileExists[temp] ) {
				tips.error(temp + '文件不存在');
				return false ;
			}

			var data = $('#systemForm').serializeObject();
			data['temp'] = temp;

			sure.init('确认删除' + temp + '文件吗?' , function () {

				$.get(Param.uri.destroySystemFile , data)
				 .fail(function (res) {
					 tips.error(res.responseText);
				 })
				 .done(function (res) {
					 if ( res.code != 0 ) {
						 tips.error(res.msg);
					 } else {
						 $('.createSystemBtn[data-temp="' + temp + '"]').prop('disabled' , false);
						 $thisBtn.prop('disabled' , true);

						 self.config.fileExists[temp] = false;
						 $('.createSystemBtn[data-temp="all"]').prop('disabled' , false);
						 tips.success(res.msg);
					 }
				 });
			});
		});
	} ,

	getSystemInfo : function () {
		var self = this;

		var $form = $('#systemForm');
		var data = $form.serializeObject();
		data['type'] = $("#type_tabs").find("li.active > a").data('type');

		$.get(Param.uri.getSystemInfo , data)
		 .fail(function (res) {
			 tips.error(res.responseText);
		 })
		 .done(function (res) {
			 if ( res.code == 401 ) {
				 //需要登录
				 tips.error('请先登录');
			 } else if ( res.code != 0 ) {
				 tips.error(res.msg);
			 } else {
				 tips.success(res.msg);
				 var allFileExists = true;
				 self.config.fileExists = res.data.fileExists;
				 $.each( res.data.fileExists , function (key , val) {
					 $('.createSystemBtn[data-temp="' + key + '"]').prop('disabled' , val);
					 $('.deleteSystemBtn[data-temp="' + key + '"]').prop('disabled' , ! val);
					 if ( allFileExists && ! val ) {
						 allFileExists = false;
					 }
				 });
				 $('.createSystemBtn[data-temp="all"]').prop('disabled' , allFileExists);
				 self.setSelect(res.data);
			 }
		 });
	} ,

	setSelect : function (data) {
		var html = [];
		html.push('<option value="">请选择</option>');

		for ( var i = 0 ; i < data.fieldInfo.length ; i ++ ) {
			var field = data.fieldInfo[i]['fieldName'];
			var common = data.fieldInfo[i]['fieldComment'];
			html.push('<option value="' + field + '">' + field + ' (' + common + ')' + '</option>');
		}

		$('select[name="upload"]').html(html.join('')).val(data.components.upload).trigger('change');
		$('select[name="editor"]').html(html.join('')).val(data.components.editor).trigger('change');
		$('select[name="select2"]').html(html.join(''));
	}
};