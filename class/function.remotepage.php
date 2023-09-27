<?php
if(!defined("SNAPE_VN")) die ('No access');

function remotePage($content) {
	$value = array();
	switch ($content) {

		case 'dang-nhap':
			$value['title'] = 'Đăng nhập';
			$value['content'] = 'login';
			break;

		case 'dang-ky':
			$value['title'] = 'Đăng ký tài khoản';
			$value['content'] = 'register';
			break;

		case 'hop-qua':
			$value['title'] = 'Hộp quà may mắn';
			$value['content'] = 'openbox';
			break;

		case 'tai-khoan':
			$value['title'] = remoteAccount($_REQUEST['module'])['title'];
			$value['content'] = 'account';
			break;
			
		case 'ho-tro':
			$value['title'] = remoteSupport($_REQUEST['module'])['title'];
			$value['content'] = 'support';
			break;
		
		case 'chon-may-chu':
			$value['title'] = 'Chọn máy chủ';
			$value['content'] = 'selectserver';
			break;

		case 'panel-admin':
			$value['title'] = 'Quản lý người chơi';
			$value['content'] = 'paneladmin';
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

		case 'nap-the':
			$value['title'] = 'Nạp thẻ';
			$value['content'] = 'recharge';
			break;
		/*
		case 'top-mini':
			$value['title'] = 'Top Mini';
			$value['content'] = 'topmini';
			break;
		
		case 'top-online':
			$value['title'] = 'Top Online Gà Huyền Thoại';
			$value['content'] = 'toponline';
			break;
		
		case 'top-nap':
			$value['title'] = 'Top Nạp gà Huyền Thoại';
			$value['content'] = 'topnap';
			break;
			
		case 'usernamecuachuminhthangnaovaotaodachetmekkk':
			$value['title'] = 'Xem tài khoản';
			$value['content'] = 'username';
			break;
			
		case 'usernamecuachuminhthangnaovaotaodachetmekkks2':
			$value['title'] = 'Xem tài khoản';
			$value['content'] = 'usernames2';
			break;*/
			
		/*case 'quan-ly-ket-noi':
			$value['title'] = 'Quản lý kết nối';
			$value['content'] = 'connectmanage';
			break;*/
			
		case 'lich-su-giao-dich':
			$value['title'] = 'Lịch sử giao dịch';
			$value['content'] = 'history';
			break;
			
		case 'chuyen-xu':
			$value['title'] = 'Chuyển Xu';
			$value['content'] = 'changemoney';
			break;
			
		/*case 'doi-coin-game':
			$value['title'] = 'Đổi CoinGame';
			$value['content'] = 'changecg';
			break;*/
			
		/*case 'doi-vat-pham':
			$value['title'] = 'Đổi quà';
			$value['content'] = 'changeitem';
			break;*/
			
		/*case 'cua-hang':
			$value['title'] = 'Webshop - Cửa hàng';
			$value['content'] = 'webshop';
			break;*/
			
		/*case 'tui-web':
			$value['title'] = 'Túi web';
			$value['content'] = 'bagweb';
			break;*/
			
		case 'giftcode':
			$value['title'] = 'Giftcode - Quà tặng';
			$value['content'] = 'giftcode';
			break;
			
		case 'nap-momo':
			$value['title'] = 'Nạp Momo';
			$value['content'] = 'rechargemomo';
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