<?php
session_start();
define("SNAPE_VN", true);

include_once dirname(__FILE__).'/#config.php';

include dirname(__FILE__).'/class/function.remotepage.php';

include dirname(__FILE__).'/class/function.global.php';

include dirname(__FILE__).'/class/class.account.php';

include dirname(__FILE__).'/class/class.item.php';

$pageRemote = remotePage(str_replace("/", "", $_GET['page']));

$account = new account(0);

if($account->isLogin())
    $userInfo = $account->getUserInfo($_SESSION['ss_user_id']);

if($account->getUserIP() == '171.246.202.163' || $account->getUserIP() == '27.79.255.223') die('motherfucker :)');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
	<link rel="shortcut icon" href="http://img.zing.vn/products/gn/favicon.ico"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Gunny phiên bản cày cuốc xu và huân chương mua đá cường hóa hên xui!!!">
	<meta name="keywords" content="gunny hoi uc, gunny cuong hoa hên xui, gunny 3.0, gunny 3.4, gunny 3.6, gunny 2012, gunny huan chuong ,gunny free, ddtank, ddtank private, gunny private, gunny lậu, gunny private, gunny miễn phí, gunny đầy đủ tính năng, gunny phó bản chuẩn zing, gunny có doanh chiến, gunny có guild chiến, gunny có tranh bá chí tôn, gunny có pet tôn ngộ không, gunny miễn phí pet tôn ngộ không, gunny, gunny lau, gunny lau moi nhat, gunny phien ban moi, gunny moi nhat, gunny dai chien 7 thanh pho, gunny lau full xu, gunnyfullxu, gunny lau co pet ton ngo khong, gunny lau mien phi pet ton ngo khong, pet ton ngo khong gunny, free pet ton ngo khong, gunny full xu, gunny cay quoc, gunny day du tinh nang, gunny full pho ban, gunny pho ban chuan, gunny pho ban do ngon">
    <meta name="author" content="DDT Coder">
	<meta property="og:title" content="<?= $_config['page']['title'] ?> | Trang Game Private 2021.">
	<meta property="og:image" content="http://ddt.wan.com/Public/ad_gug/20210526/2021052624771.png"/>
	<meta property="og:description" content="Gunny phiên bản làm nhiệm vụ, chiến đấu tự do, chiến đấu guild, đi phó bản, không webshop, cày cuốc xu và huân chương mua đá cường hóa hên xui!!!. Trải nghiệm ngay bây giờ.">
    <title><?=(($pageRemote['title'] != null) ? $pageRemote['title'].' - ' : '').$_config['page']['title']?></title>

    <!-- Bootstrap Core CSS -->
	<link href="<?=$_config['page']['fullurl']?>/css/style.css" rel="stylesheet">
    <link href="<?=$_config['page']['fullurl']?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?=$_config['page']['fullurl']?>/css/font-awesome.css" rel="stylesheet">
	<link href="<?=$_config['page']['fullurl']?>/css/bootstrap-social.css" rel="stylesheet" >
	<link rel="stylesheet" href="<?=$_config['page']['fullurl']?>/css/bootstrap-select.min.css" >
	<link rel="stylesheet" href="<?=$_config['page']['fullurl']?>/css/item_reader.css" >
	<link rel="stylesheet" href="<?=$_config['page']['fullurl']?>/css/popup_itemreader.css" >
	<link href="<?=$_config['page']['fullurl']?>/css/anno.css" rel="stylesheet">
	
	<!-- jQuery Version 1.11.1 -->
    <script src="<?=$_config['page']['fullurl']?>/js/jquery-1.11.3.min.js"></script>
	<script src='<?=$_config['page']['fullurl']?>/js/jquery-ui.min.js'></script>
	<script src='<?=$_config['page']['fullurl']?>/js/jquery.number.min.js'></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=$_config['page']['fullurl']?>/js/bootstrap.min.js"></script>
	<script src="<?=$_config['page']['fullurl']?>/js/jquery.validate.min.js"></script>
    <script src="<?=$_config['page']['fullurl']?>/js/jquery.twbsPagination.min.js"></script>
    <script type="text/javascript" src="<?=$_config['page']['fullurl']?>/js/pwstrength.js"></script>
	<script src="<?=$_config['page']['fullurl']?>/js/bootstrap-select.min.js"></script>
	<script src="<?=$_config['page']['fullurl']?>/js/fuckadblock.js"></script>
	<script src="<?=$_config['page']['fullurl']?>/js/anno.js"></script>
	<script src="<?=$_config['page']['fullurl']?>/js/guild.js"></script>
	<script type="text/javascript">
	/*function adBlockDetected() {
		canMove = true;
		window.location = '<?=$_config['page']['fullurl']?>/adblock.html';
	}
	function adBlockNotDetected() {
		//alert("Good man ^^!");
	}
		
	if(typeof fuckAdBlock === 'undefined') {
		adBlockDetected();
	} else {
		fuckAdBlock.setOption({ debug: true });
		fuckAdBlock.onDetected(adBlockDetected).onNotDetected(adBlockNotDetected);
	}*/
	</script>
</head>

