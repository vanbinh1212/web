<?php
session_start();
define("SNAPE_VN", true);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

include_once(dirname(__FILE__)."/../class/function.global.php");


$account = new account();

$return['type'] = -1;
$return['content'] = 'Lỗi không xác định';

$password = trim($_POST['txtPassword']);
$newpassword = trim($_POST['txtNewPassword']);
$code = trim($_POST['txtCode']);

if(!$account->isLogin()) {
	$return['type'] = 1;
	$return['content'] = 'Chưa đăng nhập.';
}

if($password == $newpassword) {
	$return['type'] = 154;
	$return['content'] = 'Mật khẩu mới phải khác mật khẩu cũ.';
}

if(!vaildCaptcha($code)) {
	$return['type'] = -928;
	$return['content'] = 'Mã bảo vệ không chính xác.';
}

if($return['type'] == -1) {
	if(strlen($newpassword) < 6 || strlen($newpassword) > 100) {
		$return['type'] = 2;
		$return['content'] = 'Dữ liệu không hợp lệ.';
	} else {

		$password = strtoupper(md5($password));

		$newpassword = strtoupper(md5($newpassword));

		// check account can change password without old pass
		$userInfo = $account->getUserInfo($_SESSION['ss_user_id']);

		if($userInfo['Password'] == null || ($userInfo['Password'] == $password && $userInfo['Password'] != null)) {

			// can set new password
			$account->changePassword(0, $newpassword);

			$return['type'] = 0;
			$return['content'] = 'Thành công.';

		} else {
			// errror
			$return['type'] = 133;
			$return['content'] = 'Mật khẩu cũ không chính xác.';
		}
	}
}
echo json_encode($return);
sqlsrv_close($conn);
?>