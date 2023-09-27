<?php
if(!defined("SNAPE_VN")) die('No access');

if(!$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());

?>
<script type="text/javascript" src="<?=$_config['page']['fullurl']?>/js/load_news.js?update="<?=time()?>></script>
<script type="text/javascript">
        $(document).ready(function () {
        	// SnapeNews.init(1);
			//SnapeGuild.guildHome().show();
			
			SnapeNews.getTop('FightPower');
			
			$('.levelTop').click(function() {SnapeNews.getTop('Level')});
			$('.fightPowerTop').click(function() {SnapeNews.getTop('FightPower')});
        });
		
    </script>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div id="msg_err_spin" class="alert alert-danger" style="display: none"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 menu-left">
			<div style="padding-bottom: 17px;text-align:center;">
					<a href="<?=$_config['page']['fullurl']?>/chon-may-chu/"><img src="<?=$_config['page']['fullurl']?>/images/playnow1.gif" width="98%"></a>
				</div>
				<div class="panel-heading">
				</div>
				<div class="list-group">
					<a href="<?=$_config['page']['fullurl']?>/chon-may-chu/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-send"></span> Chơi game ngay</a>
					<a href="<?=$_config['page']['fullurl']?>" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-home"></span> Trang chủ</a>
					<?php if($_SESSION['ss_user_email'] == $_config['panel']['Administrator']){
					echo '<a href="'.$_config['page']['fullurl'].'/panel-admin/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-home"></span> [PANEL] Quản Lý</a>';	
					}
					?>
					<a href="<?=$_config['page']['fullurl']?>/quan-ly/" class="list-group-item <?=(($accountPage['content'] == 'adm_notice') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-user"></span> Thông báo server</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/nap-momo/" class="list-group-item <?=(($accountPage['content'] == 'rechargemomo') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-usd"></span> Nạp ATM or MOMO</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/doi-mat-khau/" class="list-group-item <?=(($accountPage['content'] == 'changepassword') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-th-large"></span> Đổi mật khẩu</a>
					<a href="<?=$_config['page']['fullurl']?>/tai-khoan/chuyen-xu/" class="list-group-item <?=(($accountPage['content'] == 'changemoney') ? 'active' : '')?>"><span class="icon_menulist glyphicon glyphicon-refresh"></span> Chuyển Xu</a>
					<a id="guildAccount" href="<?=$_config['page']['fullurl']?>/tai-khoan/" class="list-group-item"><span class="icon_menulist glyphicon glyphicon-user"></span> Quản lý tài khoản</a>
				</div>
				<div>
					
				</div>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default" style="margin-top: 25px;">
					<div id="namebox" class="panel-heading">Danh Sách TOP</div>
					<div class="panel-body">
						<div class="news_list">
							<button class="levelTop btn btn-primary btn-block">Top Level</button>
							<button class="fightPowerTop btn btn-primary btn-block">Top lực chiến</button>
							<button class="onlineTop btn btn-primary btn-block">Top Online</button>
							
							<div class="article">
								
							</div>
						</div>
						<div class="pagin_news"></div>
					</div>
				</div>
				
				</div>
			</div>
		</div>