<body>
	<script>
	
	// khai bao ham global
	var max_loading_gif_count = <?=$_config['effect']['loading_max_count']?>;
	
	var full_url = "<?=$_config['page']['fullurl']?>";
	
	var isAdsDisplayed = true;

    </script>
	
	<script src="<?=$_config['page']['fullurl']?>/js/script_global.js"></script>

    <script type="text/javascript">

    SnapeClass.encryptCurrentURL = '<?=base64currenturl()?>';

    </script>
	
	<div id="fb-root"></div>
	<script>
			window.fbAsyncInit = function() {
				FB.init({
				  appId      : '<?=$_config['social']['facebook']['id']?>',
				  xfbml      : true,
				  version    : 'v2.5'
				});
			  };
		  
			(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.5&appId=<?=$_config['social']['facebook']['id']?>";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

    <div id="modalSnape" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Thông báo</h4>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng lại</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	
	<div id="div_loading" class="loading_backgroup">
		<div class="loading_fullsceen">
			<img src="<?=$_config['page']['fullurl']?>/images/please-wait.gif" /><br/>
			<img id="image_loading_big" src="<?=$_config['page']['fullurl']?>/images/big_loading_<?=rand(0, $_config['effect']['loading_max_count'])?>.gif" width="200px" />
		</div>
	</div>
    <!-- Navigation -->
    <nav class="navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=$_config['page']['fullurl']?>">
					<img height="50" src="<?=$_config['page']['fullurl']?>/images/logo.png" />
				</a>

            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="<?=$_config['page']['fullurl']?>/chon-may-chu/"><img height="40" width="55" src="<?=$_config['page']['fullurl']?>/images/choigame.png" /></a>
                    </li>
                    <li>
                        <a href="<?=$_config['page']['fullurl']?>/tai-khoan/nap-momo/"><img height="40" width="55" src="<?=$_config['page']['fullurl']?>/images/napthe.png" /></a>
                    </li>
                    <li>
                        <a href="<?=$_config['Contact']['FB']?>"><img height="50" width="130" src="<?=$_config['page']['fullurl']?>/images/FANPAGE.png" /></a>
                    </li>
					<li>
                        <a href="<?=$_config['Contact']['FB1']?>"><img height="50" width="450" src="<?=$_config['page']['fullurl']?>/images/group.png" /></a>
                    </li>
										
                </ul>
				
				<ul class="nav navbar-nav navbar-right">
                    <?php
                    if($account->isLogin()) {
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Xin chào <?=$account->getUsername()?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="divider"></li>
                            <li><a>CoinS : <b><?=number_format($userInfo['Money'])?></a></b></li>
                            <li><a href="<?=$_config['page']['fullurl']?>/tai-khoan/nap-the/">Nạp thẻ</a></li>
							<li><a href="<?=$_config['page']['fullurl']?>/tai-khoan/chuyen-xu/">Chuyển xu</a></li>
                            <li class="divider"></li>
                            <li><a href="<?=$_config['page']['fullurl']?>/tai-khoan/">Quản lý tài khoản</a></li>
							<li><a href="<?=$_config['page']['fullurl']?>/tai-khoan/lich-su-giao-dich/">Lịch sử giao dịch</a></li>
                            <li class="divider"></li>
                            <li><a href="javascript:;" onclick="SnapeClass.logout()">Thoát</a></li>
                        </ul>
                    </li>
                    <?php
                    } else {
                    ?>
                    <li><a href="<?=$_config['page']['fullurl']?>/dang-ky/"><span class="glyphicon glyphicon-user"></span> Đăng ký</a></li>
                    <li><a href="<?=$_config['page']['fullurl']?>/dang-nhap/"><span class="glyphicon glyphicon-log-in"></span> Đăng nhập</a></li>
                    <? } ?>
				</ul>

            </div>
			
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	<?php if(!$_GET['iframe']) { ?>
    <div class="container">
</div>
	<?php } ?>
    <!-- Page Content -->
    <div class="container">

        <?php
        // check
        if($account->isLogin() && !$_SESSION['ss_notice_update_password'] && $userInfo['Password'] == null) {
            echo '<script>SnapeClass.openModal("Cảnh báo", "<font color=\'red\'>Tài khoản của bạn chưa được cập nhật mật khẩu. Vui lòng cập nhật để tránh bị hack tài khoản.</font>", [{"Name": "Cập nhật mật khẩu", "Link": "'.$_config['page']['fullurl'].'/tai-khoan/doi-mat-khau/"}])</script>';
            $_SESSION['ss_notice_update_password'] = true;
        }
		include_once 'module/'.$pageRemote['content'].'.php';
		?>
        <!-- /.row -->

    </div>
    <!-- /.container -->
    <?php if(!$_GET['iframe']) { ?>
	<footer>
    <div class="footer">
		<div class="container">
			<div class="foo-intro col-sm-10">
				<h3>GUNNY PHIÊN BẢN HỒI ỨC | <?=(($pageRemote['title'] != null) ? $pageRemote['title'].' - ' : '').$_config['page']['title']?></h3>
				<p>Group Zalo: <b>https://zalo.me/g/hdpeza017</b> | Zalo Hỗ Trợ : <b>0833060331</b></p>
			</div>
		</div>
	</div></footer>
    <?php } ?>
	<script type="text/javascript">
	  (function() {
		window._pa = window._pa || {};
		// _pa.orderId = "myOrderId"; // OPTIONAL: attach unique conversion identifier to conversions
		// _pa.revenue = "19.99"; // OPTIONAL: attach dynamic purchase values to conversions
		// _pa.productId = "myProductId"; // OPTIONAL: Include product ID for use with dynamic ads
		var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
		pa.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + "//tag.marinsm.com/serve/58d09d48abaf5eef8a000016.js";
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);
	  })();
	</script>
</body>

</html>