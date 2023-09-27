<?php
session_start();
define("SNAPE_VN", true);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once dirname(__FILE__).'/../#config.php';
include_once dirname(__FILE__).'/../class/function.global.php';

$page = $_POST['page'];

$itemOnPage = $_config['function']['his_per_page'];

if(!is_numeric($page) || $page <= 0 || $page > 999999) {
	die("Lỗi dữ liệu.");
}

$limit = NULL;

$q = sqlsrv_query($conn, "Select Top($itemOnPage) *, RowID , TotalRows from
									(
									Select
									RowID=ROW_NUMBER() OVER (ORDER BY Mem_History.ID DESC),
									*,
									TotalRows=Count(*) OVER()
									from Mem_History where UserID = ?
									) A
									Where A.RowId > (($page-1)*$itemOnPage)
						", array($_SESSION['ss_user_id']), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

$return = array();

$return['list'] = array();

if(sqlsrv_num_rows($q) > 0) {
	$i = 0;
	while($newsInfo = sqlsrv_fetch_array($q, SQLSRV_FETCH_ASSOC)) {

		//$return['list'][$i] = $newsInfo;
		$return['list'][$i]['ID'] = $newsInfo['ID'];
		$return['list'][$i]['Type'] = $newsInfo['Type'];
		$return['list'][$i]['Content'] = $newsInfo['Content'];
		$return['list'][$i]['Cash'] = (($newsInfo['Value'] < 0) ? '<font color="red">'.number_format($newsInfo['Value']).'</font>' : '<font color="blue">'.number_format($newsInfo['Value']).'</font>');

		if(!$totalitem)
			$totalitem = $newsInfo['TotalRows'];
		$i++;
	}

	if($totalitem) {
		$return['totalpage'] = ceil($totalitem / $i);
		$return['totalitem'] = $totalitem;
		$return['itemOnPage'] = $i;
	}
}

echo json_encode($return);

sqlsrv_free_stmt($q);
sqlsrv_close($conn);
?>