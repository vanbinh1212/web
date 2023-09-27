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

$type = trim($_POST['txtType']);
$serial = trim($_POST['txtSerial']);
$passcard = trim($_POST['txtPasscard']);
$code = trim($_POST['txtCode']);
$serverid = 1001; // dat mac dinh la so nao di sau nay thich them giftcode gi do phai sua lai

if(!$_SESSION['ss_user_id']) {
	$return['type'] = 1;
	$return['content'] = 'Vui lòng đăng nhập để sử dụng tính năng này.';
}

if(!vaildCaptcha($code)) {
	$return['type'] = -928;
	$return['content'] = 'Mã bảo vệ không chính xác.';
}


if($return['type'] == -1) {
	if(!is_numeric($type) || $type <= 0 || strlen($serial) < 5 || strlen($passcard) < 5 || !is_numeric($serverid) || $serverid <= 0) {
		
		$return['type'] = 2;
		
		$return['content'] = 'Dữ liệu không hợp lệ. ';
		
	} else {
		
		$cardName = null;
		$TxtCard = '';
		$Telco = null;
		
		switch($type) {
            case 2:
				$cardName = 'Viettel';
				$TxtCard = 'VTT';
				$Telco = 'VIETTEL';
				break;
				
			case 3:
				$cardName = 'Mobifone';
				$TxtCard = 'VMS';
				$Telco = 'MOBIFONE';
				break;
				
			case 5:
				$cardName = 'Zing';
				$TxtCard = 'VNG';
				$Telco = 'ZING';
				break;
				
			case 6:
				$cardName = 'Vinaphone';
				$TxtCard = 'VNP';
				$Telco = 'VINAPHONE';
				break;
				
			case 7:
				$cardName = 'GATE';
				$TxtCard = 'FPT';
				$Telco = 'GATE';
				break;
				
				
			// case 8:
				// $cardName = 'Vcoin';
				// $TxtCard = '';
				// $Telco = '';
				// break;
				
			// case 11:
				// $cardName = 'Vietnamobile';
				// $TxtCard = '';
				// $Telco = '';
				// break;
		}
		
		$conn_sv = connectTank($serverid);
		
		if(!$conn_sv) {
			$return['type'] = 2;
			$return['content'] = 'Máy chủ bảo trì hoặc không thể kết nối.';
		} else {
			// if($_SESSION['ss_user_email'] == 'vinhcubu') {
				// $account = new account();
				// $return['type'] = -1958;
				// $return['content'] = $account->getAmountReceived(intval($_POST['menhgia_the']));
				// $return['content2'] = $_POST['menhgia_the'];
				// echo json_encode($return);
				// sqlsrv_close($conn);
				// die();
			// }
			$account = new account();
			$coinhave = $account->getAmountReceived(intval($_POST['menhgia_the']));
			if(!$coinhave) {
				$return['type'] = -1958;
				$return['content'] = 'Mệnh giá không hợp lệ vui lòng chọn mệnh giá khác!';
			} else
			if($Telco != null) {
				$request_id = rand(100000000, 999999999);  //Mã đơn hàng của bạn
				$command = 'charging';  // Nap the
				$url = 'https://thesieure.com/chargingws/v2';
				$partner_id = '5715317261';
				$partner_key = '3edba7a8497cda6af1b735e18ad8d5c2';
				
				// $result = Connect::init($passcard,$serial,$_POST['menhgia_the'], $type);
				
				$dataPost = array();
				$dataPost['request_id'] = $request_id;
				$dataPost['code'] = $passcard;
				$dataPost['partner_id'] = $partner_id;
				$dataPost['serial'] = $serial;
				$dataPost['telco'] = $Telco;
				$dataPost['command'] = $command;
				ksort($dataPost);
				$sign = $partner_key;
				foreach ($dataPost as $item) {
					$sign .= $item;
				}
				
				$mysign = md5($sign);

				$dataPost['amount'] = $_POST['menhgia_the'];
				$dataPost['sign'] = $mysign;

				$data = http_build_query($dataPost);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				curl_setopt($ch, CURLOPT_REFERER, $actual_link);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				$result = curl_exec($ch);
				curl_close($ch);

				$obj = json_decode($result);
				$errorMessage = array(
					'INPUT_DATA_INCORRECT' => 'Dữ liệu không hợp lệ'
				);
				if ($obj->status == 99) {
					//Gửi thẻ thành công, đợi duyệt.
					
					$account = new account();
					
					$account->logCard($_SESSION['ss_user_id'], $Telco, $serial, $passcard, 0, 0, $obj->request_id);
					$return['type'] = -115;
					$return['content'] = 'Thẻ này đã được gửi qua Nạp Chậm bạn vui lòng đợi 30s-1p nếu thẻ đúng sẽ có tiền trong tài khoản nhé';
				} elseif ($obj->status == 1) {
					//Thành công
					$account = new account();
					
					$account->logCard($_SESSION['ss_user_id'], $Telco, $serial, $passcard, $obj->value, 1, $obj->request_id);
					
					$menhgia = intval($obj->value);
					// khuyen mai or gi do
					// $coinhave = intval($obj->value) * 1; // day la coin goc nap 1 dc 1
					
					// chuyen tien vao tai khoan
					$canAdd = $account->addCoin($_SESSION['ss_user_id'], $coinhave, true);
					
					if($canAdd) {
						$account->log($_SESSION['ss_user_id'], 'Nạp thẻ', 2, 'Nạp thẻ '.$cardName.' mệnh giá '.number_format($menhgia).'VNĐ', $coinhave);
						
						//$account->addNumberField($_SESSION['ss_user_id'], "MoneyLock", $coinhave); // day la coin khuyen mai. khong thich them thi bo ntn
						$account->addNumberField($_SESSION['ss_user_id'], "VIPExp", $menhgia / 1000);
						
						$userInfo = $account->getUserInfo($_SESSION['ss_user_id']);
						
						$account->updateInfo($_SESSION['ss_user_id'], "VIPLevel", getVIPLevel($userInfo['VIPExp']));
						
						$return['type'] = 0;
						$return['content'] = 'Nạp thành công thẻ cào mệnh giá '.number_format($menhgia).'VNĐ. Bạn nhận được '.number_format($coinhave).'Coin.';
					
					} else {
						$return['type'] = -1958;
						$return['content'] = 'LỖI. Liên hệ GM để được trợ giúp.';
					}
					// Những loại thẻ được xử lý ngay Zing, Garena, Gate, Vcoin
				} elseif ($obj->status == 2) {
					//Thành công nhưng sai mệnh giá
					$account = new account();
					
					$account->logCard($_SESSION['ss_user_id'], $Telco, $serial, $passcard, $obj->value, 1, $obj->request_id);
					
					$menhgia = intval($obj->value);
					// khuyen mai or gi do
					// $coinhave = intval($obj->value) * 1; // day la coin goc nap 1 dc 1
					$coinhave = $account->getAmountReceived(intval($menhgia));//lay config tu db
					
					// chuyen tien vao tai khoan
					$canAdd = $account->addCoin($_SESSION['ss_user_id'], $coinhave, true);
					
					if($canAdd) {
						$account->log($_SESSION['ss_user_id'], 'Nạp thẻ', 2, 'Nạp thẻ '.$cardName.' mệnh giá '.number_format($menhgia).'VNĐ', $coinhave);
						
						//$account->addNumberField($_SESSION['ss_user_id'], "MoneyLock", $coinhave); // day la coin khuyen mai. khong thich them thi bo ntn
						$account->addNumberField($_SESSION['ss_user_id'], "VIPExp", $menhgia / 1000);
						
						$userInfo = $account->getUserInfo($_SESSION['ss_user_id']);
						
						$account->updateInfo($_SESSION['ss_user_id'], "VIPLevel", getVIPLevel($userInfo['VIPExp']));
						
						$return['type'] = 0;
						$return['content'] = 'Nạp thành công thẻ cào mệnh giá '.number_format($menhgia).'VNĐ. Bạn nhận được '.number_format($coinhave).'Coin.';
					
					} else {
						$return['type'] = -13;
						$return['content'] = 'LỖI. Liên hệ GM để được trợ giúp.';
					}
				} elseif ($obj->status == 3) {
					//Thẻ lỗi
					$return['type'] = -3;
					$return['content'] = $obj->message;
				} elseif ($obj->status == 4) {
					//Bảo trì
					$return['type'] = -4;
					$return['content'] = 'LỖI. Hệ thống nạp bảo trì!';
				} else {
					//Lỗi
					$return['type'] = -69;
					$return['content'] = $errorMessage[$obj->message] ? $errorMessage[$obj->message] : $obj->message;
					// $return['obj'] = $obj;
					// $return['dataPost'] = $dataPost;
				}
			} else {
				$return['type'] = -105;
				$return['content'] = 'Loại thẻ cào này chưa hỗ trợ';
			}
		}
	}
}
echo json_encode($return);
sqlsrv_close($conn);
?>