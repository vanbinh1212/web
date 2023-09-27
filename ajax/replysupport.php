<?php
session_start();
define("SNAPE_VN", true);

error_reporting(E_ALL ^ E_NOTICE);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once dirname(__FILE__).'/../#config.php';

include_once(dirname(__FILE__)."/../class/function.global.php");

include_once dirname(__FILE__).'/../class/class.account.php';

$result['type'] = -1;
$result['content'] = 'Lỗi không xác định';
// check login
if(!account::isLogin()) {
	$result['type'] = -165;
	$result['content'] = 'Vui lòng đăng nhập trước';
}

$id = $_POST['id'];

$text = $_POST['text'];

if(!is_numeric($id) || $id <= 0 || strlen($text) < 10 || strlen($text) > 5000) {
	$result['type'] = -165;
	$result['content'] = 'Lỗi hệ thống';
}

// load
if($result['type'] == -1) {
	
	// lấy dữ liệu support
	$qRead = sqlsrv_query($conn, "select Mem_Support.*, Mem_Account.Email, Mem_Account.Fullname from Mem_Support, Mem_Account where Mem_Support.ID = ? AND Mem_Support.UserID = ? AND Mem_Support.UserID = Mem_Account.UserID", array($id, $_SESSION['ss_user_id']), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
	
	if(sqlsrv_num_rows($qRead) > 0) {
		// read two floor
		$supInfo = sqlsrv_fetch_array($qRead, SQLSRV_FETCH_ASSOC);
		
		// kiem tra xem dc phep hoi am hay khong
		if($supInfo['Status'] == 1) {
			// create
			$qInsert = sqlsrv_query($conn, "insert into Mem_SupportData (TicketID, UserID, Content, TimeCreate, IPCreate) VALUES (?, ?, ?, ?, ?)", array($id, $_SESSION['ss_user_id'], $text, time(), account::getUserIP()));
			if($qInsert) {
				// update player reply
				sqlsrv_free_stmt($qInsert);
				$qUpdate = sqlsrv_query($conn, "update Mem_Support set Status = ? WHERE ID = ?", array(0, $id));
				sqlsrv_free_stmt($qUpdate);
				// return result
				$result['type'] = 0;
				$result['result'] = array("Picture" => $_config['page']['fullurl']."/images/avatar_user.jpg", "Name" => $_SESSION['ss_user_email'], "Time" => date("H:i:j d/m/Y"), "Content" => $text, "IsStaff" => false);
			} else {
				$result['type'] = -165;
				$result['content'] = 'Lỗi hồi âm. Liên hệ GM để được trợ giúp.';
			}
		} else {
			$result['type'] = -165;
			$result['content'] = 'Vui lòng đợi BQT trả lời mới được quyền hồi âm lại.';
		}
		
	} else {
		$result['type'] = -165;
		$result['content'] = 'Không tồn tại hỗ trợ này';
	}
	sqlsrv_free_stmt($qRead);
}

echo json_encode($result);
?>