<?php
session_start();
define('Tu4nMR@PRoKAK4','Fuck You',true);
include_once("../../connect.php");

$username   = $_POST['txtUser'];
$password   = md5($_POST['txtPassword']);
$passcap2   = md5($_POST['txtRef']);
$sex        = $_POST['txtSex'];
$server = 1;
$ip=getIP();
$timezone = +7;
$time = gmdate("H:i|d/m", time() + 3600*($timezone+date("I")));
$ngay = gmdate("d", time() + 3600*($timezone+date("I")));
#kiem tra du lieu
$checkuser  = preg_match("/^([a-zA-Z0-9\-\_]*)$/",$username);
	
if($checkuser == false || strlen($username) < 4 || strlen($username) > 15)
{
	die('Tên tài khoản không hợp lệ');
}

$nickname=rand();
$q = $data->query("SELECT * FROM Mem_Account WHERE Email= '$username'",'mem');
if($data->query_num($q) == 0)
{
	$sql3 = $data->query("select * from Sys_Users_Detail WHERE nickname =N'$nickname'");
	if($data->query_num($sql3) == 0)
	{
		$xu_game=0;
		//$nickname=rand();
		$coin_star=0;
		$xu_khoa=0;
		$vang=0;
		$kinhnghiem=0; // Level 30
		//$data->query("execute Mem_Users_CreateUser 'DanDanTang','$username','$password','0','1','MD5','false'",'mem');
		$data->query("insert into Mem_Account (Email, Password, Password2, TimeCreate, IPCreate, Money) VALUES ('$username','$password','$passcap2','".time()."','Launcher',0)",'mem');
		//@$data->query("insert into Log_AD (NickName,loai,Time,Username,coin) values ('$username',N'Đăng Ki $ip','$time','$username','$gia')");
		
		$data->query("execute SP_Account_Register '$username','$password',N'bembemgunny $nickname','$sex','$xu_game','$vang','$xu_khoa'");
		$qshop = @$data->query("Select UserId,Password,Email from Mem_Account Where Email = '$username'","mem");
		if(@$data->query_num($qshop) == 1) {
			$info = @$data->query_array($qshop);
			$_SESSION['ShopID']   = $info['UserId'];
			$_SESSION['Username'] = $info['UserName'];
			$_SESSION['Userpass'] = $info['Password'];
			$_SESSION['IDServer'] = $server;
			die('đăng ký thành công tài khoản '.$username.'!');
		}
		else 
		{
			die('Có lỗi xảy ra vui lòng liên hệ admin!');
		}
	}
	else
	{
		die('Tên nhân vật đã tồn tại !');
	}
}
else
{
	die('Tài khoản đã có trong hệ thống!');
}
?>