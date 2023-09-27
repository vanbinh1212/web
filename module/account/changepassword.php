<?php
if(!defined("SNAPE_VN")) die('No access');

?>
<script type="text/javascript">
    $(document).ready(function () {
    	$('#txtNewPassword').pwstrength(options);
    	$("#frmChangePassword").validate({
    		rules: {
    			<? if($userInfo['Password'] != null) { ?>
    			'txtPassword': {
    				required: true,
    				minlength: 6,
					maxlength: 100
    			},
    			<? } ?>
    			'txtRePassword': {
    				required: true,
    				minlength: 6,
					maxlength: 100,
					equalTo: "#txtNewPassword"
    			},
    			'txtNewPassword': {
    				required: true,
    				minlength: 6,
					maxlength: 100
    			},
				'txtCode': {
					required: true
				}
    		},
    		messages: {
				txtRePassword: {
					required: "Vui lòng nhập lại mật khẩu mới!",
					equalTo: "Nhập lại mật khẩu không khớp với mật khẩu mới!"
				}
			},
			submitHandler: function(form) {
				SnapeClass.disableSubmit();
				$.post(full_url + "/form/changepassword.php", $("#frmChangePassword").serialize(), function(data) {
					if(data['type'] == 0) {
						SnapeClass.openModal("Thành công", "Chúc mừng bạn đã đổi mật khẩu thành công. Bạn có thể đăng nhập <?=$_SESSION['ss_user_email']?> với mật khẩu mới.", []);
						SnapeClass.clearAllInput();
					} else {
						SnapeClass.openModal("Lỗi đổi mật khẩu", data['content'], []);
					}
					SnapeClass.resetNewCaptcha('captchaImage');
					SnapeClass.undisableSubmit();
				}, 'json');
				return false;
			}
    	});
	});
</script>
<form id="frmChangePassword" method="post">
	<div class="center-block" style="width:80%">
		<div class="form-group">
			<div id="msg_err_login" class="alert alert-danger">Nếu tài khoản của bạn đăng ký qua Facebook, Yahoo hoặc Google thì mật khẩu cũ có thể bỏ trống. Sau khi đổi mật khẩu các bạn có thể đăng nhập theo Email và mật khẩu mới.</div>
		</div>
		<div id="div_password" class="form-group">
			<label class="control-label" for="email_login">Mật khẩu cũ:</label>
			<input type="password" id="txtPassword" name="txtPassword" autocomplete="off" class="form-control" <?=(($userInfo['Password'] == null) ? 'disabled' : '')?>>
		</div>
		<div id="div_newpassword" class="form-group">
			<label class="control-label" for="email_login">Mật khẩu mới:</label>
			<input type="password" id="txtNewPassword" name="txtNewPassword" autocomplete="off" class="form-control">
			<div class="pwstrength_viewport_progress"></div>
		</div>
		<div id="div_repassword" class="form-group">
			<label class="control-label" for="email_login">Nhập lại mật khẩu:</label>
			<input type="password" id="txtRePassword" name="txtRePassword" autocomplete="off" class="form-control">
		</div>
		<div class="form-group">
			<label class="control-label" for="captcha_login">
				<a href="javascript:;" onclick="SnapeClass.resetNewCaptcha('captchaImage')"><img id="captchaImage" src="<?=$_config['page']['fullurl']?>/captcha.php" /></a>
			</label>
			
			<input type="text" id="txtCode" name="txtCode" autocomplete="off" class="form-control" placeholder="Nhập chuỗi phía trên">
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary btn-block" value="Đổi mật khẩu">
		</div>
	</div>
</form>