<?php
session_start();
define('Tu4nMR@PRoKAK4','Fuck You',true);
include_once("../../connect.php");
$txtUser = $_POST['txtUser'];
$txtPassword = md5($_POST['txtPassword']);
$txtOtp = md5($_POST['txtOtp']);

$params1 = array($txtUser, $txtOtp);
$params2 = array($txtPassword, $txtUser, $txtOtp);

$qshop = @$data->queryWithParam("Select Password2 from Mem_Account Where Email = ? and Password2 = ?",$params1);
if(@$data->query_num($qshop) == 1) {
	$info = @$data->query_array($qshop);
	@$data->queryWithParam("Update Mem_Account SET Password = ?  Where Email = ? and Password2 = ?",$params2);
	echo 'Lấy lại mật khẩu thành công';
}
else
{
	echo 'Thông tin không chính xác';
}
?>