<?php
if(!defined("SNAPE_VN")) die('No access');
?>
		<div class="alert alert-success" role="alert">TOP Online Gà Hắc Ám từ 00h ngày 27/07/2018.</div>
		<table class="table table-hover">
			<thead>
			  <tr>
				<th>Xếp hạng</th>
				<th>Tài khoản</th>
				<th>Tên nhân vật</th>
				<th>Online</th>
				
			  </tr>
			</thead>
			<tbody>
			<?php
			$qTop = sqlsrv_query($conn, "SELECT TOP 10 UserName,NickName,OnlineTime FROM Tank41_S3.dbo.Sys_Users_Detail WHERE IsExist = 'True' ORDER by OnlineTime DESC", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
			
			$iTop = 1;
			if(sqlsrv_num_rows($qTop) > 0) {

				while($topInfo = sqlsrv_fetch_array($qTop, SQLSRV_FETCH_ASSOC)) {
					
					$realEmail = $topInfo['UserName'];
					$realNickname = $topInfo['NickName'];
					$realTongNap = $topInfo['OnlineTime'] . " Phút";
					$classHig = 'success';
					if($realEmail != $_SESSION['ss_user_email']) {
						
						$NickNameArr = explode($topInfo['NickName']);
						$GradeArr = explode($topInfo['Grade']);
						$TongNapArr = explode("Phút", $topInfo['OnlineTime']);
						$realEmail = substr($emailArr[0], 0, (strlen($emailArr[0]) / 1.3)).'xxxxxxxxxx'.$emailArr[1];			
						$classHig = '';
					}
					echo '<tr class="'.$classHig.'">
				<td>'.$iTop.'</td>
				<td>'.$realEmail.'</td>
				<td>'.$realNickname.'</td>
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