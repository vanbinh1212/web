<?php
session_start();
define("SNAPE_VN", true);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once(dirname(__FILE__)."/../#config.php");

include_once(dirname(__FILE__)."/../class/class.account.php");

include_once(dirname(__FILE__)."/../class/function.global.php");

include_once(dirname(__FILE__)."/../class/function.soap.php");

// tam dung
//die();

function getSameItemId($itemid, $cartArray) {
	
	$list = array();
	
	foreach($cartArray as $cartItem) {
		
		if($cartItem["ItemID"] == $itemid) {
			
			$list[] = $cartItem;
			
		}
		
	}
	
	return $list;
}

$account = new account();

$return['type'] = -1;
$return['content'] = 'Lỗi không xác định';

$params = trim($_POST['params']);

if(!$account->isLogin()) {
	$return['type'] = 1;
	$return['content'] = 'Chưa đăng nhập.';
}

if($params == null) {
	$return['type'] = 154;
	$return['content'] = 'Error verify params.';
}

if($return['type'] == -1) {
	
	// split params
	$paramArr = explode(',', $params);
	
	if(count($paramArr) <= 0) {
		
		$return['type'] = 154;
		$return['content'] = 'Không có vật phẩm trong giỏ đồ.';
		
	} else {
		
		$canContinue = true;
		
		$cartList = array();
		
		$itemIdList = array();
		// foreach item
		foreach($paramArr as $item) {
			
			$itemArr = explode('.', $item);
			
			if(count($itemArr) >= 2 && is_numeric($itemArr[0]) > 0 && is_numeric($itemArr[1]) && $itemArr[1] > 0 && $itemArr[1] <= 999) {
				
				$cartList[] = array("ItemID" => $itemArr[0], "Count" => $itemArr[1]);
				
				$itemIdList[] = $itemArr[0];
				
			} else {
				
				$canContinue = false;
				
				break;
				
			}
			
		}
		
		if($canContinue) {
			
			$itemsBlock = array();
			
			$itemsListBuy = array();
			
			$lastListItemId = array();
			
			$totalMoney = 0;
			
			$haveItemPlayers = false;
			
			$qFind = sqlsrv_query($conn, "select * from Webshop_Items where ID IN (".implode(",", $itemIdList).")", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
			
			if(sqlsrv_num_rows($qFind) > 0) {
				
				while($itemInfo = sqlsrv_fetch_array($qFind, SQLSRV_FETCH_ASSOC)) {
					
					for($i = 0; $i < count($cartList); $i++) {
						
						if($cartList[$i]["ItemID"] == $itemInfo["ID"]) {
							
							// check item is not vaild
							if($itemInfo["UserID"] != 0) {
								
								// nguoi choi ban
								if($itemsBlock[$itemInfo["ID"]] == false && $itemInfo["IsExit"] == true) {
									
									$itemInfo["RealCount"] = 1;
									
									$itemsListBuy[] = $itemInfo;
									
									$itemsBlock[$itemInfo["ID"]] = $itemInfo["ID"];
									
									$totalMoney += $itemInfo["Price"];
									
									$lastListItemId[] = $itemInfo["ID"];
									
									$haveItemPlayers = true;
								}
								
							} else {
								
								// he thong ban
								// get same
								$listSame = getSameItemId($itemInfo["ID"], $cartList);
								
								if(count($listSame) > 0) {
									
									foreach($listSame as $sameItem) {
										
										$itemInfo["RealCount"] = $sameItem["Count"];
										
										if($itemInfo["MultiBuy"] == false)
											$itemInfo["RealCount"] = 1;
										
										$itemsListBuy[] = $itemInfo;
										
										$totalMoney += $itemInfo["Price"] * $itemInfo["RealCount"];
								
										$lastListItemId[] = $itemInfo["ID"];
										
									}
									
								}
								
							}
							
							break;
							
						}
						
					}
					
				}
				
				if(count($itemsListBuy) > 0 && $totalMoney > 0) {
					
					// check money
					$userInfo = account::getUserInfo($_SESSION['ss_user_id']);
					
					$typePay = 0;
					
					if(!$haveItemPlayers && $userInfo['MoneyLock'] >= $totalMoney)
						$typePay = 2;
					else if($userInfo['Money'] >= $totalMoney)
						$typePay = 1;
					
					if($typePay > 0) {
						
						$canPaying = false;
						if($typePay == 1)
							$canPaying = account::removeMoney($_SESSION['ss_user_id'], 'Money', $totalMoney);
						else
							$canPaying = account::removeMoney($_SESSION['ss_user_id'], 'MoneyLock', $totalMoney);
						// remove money
						if($canPaying) {
							
							// set remove item sell by user
							if(count($itemsBlock) > 0) {
								sqlsrv_query($conn, "Update Webshop_Items SET IsExit = ? WHERE ID IN (".implode(',', $itemsBlock).")", array(false));
							}
							
							// set count buy from all items list
							if(count($lastListItemId) > 0)
								sqlsrv_query($conn, "Update Webshop_Items SET CountBuy = CountBuy + ? WHERE ID IN (".implode(',', $lastListItemId).")", array(1));
							
							// add log
							account::log($_SESSION['ss_user_id'], 'Webshop', 10, 'Mua vật phẩm ID: '.implode(',', $lastListItemId).' trên webshop sử dụng '.(($typePay == 1) ? 'Coin' : 'Coin Khóa'), -$totalMoney);
							// can buy
							foreach($itemsListBuy as $lastItem) {
								
								account::addItemBag($_SESSION['ss_user_id'], $lastItem['ServerID'], $lastItem['TemplateID'], ($lastItem['Count'] * $lastItem['RealCount']), $lastItem['IsBind'], $lastItem['VaildDate'], $lastItem['MultiBuy']);
								
								// send money to user seller
								if($lastItem["UserID"] != 0) {
									
									// send money
									$realMoneyReceive = floor($lastItem['Price'] - ($lastItem['Price'] / $_config['function']['fee_seller_webshop']));
									//account::addCoin($lastItem["UserID"], $realMoneyReceive, false);
									account::addNumberField($lastItem["UserID"], 'MoneyLock', $realMoneyReceive);
									account::log($lastItem['UserID'], 'Webshop', 10, 'Nhận Coin từ vật phẩm #'.$lastItem['ID'].' đăng bán trên Webshop mua bởi '.$_SESSION['ss_user_email'], $realMoneyReceive);
									
								}
								
							}
							$return['type'] = 0;
							$return['content'] = 'Thành công.';
							$return['money'] = number_format(account::getUserInfoByField($_SESSION['ss_user_id'], 'Money'));
							$return['moneylock'] = number_format(account::getUserInfoByField($_SESSION['ss_user_id'], 'MoneyLock'));
						}
						
					} else {
						// khongdu tien
						$return['type'] = 154;
						$return['content'] = 'Coin hoặc Coin Khóa của bạn không đủ để thanh toán. Hoặc có thể trong giỏ hàng có vật phẩm do người chơi đăng bán sẽ không thể sử dụng Coin Khóa để mua toàn bộ giỏ hàng.';
					}
					
				} else {
					
					$return['type'] = 154;
					$return['content'] = 'Thiết lập giỏ đồ của bạn bị lỗi. Liên hệ GM để được trợ giúp.';
					
				}
				
			} else {
				
				$return['type'] = 154;
				$return['content'] = 'Lỗi lấy thông tin giỏ đồ.';
				
			}
			
		} else {
			
			$return['type'] = 154;
			$return['content'] = 'Lỗi định dạng params. Liên hệ với GameMaster để được trợ giúp.';
			
		}
		
	}
	
}
echo json_encode($return);

sqlsrv_close($conn);
?>