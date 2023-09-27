<?php
if(!defined("SNAPE_VN")) die('No access');

$facebookInfo = $account->getSocialConnect($_SESSION['ss_user_id'], 'facebook');

$googleInfo = $account->getSocialConnect($_SESSION['ss_user_id'], 'google');

$yahooInfo = $account->getSocialConnect($_SESSION['ss_user_id'], 'yahoo');
?>

						<div class="center-block">
							<table class="table table-hover">
								<tr>
									<td style="border-top: 0px">Địa chỉ Email: </td>
									<td style="border-top: 0px"><?=$_SESSION['ss_user_email']?></td>
								</tr>
								<tr>
									<td>Coin: </td>
									<td><?=number_format($userInfo['Money'])?></td>
								</tr>
								
								<!--<tr>
									<td>Họ và tên: </td>
									<td><?=(($userInfo['Fullname'] == null) ? '<span class="disconnect_social" data-toggle="tooltip" data-placement="top" title="Tính năng sắp được cập nhật">Chưa cập nhật</span>' : '<span class="connected_social" data-toggle="tooltip" data-placement="top" title="Được tặng thêm 30% Xu khi thi đấu">'.$userInfo['Fullname'].'</span>') ?></td>
								</tr>
								<tr>
									<td>Số điện thoại: </td>
									<td><?=(($userInfo['Phone'] == null) ? '<span class="disconnect_social" data-toggle="tooltip" data-placement="top" title="Tính năng sắp được cập nhật">Chưa đăng ký</span>' : '<span class="connected_social" data-toggle="tooltip" data-placement="top" title="Được tặng thêm 50% Xu khi thi đấu">'.$userInfo['Phone'].'</span>') ?></td>
								</tr>-->
							</table>
						</div>