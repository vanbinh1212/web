<?php
session_start();
define("SNAPE_VN", true);

// Show error
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//end show

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

include_once(dirname(__FILE__)."/../class/function.global.php");

include_once(dirname(__FILE__)."/../class/function.soap.php");

//include dirname(__FILE__).'/../recaptcha/autoload.php';

$account = new account();

$return['type'] = -1;
$return['content'] = 'Lỗi không xác định';

$coin = trim($_POST['txtCoin']);
$serverid = trim($_POST['txtServer']);
$code = trim($_POST['txtCode']);

if(!$account->isLogin()) {
	$return['type'] = 1;
	$return['content'] = 'Chưa đăng nhập.';
}

if(!is_numeric($coin) || $coin < 1000 || $coin > 10000000 || !is_numeric($serverid) || $serverid <= 0) {
	$return['type'] = 154;
	$return['content'] = 'Error verify params.';
}

if(!vaildCaptcha($code)) {
	$return['type'] = -928;
	$return['content'] = 'Mã bảo vệ không chính xác.';
}


if($serverid == 3) {
	$return['type'] = -928;
	$return['content'] = 'Máy chủ này không hỗ trợ chuyển Xu vào game.';
}

if($return['type'] == -1) {
	
	$conn_sv = connectTank($serverid);
	
	if(!$conn_sv) {
		$return['type'] = 2;
		$return['content'] = 'Máy chủ bảo trì hoặc không thể kết nối.';
	} else {
		//$result = $account->chargeMoney($_SESSION['ss_user_email'], 100);
		$serverInfo = loadserver($serverid);
		// check money
		$userInfo = $account->getUserInfo($_SESSION['ss_user_id']);
		
		if($coin <= $userInfo['Money']) {
			// du tien
			// kiem tra xem co nhan vat hay khong
			$playerInfo = $account->getPlayerInfo($_SESSION['ss_user_email']);
			
			if($playerInfo != false) {
				// if($playerInfo['State'] == 1) {
					// co nhan vat => remove money
					$removeMoney = $account->removeCoin(0, $coin);
					if($removeMoney) {
						
						$xuhave = $coin * $_config['config_xu'];
						
						$account->log($_SESSION['ss_user_id'], 'Chuyển Xu', 3, 'Chuyển '.number_format($xuhave).' Xu vào máy chủ '.$serverInfo['ServerName'], -$coin);
						
						// remove money thanh cong => chuyen xu
						$result = $account->chargeMoney($playerInfo, $xuhave);
						//die('aaaa:'.$val);
						if($result != false) {
							// notice
							if($playerInfo['State'] == 1) {
								$noticeResult = soapChargeMoney($serverid, $playerInfo['UserID'], $result);
							}
							$return['type'] = 0;
							$return['money'] = $xuhave;
							// $return['noticeResult'] = $noticeResult;
							$return['servername'] = $serverInfo['ServerName'];
							$return['content'] = 'Success.';
						} else {
							$return['type'] = -1010;
							$return['content'] = 'Lỗi hệ thống. Liên hệ GM để được hỗ trợ. CRE';
						}
					} else {
						$return['type'] = -1010;
						$return['content'] = 'Lỗi hệ thống. Liên hệ GM để được hỗ trợ.';
					}
				// } else {
					// $return['type'] = -1010;
					// $return['content'] = 'Vui lòng vào game rồi chuyển để tránh mất mát';
				// }
				
			} else {
				$return['type'] = -1010;
				$return['content'] = 'Vui lòng tạo nhân vật để sử dụng dịch vụ..';
			}
		} else {
			$return['type'] = -1010;
			$return['content'] = 'Số dư không đủ. Không thể thực hiện giao dịch.';
		}
	}
	sqlsrv_close($conn_sv);
}
echo json_encode($return);
sqlsrv_close($conn);
?>