<?php
if(!defined("SNAPE_VN")) die('No access');

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());

/*
if(($userInfo['Fullname'] == null || $userInfo['Phone'] == null) && !$_SESSION['ss_notice_update_info'] && $_SESSION['ss_notice_update_password']) {

	$text = "Bạn chưa cập nhật SDT hoặc Tên chủ tài khoản. <font color='red'>Hãy cập nhật để được tăng thêm Xu khi thi đấu trong game.</font><br/><br/>";

	if($userInfo['Fullname'] == null)
		$text .= "+ Cập nhật <b>Tên chủ tài khoản:</b> Liên hệ GM để cập nhật, truy cập vào trang <a href='".$_config['page']['fullurl']."/ho-tro/'>Hỗ trợ</a> để yêu cầu.";

	if($userInfo['Phone'] == null)
		$text .= "<br/>+ Cập nhật <b>Số điện thoại:</b> Soạn tin <b>XACTHUC HD</b> gửi 8085.";

	echo '<script>SnapeClass.openModal("Cảnh báo", "'.$text.'", [])</script>';

	$_SESSION['ss_notice_update_info'] = true;
}*/

$accountPage = remoteAccount($_REQUEST['module']);

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
					<?php if($_SESSION['ss_user_email'] == $_config['panel']['Administrator']){
					echo '<a href="'.$_config['page']['fullurl'].'/panel-admin/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-home"></span> [PANEL] Quản Lý</a>';	
					}
					?>
					<a href="<?=$_config['page']['fullurl']?>/chon-may-chu/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-send"></span> Chơi game ngay</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/doi-mat-khau/" class="list-group-item <?=(($accountPage['content'] == 'changepassword') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-th-large"></span> Đổi mật khẩu</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/nap-momo/" class="list-group-item <?=(($accountPage['content'] == 'rechargemomo') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-usd"></span> Nạp ATM or MOMO</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/chuyen-xu/" class="list-group-item <?=(($accountPage['content'] == 'changemoney') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-refresh"></span> Chuyển Xu</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/lich-su-giao-dich/" class="list-group-item <?=(($accountPage['content'] == 'history') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-list-alt"></span> Lịch sử giao dịch</a>
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
						  <?=(($accountPage['content'] == 'accountinfo') ? '<li class="active">Tài khoản</li>' : '<li><a href="'.$_config['page']['fullurl'].'/tai-khoan/">Tài khoản</a></li><li class="active">'.$accountPage['title'].'</li>')?>
						</ol>
					</div>
					
				</div>
				<div class="panel panel-default" style="margin-top: 0px;">
					<div id="namebox" class="panel-heading"><?=$accountPage['title']?></div>
					<div class="panel-body">
						<? include dirname(__FILE__).'/account/'.$accountPage['content'].'.php'; ?>
					</div>
				</div>
			</div>
		</div>