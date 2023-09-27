<?php
session_start();

define("SNAPE_VN", true);

error_reporting(E_ALL ^ E_NOTICE);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once dirname(__FILE__).'/../../#config.php';

include_once dirname(__FILE__).'/../../class/class.account.php';

include dirname(__FILE__).'/../../class/function.global.php';

$id = $_POST['id'];

if(!is_numeric($id) || $id <= 0) die();

$result['type'] = -1;
$result['content'] = 'Lỗi không xác định';
// check login
if(!account::isLogin()) {
	$result['type'] = -165;
	$result['content'] = 'Vui lòng đăng nhập trước';
}

$serverInfo = loadserver($id);

if($serverInfo == null)
{
	$result['type'] = -165;
	
	$result['content'] = 'Không tồn tại máy chủ này';
	
} else {
	
	// check they geted or not
	$eventInfo = account::getEventInfo($_SESSION['ss_user_id'], 1, $id);
	
	if($eventInfo == false || $eventInfo['BooleanSuccess'] == false) {
		
		// create new
		$activeid = 0;
		switch($id) {
				
			case 1:
			$activeid = 3;
			break;
			
			case 2:
			$activeid = 3;
			break;
				
		}
		
		$conn_sv = connectTank($id);

		if($conn_sv == false) {
			
			$result['type'] = -165;
			
			$result['content'] = 'Máy chủ hiện không khả dụng.';
			
		} else {
			
			$giftcode = addGiftcode($activeid, 'FB');
			
			if($giftcode != false) {
				
				$qCreate = sqlsrv_query($conn, "INSERT INTO Mem_EventProcess (UserID, EventID, ServerID, Process1, BooleanSuccess) VALUES (?, ?, ?, ?, ?)", array($_SESSION['ss_user_id'], 1, $id, $giftcode, true));
				
				account::log($_SESSION['ss_user_id'], 'Share Facebook', 6, 'Giftcode Share Facebook cho máy chủ '.$serverInfo['ServerName'].' là: '.$giftcode, 0);
				
				$result['type'] = 0;
					
				$result['content'] = $giftcode;
				
			} else {
					
				$result['type'] = -165;
					
				$result['content'] = 'Không thể khởi tạo Giftcode';
					
			}
		}
		
	} else {
		
		$result['type'] = -165;
		$result['content'] = 'Bạn đã nhận Giftcode. Vui lòng kiểm tra lại lịch sử giao dịch.';
		
	}
	
}

echo json_encode($result);
?>