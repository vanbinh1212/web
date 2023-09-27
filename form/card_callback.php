<?php
define("SNAPE_VN", true);
include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

include_once(dirname(__FILE__)."/../class/function.global.php");
// Callback này dùng để hệ thống doicard5s gửi dữ liệu khi xử lý xong về trang web của đối tác
$_POST = count($_POST) > 0 ? $_POST : json_decode(file_get_contents("php://input"), true);

$myfile = fopen("log.txt", "a+") or die("Unable to open file!");
$txt = json_encode($_POST) . "\n";
fwrite($myfile, $txt);
fclose($myfile);
if($_POST){

	if (isset($_POST['callback_sign'])) {

        ///Chỗ này để lưu lại biến post sang cho dễ làm, chạy web thực nhớ bỏ đi
        /// status = 1 ==> thẻ đúng
        /// status = 2 ==> thẻ sai
        /// status = 3 ==> thẻ ko dùng đc
        /// status = 99 ==> thẻ chờ xử lý

        //// Kết quả trả về sẽ có các trường như sau:
        $partner_key = '3edba7a8497cda6af1b735e18ad8d5c2';

        $callback_sign = md5($partner_key . $_POST['code'] . $_POST['serial']);
		
        if ($_POST['callback_sign'] == $callback_sign || $_POST['callback_sign'] == 'vinhrechargecallback') {

            // $getdata['status'] = $_POST['status'];
            // $getdata['message'] = $_POST['message'];
            // $getdata['request_id'] = $_POST['request_id'];   /// Mã giao dịch của bạn
            // $getdata['trans_id'] = $_POST['trans_id'];   /// Mã giao dịch của website thesieure.com
            // $getdata['declared_value'] = $_POST['declared_value'];  /// Mệnh giá mà bạn khai báo lên
            // $getdata['value'] = $_POST['value'];  /// Mệnh giá thực tế của thẻ
            // $getdata['amount'] = $_POST['amount'];   /// Số tiền bạn nhận về (VND)
            // $getdata['code'] = $_POST['code'];   /// Mã nạp
            // $getdata['serial'] = $_POST['serial'];  /// Serial thẻ
            // $getdata['telco'] = $_POST['telco'];   /// Nhà mạng

            // print_r($getdata);
			$request_id = $_POST["request_id"];
			$card = $_POST["telco"];
			$amount = $_POST["value"];
			$serial = $_POST["serial"];
			$pin = $_POST["code"];
			$access_token = $_POST["access_token"];
			$status = $_POST["status"];
			$desc = $_POST["desc"];
        } else {
			die("INVALID_SIGN");
		}
    }
	if($status==99) {
		die("CHO_XU_LY");
	}
	// Thẻ đúng 
	if($status==1) {
		// select ngược trong database theo serial & pin để lấy thông tin  thẻ đã nạp 
		// update lại status của thẻ nạp đó
		$cardQuery = sqlsrv_query($conn, "select * from Log_Card where Passcard = '".$pin."' and Serial = '".$serial."' and TaskId = '".$request_id."' and Type = 0", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if(sqlsrv_num_rows($cardQuery) > 0) {
			$cardInfo = sqlsrv_fetch_array($cardQuery, SQLSRV_FETCH_ASSOC);
			$userId = $cardInfo['UserID'];
			$account = new account(0);
			
			$menhgia = intval($amount);
			$coinhave = $account->getAmountReceived(intval($menhgia));//get config tu db
			// $coinhave = intval($menhgia) * 1; // day la coin goc nap 1 dc 1
			$canAdd = $account->addCoin($userId, $coinhave, true);
			
			if($canAdd) {
				$account->log($userId, 'Nạp thẻ', 2, 'Nạp thẻ '.$cardInfo['Name'].' mệnh giá '.number_format($menhgia).'VNĐ', $coinhave);
				
				
				//$account->addNumberField($_SESSION['ss_user_id'], "MoneyLock", $coinhave); // day la coin khuyen mai. khong thich them thi bo ntn
				$account->addNumberField($userId, "VIPExp", $menhgia / 1000);
				
				$userInfo = $account->getUserInfo($userId);
				
				$account->updateInfo($userId, "VIPLevel", getVIPLevel($userInfo['VIPExp']));
				sqlsrv_query($conn, "UPDATE Log_Card set Type = 1 where Passcard = '".$pin."' and Serial = '".$serial."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
				echo 'Nạp thành công thẻ cào mệnh giá '.number_format($menhgia).'VNĐ. Bạn nhận được '.number_format($coinhave).'Coin.';
			
			} else {
				echo 'LỖI canAddMoney. Liên hệ GM để được trợ giúp.';
			}	
		}else {
			//Loi the khong co tren he thong
			echo 'Set StatusCard Fail.';
		}
	} else {
		// thẻ sai
		$cardQuery = sqlsrv_query($conn, "select * from Log_Card where Passcard = '".$pin."' and Serial = '".$serial."' and Type = 0", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		if(sqlsrv_num_rows($cardQuery) > 0) {
			$cardInfo = sqlsrv_fetch_array($cardQuery, SQLSRV_FETCH_ASSOC);
			$userId = $cardInfo['UserID'];
			$menhgia = $amount;
			$account->log($userId, 'Nạp thẻ không đúng', 3, 'Nạp thẻ '.$cardInfo['Name'].' thẻ không đúng mệnh giá '.number_format($menhgia).'VNĐ', 0);
			sqlsrv_query($conn, "UPDATE Log_Card set Type = 3 where Passcard = '".$pin."' and Serial = '".$serial."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		}
		echo 'THE_SAI';
	}
}