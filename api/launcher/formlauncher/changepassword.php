<?php
session_start();
define('Tu4nMR@PRoKAK4','Fuck You',true);
include_once("../../connect.php");

//$data->sqlsrv_mem();

$username  = $_POST['user'];
$newpass   = $_POST['newpass'];
$pass 	   = $_POST['pass'];
$passtow = strtoupper(md5($pass));
$text      = null;
$check = preg_match("/^([a-zA-Z0-9\-\_]*)$/",$newpass);
$checkr = preg_match("/^([a-zA-Z0-9\-\_]*)$/",$renewpass);

$params1 = array($userName, $passtow);
$params2 = array($newpass, $username);

if($newpass == null || $pass == null)
		die('Vui lòng điền đầy đủ thông tin s');
	if($check==false || $checkr ==false)
		die('Vui lòng không nhập ký tự đặc biệt');
	if(strlen($newpass) < 4 || strlen($newpass) > 30)
		die('Mật khẩu phải từ 4 - 30 ký tự');
	if($text == null) {
		$newpass = strtoupper(md5($newpass));
		$q = @$data->queryWithParam("SELECT * from Mem_Account WHERE Email = ? AND Password = ?",$params1);
		if(@$data->query_num($q) == 1) {
			$pass1 = strtoupper($renewpass);
			//@$data->query("UPDATE Mem_UserInfo SET Password = '$pass1' where UserId in (select UserId from Mem_Users where UserName='$username')","mem");
			@$data->query("UPDATE Mem_Account SET Password = ? WHERE Email = ?",$params2);
			echo("Đổi mật khẩu mới thành công");
		} else die('Mật khẩu cũ không chính xác');
	}
?>