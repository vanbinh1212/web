<?php
session_start();
define("SNAPE_VN", true);

include_once dirname(__FILE__).'/#config.php';

include dirname(__FILE__).'/class/function.global.php';

include dirname(__FILE__).'/class/class.account.php';

include dirname(__FILE__).'/class/class.oauth2.php';

$account = new account(0);

$oauth = new social_connect();

$typeConnect = $_GET['type'];

if($_GET['action'])
	$_SESSION['ss_action_oauth'] = $_GET['action'];

$actionConnect = $_SESSION['ss_action_oauth'];

if($_GET['return'])
	$_SESSION['ss_return_oauth'] = $_GET['return'];

$return = $_SESSION['ss_return_oauth'];

$scriptLoad = $_GET['script'];

$result = null;

$connect_name = 'Unknown';

$remote_url = null;

switch ($typeConnect) {
	case 'yahoo':
        // ket noi den yahoo
    	$connect_name = 'yahoo';
        break;

    case 'facebook':
        // ket noi den fb
    	$connect_name = 'facebook';
		$remote_url = 'http://'.$_SERVER['HTTP_HOST'].'/social/facebook/';
        break;

    case 'google':
        // ket noi den gg
    	$connect_name = 'google';
		$remote_url = 'http://'.$_SERVER['HTTP_HOST'].'/social/google/';
        break;
}

//$oauth->stateString = 'database';

$result = $oauth->open_connect($connect_name, $remote_url);

if($result != null) {
	
	if($result['type'] == 0) {

		switch ($actionConnect) {
			case 'connect':
				if($account->isLogin()) {
					// check can connect
					if($account->getUserInfoByField($_SESSION['ss_user_id'], "AllowSocialLogin") == true) {
						$canConnect = $account->connectSocial($_SESSION['ss_user_id'], $connect_name, $result['result']);

						if(!$canConnect)
							actionPopup(null, true, 'window.opener.SnapeClass.openModal("'.convertncr("Lỗi hệ thống").'", "'.convertncr("Tài khoản ".ucfirst($connect_name)." này đã liên kết với một tài khoản khác.").'", []);');
						else
							actionPopup(base64_decode($return), true, $scriptLoad);
					} else {
						actionPopup(null, true, 'window.opener.SnapeClass.openModal("'.convertncr("Lỗi hệ thống").'", "'.convertncr("Tài khoản này không cho phép liên kết mạng xã hội.").'", []);');
					}
				} else {
					actionPopup(null, true, 'window.opener.SnapeClass.openModal("'.convertncr("Lỗi hệ thống").'", "'.convertncr("Vui lòng đăng nhập để sử dụng tính năng này.").'", []);');
				}
				break;
			default:
				if(!$account->isLogin()) {
					$connect = $account->loginSocial($connect_name, $result['result']);
					if($connect) {
						actionPopup(base64_decode($return), true, $scriptLoad);
					} else {
						actionPopup(null, true, 'window.opener.SnapeClass.openModal("'.convertncr("Lỗi hệ thống").'", "'.convertncr("Kết nối tài khoản thất bại Hoặc Tài khoản bạn đang bị khóa.").'", []);');
					}
				} else {
					actionPopup(null, true, 'window.opener.SnapeClass.openModal("'.convertncr("Lỗi hệ thống").'", "'.convertncr("Bạn đã đăng nhập rồi. Không thể đăng ký mới.").'", []);');
				}
				break;
		}

		

	} else {
		actionPopup(null, true, 'window.opener.SnapeClass.openModal("'.convertncr("Lỗi hệ thống").'", "'.convertncr($result['content']).'", []);');
	}

} else {
	actionPopup(null, true, 'window.opener.SnapeClass.openModal("'.convertncr("Lỗi hệ thống").'", "'.convertncr("Hệ thống chưa hỗ trợ kết nối này.").'", []);');
}
?>