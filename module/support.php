<?php
if(!defined("SNAPE_VN")) die('No access');

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());

$accountPage = remoteSupport($_REQUEST['module']);

?>

		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div id="msg_err_spin" class="alert alert-danger" style="display: none"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 menu-left">
				<div class="page-header">
				    <h4>Danh mục quản lý</h4>
				</div>
				
				<div class="list-group">
					<a href="<?=$_config['page']['fullurl']?>" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-home"></span> Trang chủ</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-user"></span> Thông tin tài khoản</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/nap-the/" class="list-group-item <?=(($accountPage['content'] == 'recharge') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-usd"></span> Nạp thẻ ( Bảo Trì)</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/nap-momo/" class="list-group-item <?=(($accountPage['content'] == 'rechargemomo') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-usd"></span> Nạp ATM or MOMO</a>
					<a href="javascript:;" onclick="SnapeClass.logout()" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-log-in"></span> Thoát đăng nhập</a>
				</div>
				<div>
					
				</div>
			</div>
			<div class="col-md-8">
				<div class="row">

					<div class="col-md-12" style="margin-top: 20px;">
						<ol class="breadcrumb">
						  <li><a href="<?=$_config['page']['fullurl']?>">Trang chủ</a></li>
						  <?=(($accountPage['content'] == 'listsupport') ? '<li class="active">Hỗ trợ</li>' : '<li><a href="'.$_config['page']['fullurl'].'/ho-tro/">Hỗ trợ</a></li><li class="active">'.$accountPage['title'].'</li>')?>
						</ol>
					</div>
					
				</div>
				<div class="panel panel-default" style="margin-top: 0px;">
					<div id="namebox" class="panel-heading"><?=$accountPage['title']?></div>
					<div class="panel-body">
						<? include dirname(__FILE__).'/support/'.$accountPage['content'].'.php'; ?>
					</div>
				</div>
			</div>
		</div>