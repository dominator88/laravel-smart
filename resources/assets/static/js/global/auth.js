/**
 * Auth 页面 JS
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2016-09-11
 */

var Auth = {
	initLogin : function () {
		loading.initAjax();

		//背景幻灯片
		$.backstretch( Param.bg , {
				fade : 1000 ,
				duration : 8000
			}
		);
		var $loginBtn = $('#loginBtn');
		$loginBtn.prop('disabled' , false);

		var $loginForm = $('.login-form');
		$loginForm.reloadForm(Param.defaultData);

		var $forgetForm = $('.forget-form');

		$loginForm.on('submit' , function (e) {
			e.preventDefault();
			var loginData = $(this).serializeObject();
			//检查用户信息
			if ( $.trim(loginData.userInfo) == '' ) {
				tips.error('请填写用户名或手机号');
				return false;
			}
			//检查密码
			if ( $.trim(loginData.password) == '' ) {
				tips.error('请填写密码');
				return false;
			}

			$loginBtn.prop('disabled' , true); //禁用登录按钮

			// Send the data using post
			$.post(Param.uri.doLogin , loginData)
			 .fail(function (error) {
				 //ajax出现错误
				 $loginBtn.prop('disabled' , false);
				 tips.error('发送错误了');
				 console.log(error);
			 })
			 .done(function (res) {
				 //ajax返回
				 if ( res.code === 0 ) {
					 //返回成功
					 tips.success(res.msg , function () {
						 window.location.href = Param.uri.redirect;
					 });

					 return true;
				 }
				 $loginBtn.prop('disabled' , false);
				 tips.error(res.msg);
			 });
		});

		//点击忘记密码
		$('#forget-password').on('click' , function (e) {
			e.preventDefault();
			$loginForm.hide();
			$forgetForm.show();
		});

		//返回登录页面
		$('#back-btn').on('click' , function(e){
			e.preventDefault();
			$loginForm.show();
			$forgetForm.hide();
		});

		$forgetForm.on('submit' , function(e){
			e.preventDefault();

			tips.success('邮件已经发送,请根据提示进行下一步操作' , function () {
				$loginForm.show();
				$forgetForm.hide();
			});
		});

	}

};