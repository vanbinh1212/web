<?php
session_start();
define("SNAPE_VN", true);

error_reporting(E_ALL ^ E_NOTICE);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once dirname(__FILE__).'/../#config.php';

include_once dirname(__FILE__).'/../class/class.account.php';

$result['type'] = -1;
$result['content'] = 'Lỗi không xác định';
// check login
if(!account::isLogin()) {
	$result['type'] = -165;
	$result['content'] = 'Vui lòng đăng nhập trước';
}

$id = $_POST['id'];

if(!is_numeric($id) || $id <= 0) {
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
		$result['type'] = 0;
		$result['title'] = $supInfo['Title'];
		$result['TypeSupport'] = $supInfo['Status'];
		switch($result['TypeSupport']) {
			case 0:
			$result['TypeSupportText'] = 'Vui lòng đợi quản lý trả lời hỗ trợ.';
			break;
			case 1:
			$result['TypeSupportText'] = 'Nhập nội dung hồi âm hỗ trợ...';
			break;
			default:
			$result['TypeSupportText'] = 'Hỗ trợ này đã đóng. Không thể hồi âm';
			break;
		}
		$result['result'] = array();
		// create first
		$result['result'][] = array("Picture" => $_config['page']['fullurl']."/images/".(($supInfo['UserID'] == $_SESSION['ss_user_id']) ? 'avatar_user.jpg' : 'avatar_staff.jpg'), "Name" => $supInfo['Email'], "Time" => date("H:i:j d/m/Y", $supInfo['TimeCreate']), "Content" => $supInfo['Content'], "IsStaff" => (($supInfo['UserID'] == $_SESSION['ss_user_id']) ? false : true));
		
		// duyệt các hỗ trợ khác
		$qReadOrder = sqlsrv_query($conn, "select Mem_SupportData.*, Mem_Account.Email, Mem_Account.Fullname from Mem_SupportData, Mem_Account where Mem_SupportData.TicketID = ? AND Mem_SupportData.UserID = Mem_Account.UserID ORDER by Mem_SupportData.ID ASC", array($id), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		if(sqlsrv_num_rows($qReadOrder) > 0) {
			while($supOrderInfo = sqlsrv_fetch_array($qReadOrder, SQLSRV_FETCH_ASSOC)) {
				$result['result'][] = array("Picture" => $_config['page']['fullurl']."/images/".(($supOrderInfo['UserID'] == $_SESSION['ss_user_id']) ? 'avatar_user.jpg' : 'avatar_staff.jpg'), "Name" => (($supOrderInfo['Email'] == $_SESSION['ss_user_email']) ? $supOrderInfo['Email'] : $supOrderInfo['Fullname']), "Time" => date("H:i:j d/m/Y", $supOrderInfo['TimeCreate']), "Content" => $supOrderInfo['Content'], "IsStaff" => (($supOrderInfo['UserID'] == $_SESSION['ss_user_id']) ? false : true));
			}
		}
		sqlsrv_free_stmt($qReadOrder);
	} else {
		$result['type'] = -165;
		$result['content'] = 'Không tồn tại hỗ trợ này';
	}
	sqlsrv_free_stmt($qRead);
}

echo json_encode($result);
?>