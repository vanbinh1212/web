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

$UsernameTxt = $_POST['UsernameTxt'];
$PasswordTxt = $_POST['PasswordTxt'];
$txtCoin = $_POST['txtCoin'];
$txtServer = $_POST['txtServer'];
$txtcharacter = $_POST['txtcharacter'];
$txtPassword2 = $_POST['txtPassword2'];

$qshop = @$data->query("Select Password2, Money from Mem_Account Where Email = '$UsernameTxt' and Password = '$PasswordTxt'","mem");
if(!is_numeric($txtCoin))
{
	return;
}
if($txtCoin < 1)
{
	return;
}
if(@$data->query_num($qshop) == 1) {
	$info = @$data->query_array($qshop);
	if($info['Password2'] == $txtPassword2)
	{	
		if($info['Money']>=$txtCoin)
		{
			$qshop = @$data->query("Select * from Server_List Where ServerID = '$txtServer'","mem");
			$info = @$data->query_array($qshop);
			$qshop = @$data->query("Select UserID,NickName from ".$info['Database'].".dbo.Sys_Users_Detail Where UserName = '$UsernameTxt' AND UserID = '$txtcharacter'","mem");
			$UserInfo = @$data->query_array($qshop);
			if(@$data->query_num($qshop) == 1)
			{
				$chargId = GUID();
				@$data->query("Update Mem_Account SET Money -= '$txtCoin'  Where Email = '$UsernameTxt' and Password = '$PasswordTxt'","mem");
				$txtCoinGoc = $txtCoin;
				$txtCoin *= 0.2;
				@$data->query("INSERT INTO ".$info['Database'].".[dbo].[Charge_Money] ([ChargeID] ,[UserName] ,[Money] ,[Date] ,[CanUse] ,[PayWay] ,[NeedMoney] ,[IP] ,[NickName]) VALUES('".$chargId."','$UsernameTxt','$txtCoin','".date("Y/m/d")."','true',0,0.00,NULL,N'".$UserInfo['NickName']."')","mem");
				//file_get_contents($info['LinkRequest'] . "ChargeMoney.aspx?content=" . urlencode($UserName . "|" . strtoupper($keyrand) . "|" . $timeNow . "|" . md5($UserName.strtoupper($keyrand) . $timeNow . $LoginKey)));
				soapChargeMoney($info['LinkCenter'],$UserInfo['UserID'],$chargId);
				$contentchuyen = 'Chuyển '.number_format($txtCoin).' Xu vào máy chủ '.$info['ServerName'];
				@$data->query("insert into Mem_History (UserID, Type, TypeCode, Content, Value, TimeCreate, IPCreate) VALUES ('".$UserInfo['UserID']."',N'Chuyển Xu', 3,N'$contentchuyen',$txtCoinGoc,0,'')","mem");
				echo "Đổi xu thành công";
			}
		}
		else
		{
			echo "Không đủ xu";
		}
	}	
	
}
else
{
	echo 'Lỗi';
}
function soapChargeMoney($Link, $playerid, $chargeId) {
	
	$linkWCF = $Link;
	
	if($linkWCF && $linkWCF != null) {
		// notice to player
		$client = SoapConnect($linkWCF);
		if($client) {
			// connect to soap
			$obj = array('userID' => $playerid, 'chargeID' => $chargeId);
			$result = $client->ChargeMoney($obj);
			if($result->ChargeMoneyResult == '1')
				return true;
			else
				return false;
		}
	}
}
function SoapConnect($link) {
	$options = array(
		'soap_version'=> SOAP_1_1, 
		'exceptions'=> false, 
		'trace'=> 1,
		'cache_wsdl'=> 0
	);
	$client = new SoapClient($link.'?wsdl', $options);
	return $client;
}
?>