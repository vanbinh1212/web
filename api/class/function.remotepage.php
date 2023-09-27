<?php
if(!defined("SNAPE_VN")) die ('No access');

function remotePage($content) {
	$value = array();
	switch ($content) {

		case 'dang-nhap':
			$value['title'] = 'Đăng nhập';
			$value['content'] = 'login';
			break;
		case 'quen-mat-khau':
			$value['title'] = 'Quên mật khẩu';
			$value['content'] = 'forgotpass';
			break;
		case 'dang-ky':
			$value['title'] = 'Đăng ký tài khoản';
			$value['content'] = 'register';
			break;

		//case 'hop-qua':
			//$value['title'] = 'Hộp quà may mắn';
			//$value['content'] = 'openbox';
			//break;

		case 'tai-khoan':
			$value['title'] = remoteAccount($_REQUEST['module'])['title'];
			$value['content'] = 'account';
			break;
			
		case 'ho-tro':
			$value['title'] = remoteSupport($_REQUEST['module'])['title'];
			$value['content'] = 'support';
			break;
		/*case 'cedrus':
			$value['title'] = 'Cedrus Administrator';
			$value['content'] = 'cedrus';
			break;
		case 'cedrus-1':
			$value['title'] = 'Cedrus Administrator';
			$value['content'] = 'cedrus-1';
			break;*/
		case 'panel-quantri':
			$value['title'] = 'Administrator';
			$value['content'] = 'admin';
			break;
        case 'panel-admin':
            $value['title'] = 'Quản lý người chơi';
            $value['content'] = 'paneladmin';
            break;
        case 'chon-may-chu':
			$value['title'] = 'Chọn máy chủ';
			$value['content'] = 'selectserver';
			break;

		default:
			$value['title'] = null;
			$value['content'] = 'home';
			break;
	}
	return $value;
}

function remoteAccount($content = null) {
	$value = array();
	switch ($content) {

		case 'doi-mat-khau':
			$value['title'] = 'Đổi mật khẩu';
			$value['content'] = 'changepassword';
			break;
		/*case 'vip':
		$value['title'] = 'VIP';
		$value['content'] = 'giftvip';
		break;*/
		case 'gui-nap':
		$value['title'] = 'NẠP ATM / MOMO';
		$value['content'] = 'guinap';
		break;
		case 'thong-tin':
			$value['title'] = 'Thông tin cá nhân';
			$value['content'] = 'thongtin';
			break;	
		case 'nap-the':
			$value['title'] = 'Nạp thẻ';
			$value['content'] = 'recharge';
			break;
		case 'huong-dan':
			$value['title'] = 'Hướng Dẫn';
			$value['content'] = 'huongdan';
			break;
		case 'tao-nick':
			$value['title'] = 'Tạo nhân vật';
			$value['content'] = 'createnick';
			break;

		case 'log-card':
			$value['title'] = 'Lịch sử nạp thẻ';
			$value['content'] = 'logcard';
			break;			
		case 'choi-ngay':
			$value['title'] = 'Play Now';
			$value['content'] = 'playnow';
			break;
		case 'xoa-ket':
			$value['title'] = 'Xoa Ket';
			$value['content'] = 'xoaket';
			break;
		case 'quan-ly-ket-noi':
			$value['title'] = 'Quản lý kết nối';
			$value['content'] = 'connectmanage';
			break;
			
		case 'lich-su-giao-dich':
			$value['title'] = 'Lịch sử giao dịch';
			$value['content'] = 'history';
			break;
		//case 'top':
			//$value['title'] = 'SỰ KIỆN ĐUA TOP TUẦN';
			//$value['content'] = 'top';
			//break;			
		case 'chuyen-xu':
			$value['title'] = 'Chuyển Xu';
			$value['content'] = 'changemoney';
			break;
		/*case 'chuyen-xu-game':
			$value['title'] = 'Chuyển XuGame vào InGame';
			$value['content'] = 'changemoneylock';
			break;	*/
		/*case 'doi-coin-game':
			$value['title'] = 'Đổi CoinGame';
			$value['content'] = 'changecg';
			break;
			
		case 'doi-vat-pham':
			$value['title'] = 'Đổi quà';
			$value['content'] = 'changeitem';
			break;*/
			
		case 'cua-hang':
			$value['title'] = 'Webshop - Cửa hàng';
			$value['content'] = 'webshop';
			break;
			
		case 'tui-web':
			$value['title'] = 'Túi web';
			$value['content'] = 'bagweb';
			break;
		/*case 'kho-web':
			$value['title'] = 'Kho Web';
			$value['content'] = 'bagwebextra';
			break;	*/
		/*case 'tui-game':
			$value['title'] = 'Túi Game';
			$value['content'] = 'bagGame';
			break; */
		case 'giftcode':
			$value['title'] = 'Giftcode - Quà tặng';
			$value['content'] = 'giftcode';
			break;
		case 'hop-qua':
			$value['title'] = 'Hộp quà may mắn';
			$value['content'] = 'openbox';
			break;
			
		
		default:
			$value['title'] = 'Thông tin tài khoản';
			$value['content'] = 'accountinfo';
			break;

	}
	return $value;
}

function remoteSupport($content = null) {
	$value = array();
	switch ($content) {

		case 'gui-ho-tro':
			$value['title'] = 'Gửi hỗ trợ';
			$value['content'] = 'newsupport';
			break;
			
		case 'help-center':
			$value['title'] = 'Câu hỏi thường gặp';
			$value['content'] = 'helpcenter';
			break;
		
		default:
			$value['title'] = 'Hỗ trợ tài khoản';
			$value['content'] = 'listsupport';
			break;
	}
	return $value;
}

?>