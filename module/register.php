<?php
if(!defined("SNAPE_VN")) die('No access');

//include dirname(__FILE__).'/../recaptcha/autoload.php';

if($account->isLogin())
	movepage($_config['page']['fullurl'].'/tai-khoan/');

?>
<script type="text/javascript">
        $(document).ready(function () {

        	//$('#frmRegister').smoothScroll();

            $('#txtPassword').pwstrength(options);
			
			$("#frmRegister").validate({
				rules: {
					'txtEmail': {
						required: true,
						minlength: 4,
						maxlength: 100,
						remote: {
							url: "<?=$_config['page']['fullurl']?>/ajax/checkemail.php",
							type: "post"
						}
					},
					'txtPassword': {
						required: true,
						minlength: 6,
						maxlength: 100
					},
					'txtRePassword': {
						required: true,
						minlength: 6,
						maxlength: 100,
						equalTo: "#txtPassword"
					},
					'txtCode': {
						required: true
					}
				},
				messages: {
					txtEmail: {
						required: "Vui lòng nhập Tài khoản!",
						email: "Đây không phải là Tài khoản hợp lệ!",
						remote: "Tài khoản này đã có người sử dụng!"
					},
					txtCode: {
						required: "Vui lòng nhập chuỗi mã phía trên!"
					}
					
				},
				submitHandler: function(form) {
					//submit form
					$("#loading_text").show();
					$("#msg_err_login").hide();
					SnapeClass.disableSubmit();
					$.post(full_url + "/form/register.php", $("#frmRegister").serialize(), function(data) {
						if(data['type'] == 0) {
							window.location = full_url;
						} else {
							$("#msg_err_login").html(data['content']).show();
						}
						$("#loading_text").hide();
						SnapeClass.resetNewCaptcha('captchaImage');
						SnapeClass.undisableSubmit();
					}, 'json');
					return false;
				}
			});
			
			
        });
    </script>
	<form id="frmRegister" method="post">
		<div class="panel panel-default center-block">
			<div class="panel-heading">Đăng ký tài khoản</div>
			<div class="panel-body">
				<div class="row" style="padding-bottom:10px">
					<div class="col-md-8 col-md-offset-2">
						<!--div class="bg-success">
							<p class="highlight_text">Bạn có thể đăng ký nhanh thông qua các tài khoản</p>
							<div style="text-align:center;">
								<span class="mini_social_button"><a href="javascript::void(0)" onclick="SnapeClass.openSocialConnect('facebook');" class="btn btn-social btn-sm btn-facebook"><i class="fa fa-facebook"></i>Facebook</a></span>
								<span class="mini_social_button"><a href="javascript::void(0)" onclick="SnapeClass.openSocialConnect('google');" class="btn btn-social btn-sm btn-google"><i class="fa fa-google"></i>Google</a></span>
								<span class="mini_social_button"><a href="javascript::void(0)" onclick="SnapeClass.openSocialConnect('yahoo');" class="btn btn-social btn-sm btn-yahoo"><i class="fa fa-yahoo"></i>Yahoo!</a></span>
							</div>
						</div-->
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
							<div class="form-group">
								<label class="control-label sr-only"></label>
								<div class="text-danger" id="loading_text" style="display: none; text-align: center;">
									<img src="<?=$_config['page']['fullurl']?>/images/loading.gif">
									Đang kết nối ... 
								</div>
								<div id="msg_err_login" class="alert alert-danger" style="display: none"></div>
							</div>
							<div id="div_fullname" class="form-group">
								<label class="control-label" for="fullname_login">Họ và tên</label>
								<input type="text" id="txtFullName" name="txtFullName" autocomplete="off" class="form-control">
							</div>
							<div id="div_email" class="form-group">
								<label class="control-label" for="email_login">Tài Khoản</label>
								<input type="text" id="txtEmail" name="txtEmail" autocomplete="off" class="form-control">
							</div>
							<div id="div_password" class="form-group">
								<label class="control-label" for="password_login">Mật khẩu</label>
								<input type="password" id="txtPassword" name="txtPassword" autocomplete="off" class="form-control">
								<div class="pwstrength_viewport_progress"></div>
							</div>
							<div id="div_repassword" class="form-group">
								<label class="control-label" for="repassword_login">Nhập lại mật khẩu</label>
								<input type="password" id="txtRePassword" name="txtRePassword" autocomplete="off" class="form-control">
							</div>
							<div id="div_phone" class="form-group">
								<label class="control-label" for="phone_login">Số điện thoại</label>
								<input type="phone" id="txtPhone" name="txtPhone" autocomplete="off" class="form-control">
							</div>
							<!--div class="form-group">
								<label class="control-label" for="captcha_login">
									<a href="javascript:;" onclick="SnapeClass.resetNewCaptcha('captchaImage')"><img id="captchaImage" src="<?=$_config['page']['fullurl']?>/captcha.php" /></a>
								</label>
								
								<input type="text" id="txtCode" name="txtCode" autocomplete="off" class="form-control" placeholder="Nhập chuỗi phía trên">
								<!--<div class="g-recaptcha" data-sitekey="<?=$_config['recaptcha']['sitekey']?>"></div>
								<script type="text/javascript"
										src="https://www.google.com/recaptcha/api.js?hl=<?=$_config['recaptcha']['language']?>">
								</script>-->
							<!--/div-->
							<div class="form-group">
								<label class="control-label" for="captcha_login">
									<a href="javascript:;" onclick="SnapeClass.resetNewCaptcha('captchaImage')"><img id="captchaImage" src="<?=$_config['page']['fullurl']?>/captcha.php" /></a>
								</label>
								<input type="text" id="txtCode" name="txtCode" autocomplete="off" class="form-control" placeholder="Nhập chuỗi phía trên">
							</div>
							<div class="form-group">
								<label class="control-label sr-only"></label>
								<input type="submit" class="btn btn-primary btn-block" value="Đăng ký tài khoản mới">
								<a href="<?=$_config['page']['fullurl']?>/dang-nhap/" class="btn btn-success btn-block"><i class="glyphicon glyphicon-chevron-left"></i> Trở lại đăng nhập</a>
							</div>
						
					</div>
				</div>
				
			</div>
		</div>
	</form>