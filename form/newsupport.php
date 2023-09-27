<?php
session_start();
define("SNAPE_VN", true);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

include_once(dirname(__FILE__)."/../class/function.global.php");

include dirname(__FILE__).'/../recaptcha/autoload.php';

$return['type'] = -1;
$return['content'] = 'Lỗi không xác định';

$title = trim($_POST['txtTitle']);
$type = trim($_POST['txtType']);
$content = trim(nl2br($_POST['txtContent']));

if(!account::isLogin()) {
	$return['type'] = 1;
	$return['content'] = 'Chưa đăng nhập.';
}

if(!is_numeric($type) || $type <= 0 || strlen($title) < 3 || strlen($title) > 100 || strlen($content) < 10 || strlen($content) > 5000) {
	$return['type'] = 154;
	$return['content'] = 'Không đồng bộ với client.';
}

$recaptcha = new \ReCaptcha\ReCaptcha($_config['recaptcha']['secret']);

$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

if (!$resp->isSuccess()) {
	$return['type'] = -928;
	$return['content'] = 'Mã bảo vệ không chính xác.';
}

if($return['type'] == -1) {
	// submit new support
	$catInfo = loadSupportCategory($type);
	if($catInfo) {
		$qNewSp = sqlsrv_query($conn, "insert into Mem_Support (UserID, Type, Title, Content, Status, TimeCreate, IPCreate) VALUES (?, ?, ?, ?, ?, ?, ?)"
									, array($_SESSION['ss_user_id'], $type, $title, $content, 0, time(), account::getUserIP()));
		
		if($qNewSp) {
			sqlsrv_free_stmt($qNewSp);
			$return['type'] = 0;
			$return['content'] = 'Thành công';
		} else {
			$return['type'] = -928;
			$return['content'] = 'Lỗi gửi yêu cầu hỗ trợ mới.';
			//print_r(sqlsrv_errors());
		}
	} else {
		$return['type'] = -928;
		$return['content'] = 'Không tồn tại loại hỗ trợ này';
	}
	
}
echo json_encode($return);
sqlsrv_close($conn);
?>