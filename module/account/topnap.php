<?php
if(!defined("SNAPE_VN")) die('No access');

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());
if($_SESSION['ss_user_email'] != $_config['panel']['Administrator']){
		movepage($_config['page']['fullurl'].'');
}
?>
		<div class="alert alert-success" role="alert">TOP Nạp thẻ từ 0h00 ngày 21/02.</div>
		<table class="table table-hover">
			<thead>
			  <tr>
				<th>Xếp hạng</th>
				<th>Tài khoản</th>
				<th>Tổng nạp</th>
			  </tr>
			</thead>
			<tbody>
			<?php
			$qTop = sqlsrv_query($conn, "select  top 1000 a.UserID,b.Email,sum(a.Money) as [TongNap] from Log_Card a inner join Member_GMP.dbo.Mem_Account b on a.UserID=b.UserID 
 where a.TimeCreate > '1519171200' group by a.UserID,b.Email  ORDER by [TongNap] desc", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
			
			$iTop = 1;
			if(sqlsrv_num_rows($qTop) > 0) {

				while($topInfo = sqlsrv_fetch_array($qTop, SQLSRV_FETCH_ASSOC)) {
					
					$realEmail = $topInfo['Email'];
					$realTongNap = $topInfo['TongNap'] . " VNĐ";
					$classHig = 'success';
					if($realEmail != $_SESSION['ss_user_email']) {
						$emailArr = explode("@", $topInfo['Email']);
						$TongNapArr = explode("VNĐ", $topInfo['TongNap']);
							
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
			<?php
			$qTop2 = sqlsrv_query($conn, "select sum(money) as [Tong] from log_card where TimeCreate > '1516492800'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
			
			$iTop = 1;
			if(sqlsrv_num_rows($qTop2) > 0) {

				while($topInfo = sqlsrv_fetch_array($qTop2, SQLSRV_FETCH_ASSOC)) {

					$realTongNap2 = $topInfo['Tong'] . " VNĐ";
					$realTongNap3 = $realTongNap2 * 0.78 . " VNĐ";
					$classHig = 'success';
					if($realEmail2 != $_SESSION['ss_user_email']) {
						$TongNapArr2 = explode("VNĐ", $topInfo['Tong']);
							
						$classHig2 = '';
					}
					echo "Tổng nạp : " . $realTongNap2 . " ............. " . "Thực Nhận : " . $realTongNap3;
					echo '<tr class="'.$classHig2.'">
			  </tr>';
					$iTop2++;
				}

			}
			?>			
			</tbody>
		  </table>
			</tbody>
		  </table>
	</div>
</form>