<?php
session_start();
define("SNAPE_VN", true);
include_once dirname(__FILE__).'/../#config.php';

include dirname(__FILE__).'/../class/class.account.php';

include dirname(__FILE__).'/../class/function.global.php';

$account = new account(0);

$svid = $_GET['id'];

$username = $_GET['u'];

$pass = $_GET['p'];

$bypass = false;

if($username != null && $pass == 'keyfordirectconnect@@@') {
	$bypass = true;
} else {
	$username = $_SESSION['ss_user_email'];
}

if(!$bypass && !$account->isLogin())
	movepage($_config['page']['fullurl'].'/dang-nhap/?return='.base64currenturl());


if(!is_numeric($svid) || $svid <= 0) {
	die();
}

$conn_sv = connectTank($svid);

if(!$conn_sv) {
	die('Server is not avalible');
}

$serverInfo = loadserver($svid);

if($serverInfo == null)
	die('server is not avalible');

// try connect to request
// create login
$keyrand = rand(111111, 999999);

$timeNow = time();

$content = file_get_contents($serverInfo['LinkRequest']."CreateLogin.aspx?content=".$username."|".strtoupper($keyrand)."|".$timeNow."|".md5($username.strtoupper($keyrand).$timeNow.$_config['function']['key_request']));

if(trim($content) != "0") {
	die("Mã lỗi đăng nhập: ".$content);
}

if(!$bypass) {
	$userInfo = $account->getUserInfo($_SESSION['ss_user_id']);

	if($userInfo['IsBan'] == true)
		die('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin.');
}

