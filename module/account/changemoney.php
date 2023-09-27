<?php
if(!defined("SNAPE_VN")) die('No access');

?>
<script type="text/javascript">
    $(document).ready(function () {
		
		//SnapeClass.openModal("Cảnh báo", "<font color='red'><b>Tính năng này tạm dừng cho tới khi hệ thống gộp máy chủ thành công. Vui lòng quay lại sau.</b></font>", []);
		
    	$("#frmChangePassword").validate({
    		rules: {
    			'txtServer': {
    				required: true,
					number: true,
					min: 1
    			},
    			'txtCoin': {
    				required: true,
    				number: true,
					min: 1000,
					max: 10000000
    			},
				'txtCode': {
					required: true
				}
    		},
    		messages: {
				txtServer: {
					required: "Vui lòng chọn máy chủ.",
					number: "Máy chủ không hợp lệ!",
					min: "Vui lòng chọn máy chủ."
				},
				txtCoin: {
					required: "Vui lòng nhập Coin muốn chuyển",
					number: "Coin chuyển phải là số hợp lệ.",
					min: "Coin chuyển tối thiểu là 1.000",
					max: "Coin chuyển tối đa là 10.000.000"
				}
			},
			submitHandler: function(form) {
				SnapeClass.disableSubmit();
				$.post(full_url + "/form/changemoney.php", $("#frmChangePassword").serialize(), function(data) {
					if(data['type'] == 0) {
						SnapeClass.openModal("Thành công", "Chuyển Xu thành công. Bạn nhận được "+data['money']+" Xu vào máy chủ "+data['servername'], []);
						SnapeClass.clearAllInput();
					} else {
						SnapeClass.openModal("Lỗi chuyển Xu", data['content'], []);
					}
					SnapeClass.resetNewCaptcha('captchaImage');
					SnapeClass.undisableSubmit();
				}, 'json');
				return false;
			}
    	});
		
		// check money
		$("#txtCoin").keyup(function() {
			if($.isNumeric($("#txtCoin").val())) {
				var count = parseInt($("#txtCoin").val()) * <?php echo $_config['config_xu'];?>;
				
				$("#txtMoneyReceive").val(count);
			}
		});
	});
</script>
<form id="frmChangePassword" method="post">
	<div class="center-block" style="width:80%">
		<div class="form-group">
			<?php
				$maxAmount = 10000;
				if($_config['config_xu'] >= 10000) {
					$maxAmount = 2000;
				}
			?>
			<div id="msg_err_login" class="alert alert-danger">- Tỉ lệ chuyển đổi là <?php echo number_format($maxAmount);?> Coin =  <?php echo number_format($maxAmount * $_config['config_xu']);?> Xu<br/>- Lần đầu chuyển Xu nhận quà nạp thẻ lần đầu trong Game.</div>
		</div>
		<div id="div_password" class="form-group">
			<label class="control-label" for="email_login">Máy chủ nhận:</label>
			<select id="txtServer" name="txtServer" class="form-control">
				<option value="0">-- Chọn máy chủ --</option>
				<?php
				// load server list
				$loadserver = loadallserver();
				while($svInfo = sqlsrv_fetch_array($loadserver, SQLSRV_FETCH_ASSOC)) {
					echo '<option value="'.$svInfo['ServerID'].'">'.$svInfo['ServerName'].'</option>';
				}
				?>
			</select>
		</div>
		<div id="div_newpassword" class="form-group">
			<label class="control-label" for="email_login">Coin muốn chuyển:</label>
			<input type="text" id="txtCoin" name="txtCoin" autocomplete="off" class="form-control">
		</div>
		<div id="div_repassword" class="form-group">
			<label class="control-label" for="email_login">Xu nhận được:</label>
			<input type="text" id="txtMoneyReceive" name="txtMoneyReceive" autocomplete="off" disabled="disabled" class="form-control">
		</div>
		<!--div class="form-group">
			<label class="control-label" for="captcha_login">
				<a href="javascript:;" onclick="SnapeClass.resetNewCaptcha('captchaImage')"><img id="captchaImage" src="<?=$_config['page']['fullurl']?>/captcha.php" /></a>
			</label>
			
			<input type="text" id="txtCode" name="txtCode" autocomplete="off" class="form-control" placeholder="Nhập chuỗi phía trên">
		</div-->
		<div class="form-group">
			<label class="control-label" for="captcha_login">
				<a href="javascript:;" onclick="SnapeClass.resetNewCaptcha('captchaImage')"><img id="captchaImage" src="<?=$_config['page']['fullurl']?>/captcha.php" /></a>
			</label>
			
			<input type="text" id="txtCode" name="txtCode" autocomplete="off" class="form-control" placeholder="Nhập chuỗi phía trên">
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary btn-block" value="Chuyển Xu">
		</div>
	</div>
</form>