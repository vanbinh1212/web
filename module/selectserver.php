<?php
if(!defined("SNAPE_VN")) die('No access');

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());

?>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div id="msg_err_spin" class="alert alert-danger" style="display: none"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 menu-left">
				<h4 class="alert alert-danger" role="alert">Hỗ trợ nhanh: <a href="ymsgr:sendIM?hotrogunny30"><img src="<?=$_config['page']['fullurl']?>/images/yahoo_icon.png" width="20px" /> hotroHiGun</a></h4>
				<div class="page-header">
				    <h4>Danh mục quản lý</h4>
				</div>
				<div class="list-group">
					<a href="<?=$_config['page']['fullurl']?>/chon-may-chu/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-send"></span> Chơi game ngay</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-user"></span> Quản lý tài khoản</a>
				</div>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default" style="margin-top: 25px;">
					<div id="namebox" class="panel-heading">Chọn máy chủ</div>
					<div class="panel-body">
						<div align="center"> Loading...
						<?php
						movepage($_config['page']['fullurl'] . '/play/index.php');
						?>
						</div>
					</div>
				</div>
			</div>
		</div>