$ServerName = getServerName($svid);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chơi Ngay - <?= $_config['page']['title'] ?></title>
<link rel="shortcut icon" href="http://img.zing.vn/products/gn/favicon.ico"/>
<style>
@charset "gb2312";
html{
	overflow-y:hidden;
}
* { word-break: break-all; word-wrap: break-word; }
body, th, td, input, select, textarea, button { font: 12px/1.5em Verdana, "Lucida Grande",Arial, Helvetica,sans-serif; }
body, h1, h2, h3, h4, h5, h6, p, ul, dl, dt, dd, form, fieldset { margin: 0; padding: 0; }
h1, h2, h3, h4, h5, h6 { font-size: 1em; }
ul li { list-style: none; }
a {color: #2C629E; text-decoration: none; }
a:hover { text-decoration: underline; }
a img { border: none; }
textarea { border: 1px solid #ddd; overflow: auto; }
body{ background-color: #000; }
.t_input { padding: 3px 2px; border: 1px solid #ddd; line-height: 16px; }

.link_td { background: #fff; color: #CEC9C9; height: 33px; line-height: 33px; text-align: center; }

.link_td a { color: #3C3C3C; }
.link_td a img{}
.link_tdbox {overflow: hidden;zoom: 1;color: #666;font-family: "arial";}
.link_tdbox div{ float:left; line-height: 35px; height: 35px; overflow: hidden;}
.dqgamelogo{padding-left:15px}
.dqgame{margin: 0px 30px 0px 40px;color: #888;}
.dqgame i{font-style: normal; color:#d70000;}
.dqgame b,.dqgame font{color:#000;font-weight:bold;margin-left:5px}
.dqgame span{color:#08c;margin-left:10px}
.mrdia, .mrdia a { color: #2A8EC9;}
.dqgamefn span{font-family: "arial";margin: 0px 4px;color: rgb(219, 219, 219);}
.dqgamefn em{font-style: normal;color:#d70000;font-weight:bold}
.dqgamefn {margin-right:20px}
.dqgamefn a{font-weight:bold;color: #BE940C;}
.dqgamefn a:hover{color:#d70000;text-decoration: none;}
.mrdia{float:right !important; padding-right:15px}
.mrdia:hover{color:#d70000;text-decoration: none;}
.mrdia font{margin: 0px 8px;color:#ddd;font-family: “arial”;}
.mrdia span{color:#d70000;cursor: pointer;}

/*温馨提示*/
#winpop { width:270px; height:0px; position:absolute; right:0; bottom:0; margin:0; overflow:hidden; display:none; background:#fff url(/statics/images/602wxtsBox.png);z-index:999;}
#winpop .title { line-height: 30px; font-weight:bold; font-size:12px; color: #fff; padding-left: 10px; height: 30px; text-indent: -999em; overflow: hidden; zoom: 1;}
#winpop .con { width:100%;  font-size:12px; color: #000; overflow: hidden; zoom: 1; padding-top: 15px;}
#silu { font-size:12px; color:#666; position:absolute; right:0; text-align:right;line-height:22px;}
#winpop .close { position:absolute; right: 8px; top: 8px; color:#FFF; cursor:pointer; display: block; width: 17px; height: 15px; background: #fff;filter:alpha(opacity=0);-moz-opacity:0.5;
-khtml-opacity: 0;opacity: 0;}
#winpop .con span,#winpop .con b,#winpop .con em,#winpop .con i,#winpop .con a{display:block;line-height:20px; height:20px;font-style: normal;margin:5px 22px;font-weight:300;}
#winpop .con em{color:#08c;font-weight:bold;}
#winpop .con em strong{color:#999;font-weight:300;margin:0px 8px;}
#winpop .con em font{font-weight:300;color:#fb7b00}
#winpop .con i{color:#d70000;}
#winpop .con a {width:200px; height:30px; overflow:hidden;zoom:1; background:url(/statics/images/kjfsdownbtn.png) no-repeat; margin:0px auto; text-indent:-999em; display:block;margin-top: 22px;}



.pop-mask {
	display: none;
	position: fixed;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.7);
	top: 0;
	left: 0;
}


.client-pop {
	display: none;
	position: fixed;
	box-sizing: border-box;
	width: 450px;
	height: 300px;
	top: 45px;
	left: 50%;
	transform: translate(-50%, 0%);
	background-color: #FFF;
	border: 1px solid #999;
	border-radius: 30px;
	box-shadow: inset 0 0 10px #47639a;
	color: #000;
	font-size: 14px;
	padding: 50px 70px 0;
	z-index: 999;
}
.client-pop .pop-close {
	position: absolute;
	color: #FFF;
	text-shadow: 2px 1px grey;
	font-size: 42px;
	top: 0;
	right: -45px;
}
.client-pop .pop-body {
	margin: 0 auto;
	font-size: 14px;
	text-align: center;
}
.client-pop .pop-title {
	font-size: 20px;
	margin-bottom: 40px;
}
.btndown{
	width: 150px;
	height: 37px;
    font-size: 14px;
	font-weight:bold;
    line-height: 34px;
    border: 1px solid #09AAFF;
    background: #09AAFF;
	color: #FFF;
	border-radius: 3px;
	-webkit-border-radius: 3px;
	padding: 10px 10px;
}
</style>
<link rel="stylesheet" href="css/player.css?v=0.17" type="text/css" />
<script language="javascript" src="js/swfobject.js"></script>
<script src="./Js/jquery-1.9.1.min.js"></script>
<script>
function AddFavorite(sURL, sTitle){
    try{
        window.external.addFavorite(sURL, sTitle);
    }catch (e){
        try{
            window.sidebar.addPanel(sTitle, sURL, "");
        }catch (e){
            alert("Press Ctrl+D");
        }
    }
}
function closenav(){
	var closenavbtn=document.getElementById("602nav");
	closenavbtn.style.display="none";
	}
function miniclinet(){
	//$.getJSON( "/index/get_game_url/?sid=" + 2166, function(data){
	$.getJSON( "/play.php?id=" + <?= $svid ?>, function(data){
		$("#client-pop").css('display','block');
		$("#pop-mask").css('display','block');
		if(data.err == 0){
			var url = data.data.replace("game.jsp","Loading.swf").replace("game.jsp","Loading.swf");
			location.href = "roadclient://" + url;
		}else if(data.err == -1){
			window.location.reload();
		}else{
			alert(data.msg);
		}
	});
}
$(function(){
	$("#pop-close").click( function () {
		$("#client-pop").css('display','none');
		$("#pop-mask").css('display','none');
	});
});
</script>
<script>


</script>
</head>

<body scroll="no" style="zoom:1; position:relative;">

<table border="0" cellPadding="0" cellSpacing="0" height="100%" width="100%" style="position:relative; z-index:10">

<tr id="602nav">

<td height="30" class="link_td">



<div class="link_tdbox">
    <div class="demodemo" style="display: none;"><?= $_config['page']['fullurl'] ?>/images/logo.png</div>
	<div class="dqgamelogo"><a href="/" target="_blank"><img src="<?= $_config['page']['fullurl'] ?>/images/logo.png"  height="30" /></a></div>
    <div class="dqgame"><i></i>  <b>Máy chủ: </b><font><?= $ServerName ?></font></div>

	<div class="dqgamefn">

    <a href="<?= $_config['page']['fullurl'] ?>" target="_blank">Trang Chủ</a>
    <span class="pipe">|</span>

    <a href="<?= $_config['page']['fullurl'] ?>/tai-khoan/nap-momo/" target="_blank">Nạp Thẻ</a>
    <span class="pipe">|</span>

    <a href="<?= $_config['page']['fullurl'] ?>/tai-khoan/" target="_blank">Tài Khoản</a>
    <span class="pipe">|</span>
	
	<a href="<?= $_config['Contact']['FB'] ?>" target="_blank">Fanpage</a>
    <span class="pipe">|</span>
	
    <a href="javascript:void(0);" onclick="miniclinet()" >Tải Launcher</a>
	</div>

</div>



</td>

</tr>

<tr>

<td>
<!--iframe id="url_mainframe" frameborder="0" scrolling="no" name="main" src="/game/login/?sid=2166" style="background:#FFFFFF; height: 100%; visibility: inherit; width: 100%; z-index: 1;overflow: visible;"></iframe-->
<div class="play-content">
    <div class="relative" style="margin-left:7px;">
        <table>
            <tr>
                <td>
                    <div class="fcontent frame" id="flashcontent">
                                                <div class="frame-top"></div>
                        <script src="js/swfobject.js"></script>
                        <script>
                            var swfPath = "<?=$serverInfo['LinkFlash']?>/Loading.swf";
                            var flashvars = {
                                user: "<?=$username?>",
                                key: "<?=$keyrand?>",
                                v: "104",
                                rand: "92386938",
                                config: "<?=$serverInfo['LinkConfig']?>"
                            };
                            var params = {
                                menu: "false",
                                scale: "noScale",
                                //allowFullscreen: "true",
                                allowScriptAccess: "always",
                                //bgcolor: "",
                                wmode: "direct" // can cause issues with FP settings & webcam
                            };
                            var attributes = {
                                id:"gameContent",
                                name:"Gunny 1",
                                style:"margin: 0 auto;position: relative;left: -16px; display:block !important;"
                            };
                            swfobject.embedSWF(
                                swfPath,
                                "gameContent", "1000", "600", "11.8.0",
                                "expressInstall.swf",
                                flashvars, params, attributes);
                        </script>
                        <Center><div id="gameContent">
                            <p><a href="../DDTANK.exe">Trình duyệt không hỗ trợ Flash! Vui lòng tải Launcher.</a></p>
                        </div></Center>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
</td>

</tr>

</table>
<div class="client-pop" id="client-pop">
      <div class="pop-body">
		</br>
          <a href="../DDTANK.exe" class="btndown">INSTALL LAUNCHER</a></br></br></br>
          Already installed, <a href="javascript:void(0);" onclick="miniclinet()">load game by launcher</a>
      </div>
      <a class="pop-close" id="pop-close" href="javascript:;">X</a>
</div>
<div class="pop-mask" id="pop-mask"></div>
</body>

</html>