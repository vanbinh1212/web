<?php
if(!defined("SNAPE_VN")) die ('No access');

function nameTypeNews($type) {
	switch ($type) {
		case 1:
			return 'Tin tức';
			break;

		case 2:
			return 'Sự kiện';
			break;

		case 3:
			return 'Quà tặng';
			break;

		case 4:
			return 'Khuyến mãi';
			break;

		case 4:
			return 'Hướng dẫn';
			break;
		
		default:
			# code...
			return 'Không xác định';
			break;
	}
}

function convertncr($string) {
	$convmap = array(0x80, 0xffff, 0, 0xffff);
	return mb_encode_numericentity($string, $convmap, 'UTF-8');
}

function loadserver($serverid) {

	global $conn;

	$qCheck = sqlsrv_query($conn, "select * from Server_List where ServerID = ?", array($serverid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	if(sqlsrv_num_rows($qCheck) > 0) {
		$serverInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);
		return $serverInfo;
	} else {
		return null;
	}
}

function getServerName($serverid) {

	global $conn;

	$qCheck = sqlsrv_query($conn, "select ServerName from Server_List where ServerID = ?", array($serverid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	if(sqlsrv_num_rows($qCheck) > 0) {
		
		$serverInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);
		
		return $serverInfo['ServerName'];
		
	} else {
		
		return "Unknown";
		
	}
}

function loadallserver() {

	global $conn;

	$qCheck = sqlsrv_query($conn, "select * from Server_List order by ServerID DESC", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	return $qCheck;
}

function loadAllSupportCategory() {
	global $conn;

	$qCheck = sqlsrv_query($conn, "select * from Support_Category order by ID ASC", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	return $qCheck;
}

function loadSupportCategory($id) {

	global $conn;

	$qCheck = sqlsrv_query($conn, "select * from Support_Category where ID = ?", array($id), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	if(sqlsrv_num_rows($qCheck) > 0) {
		$serverInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);
		return $serverInfo;
	} else {
		return false;
	}
}

function statusName($type) {
	$result = array();
	
	switch($type) {
		case 0:
		$result['name'] = 'Chờ trả lời...';
		$result['color'] = '#EF380F';
		break;
		case 1:
		$result['name'] = 'Đã hồi âm';
		$result['color'] = '#5cb85c';
		break;
		case 2:
		$result['name'] = 'Đã đóng';
		$result['color'] = '#747579';
		break;
	}
	
	return $result;
}

function notice($text) {
	echo '<script>alert("'.$text.'")</script>';
}

function movepage($link) {
	die('<script>window.location="'.$link.'"</script>');
}

function url_origin($s, $use_forwarded_host=false)
{
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url($s, $use_forwarded_host=false)
{
    return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}

function base64currenturl() {

	$urlSite = full_url($_SERVER);

	return base64_encode($urlSite);
}

function actionPopup($link, $close = false, $action = null) {

	echo '<script>';

	if($link != null)
		echo 'window.opener.location = "'.$link.'";';

	if($action != null)
		echo $action;

	if($close)
		echo 'window.close();';

	echo '</script>';
	
	die();
}

function connectTank($serverid) {
	
	global $conn;
	
	$check = sqlsrv_query($conn, "select * from Server_List WHERE ServerID = ?" , array($serverid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
	
	if(sqlsrv_num_rows($check) > 0) {
		
		$svInfo = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);
		
		$connectionInfo = array("Database" => $svInfo['Database'], "UID" => $svInfo['Username'], "PWD" => $svInfo['Password'], "CharacterSet" => "UTF-8");

		$conn_sv = sqlsrv_connect($svInfo['Host'], $connectionInfo);
		
	}
	
	sqlsrv_free_stmt($check);
	
	return $conn_sv;
}

function checkuserValid($mail, $pass)
{
	global $conn;
	
	$ishave = false;

	$qCheck = sqlsrv_query($conn, "select * from Mem_Account WHERE Email = ? AND Password = ? AND IsBan = ?", array($mail, $pass, false), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	if(sqlsrv_num_rows($qCheck) > 0) {
		$ishave = true;
	}
	
	sqlsrv_free_stmt($qCheck);

	return $ishave;
}



function createGiftcode($subcode) {
	
	$rand1 = rand(0, 999);

	$rand2 = rand(0, 999);

	$rand3 = rand(0, 999);

	$rand4 = rand(0, 999);

	$rand5 = rand(1, 10);

	$code1 = md5($rand1.$subcode).md5($rand2.$subcode);

	if($rand5 > 5) {
		$code2 = md5($rand3).md5($rand4);
	} else {
		$code2 = md5($rand4).md5($rand3);
	}
	
	$code3 = md5(substr($code1, 0, strlen($code1) / 2).$code2);

	$finalCode = strtoupper(substr($code3, 0, 30));
	
	return $finalCode;
}


function addGiftcode($activeid, $subcode) {
	
	global $conn_sv;
	
	$code = createGiftcode($subcode.$activeid);
	
	$insert = sqlsrv_query($conn_sv, "insert into Active_Number (AwardID, ActiveID, PullDown, UserID, Mark) VALUES (?, ?, ?, ?, ?)" , array($code, $activeid, false, 0, 0));
	
	if($insert) {
		return $code;
	}
	
	return false;
}

function getVIPLevel($exp) {
	if($exp >= 4000)
		return 8;
	else if($exp >= 3000)
		return 7;
	else if($exp >= 2000)
		return 6;
	else if($exp >= 1500)
		return 5;
	else if($exp >= 1000)
		return 4;
	else if($exp >= 500)
		return 3;
	else if($exp >= 200)
		return 2;
	else if($exp >= 100)
		return 1;
	else
		return 0;
}

function getVIPExpNeed($exp) {
	if($exp < 100)
		return 100 - $exp;
	else if($exp < 200)
		return 200 - $exp;
	else if($exp < 500)
		return 500 - $exp;
	else if($exp < 1000)
		return 1000 - $exp;
	else if($exp < 1500)
		return 1500 - $exp;
	else if($exp < 2000)
		return 2000 - $exp;
	else if($exp < 3000)
		return 3000 - $exp;
	else if($exp < 4000)
		return 4000 - $exp;
	else
		return 0;
}

function getDateTime($unix) {
	
	//$timezone = new DateTimeZone("GMT");
	$date = new DateTime("now");
	
	if($unix != "now")
		$date->setTimestamp($unix);
	
	return $date;
	
}

function vaildCaptcha($code) {
	
	$vaild = true;
	
	if (empty($_SESSION['captcha']) || trim(strtolower($code)) != $_SESSION['captcha'])
        $vaild = false;
	
	unset($_SESSION['captcha']);
	
	return $vaild;
}

function get_curl($url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

        $str = curl_exec($curl);
        if(empty($str)) $str = curl_exec_follow($curl);
        curl_close($curl);

        return $str;
    }
	
	function curl_exec_follow($ch, &$maxredirect = null) {
  
    // we emulate a browser here since some websites detect
    // us as a bot and don't let us do our job
    $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)".
                  " Gecko/20041107 Firefox/1.0";
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent );
  
    $mr = $maxredirect === null ? 5 : intval($maxredirect);
    if (!ini_get('open_basedir') && !ini_get('safe_mode')) {
  
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
      curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
    } else {
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
  
      if ($mr > 0)
      {
        $original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $newurl = $original_url;
        
        $rch = curl_copy_handle($ch);
        
        curl_setopt($rch, CURLOPT_HEADER, true);
        curl_setopt($rch, CURLOPT_NOBODY, true);
        curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
        do
        {
          curl_setopt($rch, CURLOPT_URL, $newurl);
          $header = curl_exec($rch);
          if (curl_errno($rch)) {
            $code = 0;
          } else {
            $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
            if ($code == 301 || $code == 302) {
              preg_match('/Location:(.*?)\n/i', $header, $matches);
              $newurl = trim(array_pop($matches));
              
              // if no scheme is present then the new url is a
              // relative path and thus needs some extra care
              if(!preg_match("/^https?:/i", $newurl)){
                $newurl = $original_url . $newurl;
              }   
            } else {
              $code = 0;
            }
          }
        } while ($code && --$mr);
        
        curl_close($rch);
        
        if (!$mr)
        {
          if ($maxredirect === null)
          trigger_error('Too many redirects.', E_USER_WARNING);
          else
          $maxredirect = 0;
          
          return false;
        }
        curl_setopt($ch, CURLOPT_URL, $newurl);
      }
    }
    return curl_exec($ch);
  }
	
function signature_hash($parnerID, $secreckey,$telco,$serial,$mathe,$tranid)
{
	return md5($parnerID.'-'.$secreckey.'-'.$telco.'-'.$serial.'-'.$mathe.'-'.$tranid);
}
	
function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
?>