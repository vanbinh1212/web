<?php
session_start();
define("SNAPE_VN", true);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

$return['type'] = -1;
$return['content'] = 'Lỗi không xác định';

$email = trim($_POST['txtEmail']);
$password = trim($_POST['txtPassword']);

if($_SESSION['ss_user_id']) {
	$return['type'] = 1;
	$return['content'] = 'Bạn đã đăng nhập rồi.';
}


if($return['type'] == -1) {
	if(strlen($password) < 6 || strlen($password) > 100 || !preg_match("/^[a-zA-Z0-9\_\.]{4,40}$/", $email)) {
		$return['type'] = 2;
		$return['content'] = 'Dữ liệu không hợp lệ. ';
	} else {

		$email = strtolower($email);

		$password = strtoupper(md5($password));

		$account = new account(0);

		if($account->login($email, $password)) {
			$userid = $account->getUserInfoByEmail($email);
		$qCheck_Block = sqlsrv_query($conn, "select * from Mem_AccountBlock where UserID = '".$userid['UserID']."'", array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		if(sqlsrv_num_rows($qCheck_Block) > 0) {
         $qCheck_Block = sqlsrv_fetch_array($qCheck_Block, SQLSRV_FETCH_ASSOC);		
			$return['type'] = -1938;
			$return['content'] = $qCheck_Block['Messager'];
			} else {
			$return['type'] = 0;
			$return['content'] = 'Đăng nhập thành công!';			
			}

		} else {

			$return['type'] = -1938;
			$return['content'] = 'Tài khoản hoặc mật khẩu sai.';

		}
	}
}
echo json_encode($return);
sqlsrv_close($conn);
?>