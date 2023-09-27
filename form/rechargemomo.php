<?php
session_start();
define("SNAPE_VN", true);

ignore_user_abort(true);
set_time_limit(0);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

include_once(dirname(__FILE__)."/../class/function.global.php");

//exit();

$return['type'] = -1;
$return['content'] = 'Lỗi không xác định';
$startTimeStamp = 1628275206000;
$tranId = trim($_POST['tranId']);
$code = trim($_POST['txtCode']);
$serverid = 1001; // dat mac dinh la so nao di sau nay thich them giftcode gi do phai sua lai
$isDebug = false;

if(!$_SESSION['ss_user_id']) {
	$return['type'] = 1;
	$return['content'] = 'Vui lòng đăng nhập để sử dụng tính năng này.';
}

if(!vaildCaptcha($code)) {
	$return['type'] = -928;
	$return['content'] = 'Mã bảo vệ không chính xác.';
}

if(!is_numeric($tranId) || $tranId <= 0 || !is_numeric($serverid) || $serverid <= 0) {
		
	$return['type'] = 2;
	
	$return['content'] = 'Dữ liệu không hợp lệ. ';
	
}
$account = new account();

if($momoInfo = $account->findMomoByTransId($tranId)) {
	$return['type'] = -69;
	$return['content'] = 'Mã giao dịch đã được nạp vào '.date('H:i:s m/d/Y', $momoInfo['TimeCreate']).' bằng tài khoản : '.$momoInfo['Username'].'.';
}

if($return['type'] == -1) {
	$url = 'https://luthebao.com/api/apimomo/get?key=3824b77230ce26b3891aceb66fd5f8de1ddbd4024d8aa9b0fd0f59ea8d5a044a&days=5';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	curl_setopt($ch, CURLOPT_REFERER, $actual_link);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$result = curl_exec($ch);
	curl_close($ch);
	$jsonData = json_decode($result, true);
	if(!$jsonData['stt']) {
		$return['type'] = -332;
	
		$return['content'] = 'Hệ thống đang bảo trì, vui lòng thử sau ít phút!';
	} else {
		if($jsonData['msg'] == 'Lỗi 106: Request quá nhanh.'){
			$return['type'] = -332;
			$return['content'] = 'Hệ thống tải quá nhiều yêu cầu. Vui lòng thử lại sau vài giây!';
		} else 
		if(count($jsonData['data']['tranList']) > 0) {
			$tranList = $jsonData['data']['tranList'];
			$foundTransaction = false;
			foreach($tranList as $transaction){
				if($transaction['tranId'] == $tranId && $transaction['io'] == 1) {//tìm thấy giao dịch
					$foundTransaction = true;	
					if($transaction['finishTime'] < $startTimeStamp && !$isDebug) {
						$return['type'] = -1958;
						$return['content'] = 'Thời gian giao dịch không còn khả dụng!';
					} else if($transaction['comment'] == $_SESSION['ss_user_email'] || $isDebug) { //tên đăng nhập khớp với nội dung giao dịch
						$payAmount = $transaction['amount'];
						$coinhave = intval(floor($payAmount * ($account->getAmountReceived(10000)/ 10000)));// lấy tỉ lệ từ ConfigCharge
						
						$canAdd = $account->addCoin($_SESSION['ss_user_id'], $coinhave, true);
						$account->logMomo($transaction['tranId'], $_SESSION['ss_user_id'], $_SESSION['ss_user_email'], $payAmount);
						if($canAdd) {
							$account->log($_SESSION['ss_user_id'], 'Nạp thẻ', 2, 'Nạp '.number_format($payAmount).'VNĐ Momo', $coinhave);
							
							$account->addNumberField($_SESSION['ss_user_id'], "VIPExp", $payAmount / 1000);
							
							$userInfo = $account->getUserInfo($_SESSION['ss_user_id']);
							
							$account->updateInfo($_SESSION['ss_user_id'], "VIPLevel", getVIPLevel($userInfo['VIPExp']));
							
							$return['type'] = 0;
							$return['content'] = 'Nạp '.number_format($payAmount).' VNĐ Momo Thành công. Bạn nhận được '.number_format($coinhave).'Coin.';
						} else {
							$return['type'] = -1958;
							$return['content'] = 'LỖI. Liên hệ GM để được trợ giúp.';
						}
						break;
					} else {
						$return['type'] = -666;
						$return['content'] = 'Nội dung chuyển tiền không khớp tên tài khoản!';
					}
				}
			}
			if(!$foundTransaction) {
				$return['type'] = -113;
				$return['content'] = 'Không tìm thấy giao dịch!';
			}
		} else {
			$return['type'] = -113;
			$return['content'] = 'Không tìm thấy giao dịch!';
		}
	}
}
echo json_encode($return);
sqlsrv_close($conn);
?>