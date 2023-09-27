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

$sid = $_POST['sid'];
$username = $_POST['username'];

$qshop = @$data->query("Select * from Server_List Where ServerID = '$sid'","mem");

if(@$data->query_num($qshop) == 1) {
	$info = @$data->query_array($qshop);
	$qshop = @$data->query("Select UserID,NickName,Grade from ".$info['Database'].".dbo.Sys_Users_Detail Where UserName = '$username'","mem");
	$arr = array();
	while($info2 = @$data->query_array($strQueryDatabase))
			{
				$arr[] = $info2['UserID'].','.$info2['NickName'].','.$info2['Grade'];
			}
	echo $info['ServerName'].'+'.join("|",$arr);
}
else
{
	echo '0';
}
?>