<?php
if(!defined("SNAPE_VN")) die('No access');
?>
<script type="text/javascript">
    $(document).ready(function () {
		$( "#tranId" ).keyup(function() {
			var tranId = $('#tranId').val();
			tranId = tranId.replace(/\D/gm, '');			
			$(this).val(tranId);
		});

    	$("#frmReCharge").validate({
    		rules: {
    			'tranId': {
    				required: true,
    				minlength: 5
    			},
				'txtCode': {
					required: true
				}			
    		},
			submitHandler: function(form) {
				// check button
				SnapeClass.disableSubmit();
				$.post(full_url + "/form/rechargemomo.php", $("#frmReCharge").serialize(), function(data) {
					
					if(data['type'] == 0) {
						SnapeClass.clearAllInput();
						//SnapeClass.openModal("Thông báo", "<font color='blue'><b>" + data['content'] + "</b></font>", [{Name:"Webshop", Link: full_url + '/tai-khoan/cua-hang/'}, {Name:"Chuyển Xu", Link: full_url + '/tai-khoan/chuyen-xu/'}, {Name:"Túi đồ web", Link: full_url + '/tai-khoan/tui-web/'}]);
					} else {
						SnapeClass.openModal("Thông báo", "<font color='red'><b>" + data['content'] + "</b></font>", []);
					}
					
					SnapeClass.resetNewCaptcha('captchaImage');
					SnapeClass.undisableSubmit();
				}, 'json');
				return false;
			}
    	});
	});
</script>

<form id="frmReCharge" method="post">
	<div class="center-block" style="width:80%">
		<div class="form-group">
			<div id="msg_err_login" class="alert alert-danger">
				<div>- Tỉ lệ nạp thẻ là 1:1 (10.000 VNĐ = 10.000 Coin)</div>
				<div>- Coin nạp sử dụng trong <a href="<?=$_config['page']['fullurl']?>/tai-khoan/chuyen-xu/">Chuyển Xu</a></div>
					- Nhập sai STK or Ngân Hàng BQT không chịu trách nhiệm<br />
					<b><font color="#008800">Lưu ý : Hạn mức nạp tối thiểu là 100.000 VNĐ - Nếu thấp hơn 100.000 VNĐ Hệ thống sẽ không tự động cộng Coins vào tài khoản !</font>
				<div> <font color="#0c5460">- Lưu ý: <b><font color="#008800"> Chuyển khoản<font color="#CC0099"> ATM</font></b> có thể bị trễ 10 -> 15 phút mong mọi người thông cảm. <a target="_blank" href="<?=$_config['Contact']['FB']?>">(Liên hệ Fanpage để biết thêm chi tiết)</a></font></div>
				<div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div id="msg_err_login" class="alert alert-danger">
				<div>
					<b>
					<font color="#0c5460">- Khuyến khích</font>
					<font color="#008800"> Chuyển khoản </font> <font color="#CC0099">Internet Banking</font>
					để được khuyến mãi 10% 
					<font color="#0c5460"> <a target="_blank" href="<?php echo $_config['Contact']['FB'];?>">(Liên hệ Fanpage để biết thêm chi tiết)</a></font>
					<br>
					<font color="#4c9900"> Lưu ý : Khi <u>Chuyển Tiền</u> xong các bạn vui lòng đợi 5-10p hệ thống sẽ tự động cộng coins (Nếu thấy lâu inbox lại Fanpage HiGun ngay để BQT check và cộng Coins nhanh nhất) </font>
					</br>
					<font color="#0c5460">- Thông tin  <font color="#4c9900">MOMO</font> Chủ tài khoản: <font color="#CC0099"><?php echo $_config['MOMO']['Name'];?></font>
					<br>- STK: <font color="#CC0099"><?php echo $_config['MOMO']['SDT'];?></font> </font>
					<font color="#0c5460">- Thông tin  <font color="#4c9900">ATM: Ngân hàng Techcombank</font> Chủ tài khoản: <font color="#CC0099"><?php echo $_config['MOMO']['Name'];?></font>
					<br>- STK: <font color="#CC0099">1516122193;?></font> </font>
					<br>NỘI DUNG CHUYỂN KHOẢN <font color="#4c9900">[ten_tai_khoan]</font> : <b><?=$_SESSION['ss_user_email']?>
					<br></br>Sau khi nạp tiền xong vui lòng chờ trong ít phút hệ thống sẽ tự động check và chuyển Coins vào tài khoản của bạn!</b>
					<font color="#0c5460"> <a target="_blank" href="<?php echo $_config['Contact']['FB'];?>">(Fanpage HiGun)</a></font>
					
				</b>
				</div>
			</div>
		</div>
		</div>
		
			  </tr>
			</thead>
			<tbody>
			<?php
			$qTop = sqlsrv_query($conn, "select  top 10 a.UserID,b.Email,sum(a.Money) as [TongNap] from Log_Card a inner join Member_GMP.dbo.Mem_Account b on a.UserID=b.UserID 
 where a.TimeCreate > '1600387200' group by a.UserID,b.Email  ORDER by [TongNap] desc", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
			
			$iTop = 1;
			if(sqlsrv_num_rows($qTop) > 0) {

				while($topInfo = sqlsrv_fetch_array($qTop, SQLSRV_FETCH_ASSOC)) {
					
					$realEmail = $topInfo['Email'];
					$realTongNap = $topInfo['TongNap'] . " VNĐ";
					$classHig = 'success';
					if($realEmail != $_SESSION['ss_user_email']) {
						$emailArr = explode("@", $topInfo['Email']);
						$TongNapArr = explode("VNĐ", $topInfo['TongNap']);
						$realEmail = substr($emailArr[0], 0, (strlen($emailArr[0]) / 1.3)).'***@'.$emailArr[1];			
						$classHig = '';
					}
					echo '<tr class="'.$classHig.'">
				<td>'.$iTop.'</td>
				<td>'.$realEmail.'</td>
				<td>'.$realTongNap.'</td>
			  </tr>';
					$iTop++;
				}

			}
			?>
			</tbody>
		  </table>
	</div>
</form>