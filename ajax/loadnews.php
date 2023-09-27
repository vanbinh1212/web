<?php
session_start();
define("SNAPE_VN", true);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once dirname(__FILE__).'/../#config.php';
include_once dirname(__FILE__).'/../class/function.global.php';

$page = $_POST['page'];

$itemOnPage = $_config['function']['news_per_page'];

if(!is_numeric($page) || $page <= 0 || $page > 999999) {
	die("Lỗi dữ liệu.");
}

$limit = NULL;

$q = sqlsrv_query($conn, "Select Top($itemOnPage) *, RowID , TotalRows from
									(
									Select
									RowID=ROW_NUMBER() OVER (ORDER BY News.NewsID DESC),
									*,
									TotalRows=Count(*) OVER()
									from News  
									) A
									Where A.RowId > (($page-1)*$itemOnPage)
						", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

$return = array();

$return['list'] = array();

if(sqlsrv_num_rows($q) > 0) {
	$i = 0;
	while($newsInfo = sqlsrv_fetch_array($q, SQLSRV_FETCH_ASSOC)) {

		//$return['list'][$i] = $newsInfo;
		$return['list'][$i]['NewsID'] = $newsInfo['NewsID'];
		$return['list'][$i]['Title'] = $newsInfo['Title'];
		$return['list'][$i]['Type'] = nameTypeNews($newsInfo['Type']);
		$return['list'][$i]['Content'] = $newsInfo['Content'];
		$return['list'][$i]['TimeCreate'] = date("d-m-Y", $newsInfo['TimeCreate']);
		$return['list'][$i]['Link'] = $newsInfo['Link'];
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