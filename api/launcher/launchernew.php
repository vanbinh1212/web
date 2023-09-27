<?php
session_start();
define('Tu4nMR@PRoKAK4','Fuck You',true);
include_once("../connect.php");

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

//https://file.gunhoiuc.com/flashv70/Loading.swf|user=vinhkosd&key=190158&isGuest=&v=10950&rand=9553493&config=https://file.gunhoiuc.com/flashv70/config5.xml
//return type flash|param
/*
output:
	*flash -> loading.swf link
	*params user=vinhkosd&key=190158&isGuest=&v=10950&rand=9553493&config=link_config
input:
	["user"] = username,
	["key"] = md5pass,
	["sid"] = serverid
*/

$Link_Req = "http://gunae.click/req/";
$Link_Flash = "http://gunae.click/flash/";
$Link_Config = "http://gunae.click/flash/config.xml";
$LoginKey = "QY-16-WAN-0668-2555555-7ROAD-dandantang-cedrusnedinhlamcailogingi172";
$UserName = $_POST['user'];
$Password = $_POST['key'];



$qshop = @$data->query("Select UserId,Password,Email from Mem_Account Where Email = '$UserName' and Password = '$Password'","mem");
//$query->query("UPDATE Sys_Users_Detail SET IsLauncher = 1 where UserName = '$UserName'","mem");


if(@$data->query_num($qshop) == 1) {
	@$data->query("UPDATE Sys_Users_Detail SET IsLauncher = 1 where UserName = '$UserName'");
	$keyrand = GUID();
	$timeNow = time();
	$content = file_get_contents($Link_Req . "CreateLogin.aspx?content=" . urlencode($UserName . "|" . strtoupper($keyrand) . "|" . $timeNow . "|" . md5($UserName.strtoupper($keyrand) . $timeNow . $LoginKey)));
	//die($Link_Req . "CreateLogin.aspx?content=" . $UserName . "|" . strtoupper($keyrand) . "|" . $timeNow . "|" . md5($UserName.strtoupper($keyrand) . $timeNow . $LoginKey));
	$link = $Link_Flash . "/Loading.swf";
	$param = "user=" . $UserName . "&key=" . strtoupper($keyrand) . "&v=104&rand=92386938&config=" . $Link_Config;
	echo "$link|$param";
}

?>