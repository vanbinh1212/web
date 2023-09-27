<?php
session_start();
define("SNAPE_VN", true);

error_reporting(E_ALL ^ E_NOTICE);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once dirname(__FILE__).'/../#config.php';

include_once dirname(__FILE__).'/../class/class.account.php';

include dirname(__FILE__).'/../class/function.global.php';

$result['type'] = -1;
$result['content'] = 'Lỗi không xác định';
// check login
if(!account::isLogin()) {
	$result['type'] = -165;
	$result['content'] = 'Vui lòng đăng nhập trước';
}
$_SESSION['lastGetTop'] = !empty($_SESSION['lastGetTop']) ? $_SESSION['lastGetTop'] : (time() - 2);

if(time() - $_SESSION['lastGetTop'] < 2) {//2s cho get top 1 lan de phong truy cap db qua nhieu
	$result['type'] = -63;
	$result['content'] = 'timeout';
	echo json_encode($result);
	die();
}

$_SESSION['lastGetTop'] = time();

$type = $_POST['type'];
$listType = array('Guild', 'Level', 'FightPower');

if(!in_array($type, $listType)) {
	$result['type'] = -166;
	$result['content'] = 'Lỗi hệ thống';
}

// load
if($result['type'] == -1) {
	$dataReturn = [];
	$loadserver = loadallserver();
	while($svInfo = sqlsrv_fetch_array($loadserver, SQLSRV_FETCH_ASSOC)) {
		$dataReturn[$svInfo['ServerName']] = [];

		$connectionInfo = array("Database" => $svInfo['Database'], "UID" => $svInfo['Username'], "PWD" => $svInfo['Password'], "CharacterSet" => "UTF-8");

		$conn_road = sqlsrv_connect($svInfo['Host'], $connectionInfo);

		if($conn_road) {//have connection
			// $dataReturn[$svInfo['ServerID']][] = $itemCode;
			switch($type) {
				case 'Level':
				$columnName = 'Grade';
				$getTopAccount = sqlsrv_query($conn_road, "select TOP 10 NickName, Grade, GP from Sys_Users_Detail where IsExist = 1 order by GP desc", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
				break;
				case 'FightPower':
				$columnName = 'FightPower';
				$getTopAccount = sqlsrv_query($conn_road, "select TOP 10 NickName, FightPower from Sys_Users_Detail where IsExist = 1 order by FightPower desc", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
				break;
				case 'Guild':
				$columnName = ['ConsortiaName' => 0, 'FightPower' => 0 ];
				$getTopAccount = sqlsrv_query($conn_road, "select TOP 10 ConsortiaName, FightPower from Consortia order by FightPower desc", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
				break;
			}
			if(sqlsrv_num_rows($getTopAccount) > 0) {
				while($accountInfo = sqlsrv_fetch_array($getTopAccount, SQLSRV_FETCH_ASSOC)) {
					$dataReturn[$svInfo['ServerName']]['data'][] = $accountInfo;
				}
			} else {
				$dataReturn[$svInfo['ServerName']]['data'][] = $columnName;
			}
		} else {
			$dataReturn[$svInfo['ServerName']]['data'] = [];
		}
	}
}

echo json_encode($dataReturn);
?>