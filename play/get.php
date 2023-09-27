<?php
session_start();
define("SNAPE_VN", true);
include_once dirname(__FILE__).'/../#config.php';
include dirname(__FILE__).'/../class/class.account.php';
include dirname(__FILE__).'/../class/function.global.php';

$Result = 0;
$ServerID = $_GET['svID'];
$UserName = $_GET['userName'];
$Password = $_GET['passWord'];
$link = "";
$conn_sv = connectTank($ServerID);
$checklogin = checkuserValid($UserName, md5($Password));

$serverInfo = loadserver($ServerID);

if($serverInfo == null)
	die('server is not avalible');

if(!$conn_sv) {
	$Result = 1; //ServerID khong ton tai
}

if($checklogin == true){
	$Result = 2; //Dang nhap thanh cong
} else {
	$Result = 3; //Dang nhap that bai
	echo(3);
}


if($Result == 2){
	$keyrand = rand(111111, 999999);
	$timeNow = time();
	$content = file_get_contents($serverInfo['LinkRequest']."CreateLogin.aspx?content=".$username."|".strtoupper($keyrand)."|".$timeNow."|".md5($username.strtoupper($keyrand).$timeNow.$_config['function']['key_request']));
	$link = $serverInfo['LinkFlash'] . "/Loading.swf?user=" . $UserName . "&key=" . $keyrand . "&v=104&rand=92386938&config=" . $serverInfo['LinkConfig'];
}
echo($link);
?>
