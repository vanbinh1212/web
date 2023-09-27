<?php
if(!defined("SNAPE_VN")) die('No access');

?>
<script type="text/javascript">
    $(document).ready(function () {
    	$("#frmSupport").validate({
    		rules: {
    			'txtType': {
    				required: true
    			},
    			'txtTitle': {
    				required: true,
    				minlength: 3,
					maxlength: 100
    			},
    			'txtContent': {
    				required: true,
    				minlength: 10,
					maxlength: 5000
    			}
    		},
    		messages: {
				txtType: {
					required: "Vui lòng chọn loại hỗ trợ!"
				}
			},
			submitHandler: function(form) {
				SnapeClass.disableSubmit();
				$.post(full_url + "/form/newsupport.php", $("#frmSupport").serialize(), function(data) {
					if(data['type'] == 0) {
						SnapeClass.openModal("Thành công", "Bạn đã gửi yêu cầu thành công. BQT sẽ trả lời bạn nhanh nhất có thể.", []);
						SnapeClass.clearAllInput();
					} else {
						SnapeClass.openModal("Lỗi yêu cầu hỗ trợ", data['content'], []);
					}
					SnapeClass.resetCaptcha();
					SnapeClass.undisableSubmit();
				}, 'json');
				return false;
			}
    	});
	});
</script>
<form id="frmSupport" method="post">
	<div class="center-block" style="width:80%">
		<div class="form-group">
			<div id="msg_err_login" class="alert alert-danger">
				<div>1. Chọn mục yêu cầu phù hợp với yêu cầu của bạn muốn hỗ trợ.</div>
				<div>2. Nhập thông tin yêu cầu rõ ràng, chi tiết và đúng chuyên mục. Nếu không BQT có quyền từ chối trả lời.</div>
				<div>3. Thời gian hồi âm tùy thuộc vào thời gian làm việc của BQT. Thời gian chậm nhất là 2 ngày.</div>
			</div>
		</div>
		<div id="div_password" class="form-group">
			<label class="control-label" for="email_login">Email yêu cầu:</label>
			<input type="text" id="txtEmail" name="txtEmail" autocomplete="off" class="form-control" readonly value="<?=$_SESSION['ss_user_email']?>" >
		</div>
		<div id="div_newpassword" class="form-group">
			<label class="control-label" for="email_login">Tiêu đề:</label>
			<input type="text" id="txtTitle" name="txtTitle" autocomplete="off" class="form-control">
		</div>
		<div id="div_repassword" class="form-group">
			<label class="control-label" for="email_login">Loại hỗ trợ:</label>
			<select id="txtType" name="txtType" class="form-control">
				<option value="">-- Chọn mục hỗ trợ --</option>
				<?php
				// load 
				$loadSupCat = loadAllSupportCategory();
				while($supCatInfo = sqlsrv_fetch_array($loadSupCat, SQLSRV_FETCH_ASSOC)) {
					echo '<option value="'.$supCatInfo['ID'].'">'.$supCatInfo['Name'].'</option>';
				}
				?>
			</select>
		</div>
		<div id="div_repassword" class="form-group">
			<label class="control-label" for="email_login">Nội dung:</label>
			<textarea id="txtContent" name="txtContent" class="form-control" rows="5"></textarea>
		</div>
		<div class="form-group">
			<label class="control-label" for="captcha_login">Human Verify</label>
			<div style="text-align: center;"><div class="g-recaptcha" data-sitekey="<?=$_config['recaptcha']['sitekey']?>" style="display: inline;"></div></div>
			<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?=$_config['recaptcha']['language']?>"></script>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary btn-block" value="Gửi yêu cầu hỗ trợ">
			<a href="<?=$_config['Contact']['FB']?>" class="btn btn-success btn-block"><i class="glyphicon glyphicon-chevron-left"></i> Danh sách hỗ trợ</a>
		</div>
	</div>
</form>