<?php
if(!defined("SNAPE_VN")) die ('No access');

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());


//$userInfo = $account->getUserInfo($_SESSION['ss_user_id']);

?>

<div style="padding-bottom: 17px;text-align:center;">
					<a href="<?=$_config['page']['fullurl']?>/chon-may-chu/"><img src="<?=$_config['page']['fullurl']?>/images/playnow1.gif" width="98%" /></a>
				</div>
				<div class="panel panel-default" style="margin-top: 0px;">
					<div class="panel-heading">Thông tin tài khoản</div>
					<div class="panel-body">
						<table class="table table-hover">
							<tr>
								<td style="border-top: 0px">Tài khoản: </td>
								<td style="border-top: 0px"><?=$account->getUsername()?></td>
							</tr>
							<tr>
								<td>Coin: </td>
								<td><?=number_format($userInfo['Money'])?></td>
							</tr>
							<tr>
								<td>Điểm: </td>
								<td><?=number_format($userInfo['Point'])?></td>
							</tr>
							<tr>
								<td>Coin Game: </td>
								<td><a href="<?=$_config['page']['fullurl']?>/hop-qua/">[Xem]</a> <a href="<?=$_config['page']['fullurl']?>/tai-khoan/doi-coin-game/">[Đổi CG]</a></td>
							</tr>
							<tr>
								<td>Điểm: </td>
								<td><?=number_format($userInfo['Point'])?></td>
							</tr>
						</table>
					</div>
					<div class="form-group">
						<div style="text-align:right; padding-right: 15px;">
							<a href="<?=$_config['page']['fullurl']?>/tai-khoan/" class="btn btn-primary">Trang tài khoản</a>
							<a href="javascript:;" onclick="SnapeClass.logout()" class="btn btn-danger">Thoát</a>
						</div>
					</div>
				</div>