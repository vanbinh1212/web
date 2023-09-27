<?php
session_start();
define("SNAPE_VN", true);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

include_once(dirname(__FILE__)."/../class/function.global.php");

//include dirname(__FILE__).'/../recaptcha/autoload.php';


$return['type'] = -1;
$return['content'] = 'Lỗi không xác định';

$email = trim($_POST['txtEmail']);
$password = trim($_POST['txtPassword']);
$phone = trim($_POST['txtPhone']);
$fullname = trim($_POST['txtFullName']);
$code = trim($_POST['txtCode']);

if($_SESSION['ss_user_id']) {
	$return['type'] = 1;
	$return['content'] = 'Bạn đã đăng nhập rồi.';
}

//$recaptcha = new \ReCaptcha\ReCaptcha($_config['recaptcha']['secret']);

//$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

/*if (!$resp->isSuccess()) {
	$return['type'] = -928;
	$return['content'] = 'Mã bảo vệ không chính xác.';
}*/
function validate_mobile($mobile)
{
	return preg_match('/^[0-9]{10}+$/', $mobile);
}
if(!vaildCaptcha($code)) {
	$return['type'] = -928;
	$return['content'] = 'Mã bảo vệ không chính xác.';
}

if(!validate_mobile($phone)){
	$return['type'] = -928;
	$return['content'] = 'Số điện thoại không khả dụng!';
}
if($return['type'] == -1) {
	if(strlen($password) < 6 || strlen($password) > 100 || !preg_match("/^[a-zA-Z0-9\_\.]{4,40}$/", $email)) {
		$return['type'] = 2;
		$return['content'] = 'Dữ liệu không hợp lệ. ';
	} else {

		$email = strtolower($email);

		$password = strtoupper(md5($password));

		$account = new account(0);

		if($account->create($email, $password, $phone, $fullname) > 0) {

			$account->login($email, $password);

			$return['type'] = 0;
			$return['content'] = 'Đăng ký thành công!';

		} else {

			// can't create account
			$return['type'] = -1938;
			$return['content'] = 'Không thể khởi tạo tài khoản. Vui lòng kiểm tra lại.';

		}
	}
}
echo json_encode($return);
sqlsrv_close($conn);
?>