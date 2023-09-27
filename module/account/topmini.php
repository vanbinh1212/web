<?php
if(!defined("SNAPE_VN")) die('No access');

?>
		<div class="alert alert-success" role="alert">TOP lực chiến tài khoản tạo từ 00h 15-03 trở lên.</div>
		<table class="table table-hover">
			<thead>
			  <tr>
				<th>Xếp hạng</th>
				<th>Tài khoản</th>
				<th>Tên nhân vật</th>
				<th>Cấp Độ</th>
				<th>Lực chiến</th>
			  </tr>
			</thead>
			<tbody>
			<?php
			$qTop = sqlsrv_query($conn, "SELECT TOP 10 UserName,NickName,Grade,FightPower FROM Tank41.dbo.Sys_Users_Detail WHERE IsExist = 'True' and UserID > 4977 ORDER by FightPower DESC", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
			
			$iTop = 1;
			if(sqlsrv_num_rows($qTop) > 0) {

				while($topInfo = sqlsrv_fetch_array($qTop, SQLSRV_FETCH_ASSOC)) {
					
					$realEmail = $topInfo['UserName'];
					$realNickname = $topInfo['NickName'];
					$realTongNap = $topInfo['FightPower'] . "";
					$realGrade = $topInfo['Grade'] . "";
					$classHig = 'success';
					if($realEmail != $_SESSION['ss_user_email']) {
						$emailArr = explode("@", $topInfo['UserName']);
						$NickNameArr = explode($topInfo['NickName']);
						$GradeArr = explode($topInfo['Grade']);
						$TongNapArr = explode("Phút", $topInfo['FightPower']);
								
						$classHig = '';
					}
					echo '<tr class="'.$classHig.'">
				<td>'.$iTop.'</td>
				<td>'.$realEmail.'</td>
				<td>'.$realNickname.'</td>
				<td>'.$realGrade.'</td>
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