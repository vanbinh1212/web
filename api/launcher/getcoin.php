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

$UserName = $_POST['user'];
$Password = $_POST['key'];

$qshop = @$data->query("Select Money from Mem_Account Where Email = '$UserName' and Password = '$Password'","mem");

if(@$data->query_num($qshop) == 1) {
	$info = @$data->query_array($qshop);
	echo $info['Money'];
}
else
{
	echo '0';
}
?>