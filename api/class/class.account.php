<?php
if(!defined("SNAPE_VN")) die('No access');

class account {

	public $userid;

	public function __construct($uid = 0) {

		$this->userid = $uid;

    }

    public static function getUsername($username = null) {
    	//return str
    	if($username == null)
    		$username = $_SESSION['ss_user_email'];
    	
    	$emailArr = explode("@", $username);

    	$firstName = $username;


    	return $firstName;
    }
    public static function getPlayerInfobyuid($uid) {

        global $conn_sv, $_config;

        $qCheck = sqlsrv_query($conn_sv, "select UserID, State, UserName, NickName, Money, Win from Sys_Users_Detail where UserID = ?", array($uid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

        if(sqlsrv_num_rows($qCheck) > 0) {

            $userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

            sqlsrv_free_stmt($qCheck);

            return $userInfo;

        } else {

            return false;

        }
    }
	public static function UpdateLauncherState($isLauncher, $username, $serverid) {
			include_once(dirname(__FILE__)."/../class/function.global.php");
			$conn_sv = connectTank($serverid);
	
			if(!$conn_sv) {
				return;
			}
		
		$qUpdate = sqlsrv_query($conn_sv, "UPDATE Sys_Users_Detail SET IsLauncher = ? where UserName = ?", array($isLauncher, $username));

		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
			
		}
		
	}
	public static function findEmptySlotByBagType($bagtype,$uid,$connect) {
		
				for($i =31; $i<80; $i++)
				{
					$qCheck = sqlsrv_query($connect, "Select Place from Sys_Users_Goods where BagType = ? and Place = ? and UserID = ? order by Place", array($bagtype,$i, $uid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
					if(sqlsrv_num_rows($qCheck) == 0)
					{
						return $i;
					}
				}
				return -1;
	}
	public static function UpdateBugDoPet($serverid) {
			include_once(dirname(__FILE__)."/../class/function.global.php");
			$conn_sv = connectTank($serverid);
	
			if(!$conn_sv) {
				return;
			}
		
		$qUpdate = sqlsrv_query($conn_sv, "update Pet_Equip_Data set ValidDate=365 where eqTemplateID='-1'");

		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
			
		}
		
	}
		public static function InsertCodeNap($pin, $menhgia, $serverid) {
			include_once(dirname(__FILE__)."/../class/function.global.php");
			$conn_sv = connectTank($serverid);
	
			if(!$conn_sv) {
				return;
			}
		
		$qUpdate = sqlsrv_query($conn_sv, "INSERT INTO Active_Number (AwardID, ActiveID, PullDown, UserID) VALUES (?, ?, ?, ?)", array($pin, $menhgia, 0, 0));

		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
			
		}
		
		}
		public static function GuiItemNapThe($templateid, $playerInfo ,$serverid) {
		
		include_once(dirname(__FILE__)."/../class/function.global.php");
			$conn_sv = connectTank($serverid);
	
			if(!$conn_sv) {
				return;
			}
		//@$data->query("execute Webshop_giftcode '$idvp','$userid','$username','$hopthanh','$hopthanh','$hopthanh','$hopthanh','$StrengthenLevel','$soluong','$isbinds'");
	function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
	}
		$qUpdate = sqlsrv_query($conn_sv, "execute Cedrus_Naptichluy ?, ?, ?,'0','0','0','0','0','1','1'", array($templateid, $playerInfo['UserID'], $playerInfo['UserName']));
		$url='http://gunlove.net/kichthu/WebForm1.aspx?uid='.$playerInfo['UserID'].'&sid=1';
		
		get_data($url);					
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}
	}
		
		public static function getPlayerInfoNap($username, $serverid) {

			include_once(dirname(__FILE__)."/../class/function.global.php");
				$conn_sv = connectTank($serverid);
		
				if(!$conn_sv) {
					return;
				}

			$qCheck = sqlsrv_query($conn_sv, "select * from Sys_Users_Detail where UserName = ?", array($username), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

			if(sqlsrv_num_rows($qCheck) > 0) {

				$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

				sqlsrv_free_stmt($qCheck);

				return $userInfo;

			} else {

				return false;

			}
		}
			public static function getPlayerInfoNapbyuid($userid, $serverid) {

			include_once(dirname(__FILE__)."/../class/function.global.php");
				$conn_sv = connectTank($serverid);
		
				if(!$conn_sv) {
					return;
				}

			$qCheck = sqlsrv_query($conn_sv, "select * from Sys_Users_Detail where UserID = ?", array($userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

			if(sqlsrv_num_rows($qCheck) > 0) {

				$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

				sqlsrv_free_stmt($qCheck);

				return $userInfo;

			} else {

				return false;

			}
		}
	
    public static function isLogin() {

		global $_config;

		if($_SESSION['ss_user_id'] && $_SESSION['ss_user_email'] && $_SESSION['ss_user_key'] == md5($_SESSION['ss_user_id'].$_SESSION['ss_user_email'].$_SESSION['ss_user_pass'].$_config['function']['code_anti_hack_session']))
			return true;
		else {
			//die("" .md5($_SESSION['ss_user_id'].$_SESSION['ss_user_email'].$_SESSION['ss_user_pass'].$_config['function']['code_anti_hack_session']));
			if($_SESSION['ss_user_id'] || $_SESSION['ss_user_email'] || $_SESSION['ss_user_pass'])
				self::removeSession();
			return false;
		}
	}

	public static function createSession($userid, $email, $password) {

		global $_config;

		$_SESSION['ss_user_id'] = $userid;

		$_SESSION['ss_user_email'] = $email;
		
		$_SESSION['ss_user_pass'] = $password;

		$_SESSION['ss_user_key'] = md5($userid.$email.$password.$_config['function']['code_anti_hack_session']);
		
		//die($_SESSION['ss_user_key']);

	}
	public static function createNickname($username, $password, $nickname , $sex, $gp, $grade) {

	global $conn_sv;


$Attack = 0;
$Defence = 0;
$Luck = 0;
$Agility = 0;
$Gold = 0;
$Money = 0;
$Style = ',,,,,,';
$Colors = ',,,,,,';
$Hide = 1111111111;
$Grade = 1;
$GP = 0;
$State = 0;
$ConsortiaID = 0;
$UserName = $username;
$NickName = $nickname;
$PassWord = 'bot';
$Sex = $sex;
$ActiveIP = 'gunae.click';
$Skin = 'NULL';
$Site = 'NULL' ;

	$qCheck = sqlsrv_query($conn_sv, "exec SP_Users_Activeweb @Userid = 0, @Attack = 0, @Defence = 0, @Luck = 0, @Agility= 0, @Gold= 0, @Money = 0,@MagicAttack ='0', @MagicDefence='0', @evolutionGrade=0, @evolutionExp=0, @Style = ',,,,,,', @Colors = ',,,,,,', @Hide = '1111111111', @Grade = '1', @GP = 0, @State = 0, @ConsortiaID = 0, @UserName = '$UserName',@NickName=N'$nickname', @PassWord = 'bot', @Sex = '$Sex', @ActiveIP = 'gunae.click', @Skin = 'NULL', @Site = 'NULL'  ", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	if($qCheck)
			sqlsrv_free_stmt($qCheck);
	}
	
	
	public static function removeSession() {
		$_SESSION['ss_user_id'] = false;
		$_SESSION['ss_user_email'] = false;
		$_SESSION['ss_user_pass'] = false;
		$_SESSION['ss_user_key'] = false;
	}

	private static function createMail($socialId, $type, $socialVar) {
		switch ($type) {

			case 'facebook':
				return $socialId.'@facebook.com';
				break;

			case 'google':
				if($socialVar != null)
					return $socialVar->email;
				else
					return $socialId.'@gmail.com';
				break;

			case 'yahoo':
				if($socialVar != null)
					return $socialVar['contact/email'];
				else
					return $socialId.'@yahoo.com';
				break;
			
			default:
				return null;
				break;
		}
	}

	public static function login($mail, $pass) {

		global $conn, $_config;

		$ishave = false;

		$qCheck = sqlsrv_query($conn, "select * from Mem_Account WHERE Email = ? AND Password = ? AND IsBan = ?", array($mail, $pass, false), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$ishave = true;

			$accInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			// create session
			self::createSession($accInfo['UserID'], $mail, $accInfo['Password']);

		}

		sqlsrv_free_stmt($qCheck);

		return $ishave;
	}

	public static function checkSocial($type, $socialVar) {

		global $conn, $_config;

		$isConnect = false;

		$checkSocial = sqlsrv_query($conn, "select * from Mem_Social WHERE Type = ? AND SocialID = ?", array($type, $socialVar['ID']), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($checkSocial) > 0) {

			$isConnect = true;

		}

		sqlsrv_free_stmt($checkSocial);

		return $isConnect;

	}

	public static function getSocial($socialId, $type) {

		global $conn, $_config;

		$checkSocial = sqlsrv_query($conn, "select * from Mem_Social WHERE Type = ? AND SocialID = ?", array($type, $socialId), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($checkSocial) > 0)
			return sqlsrv_fetch_array($checkSocial, SQLSRV_FETCH_ASSOC);

		return false;
	}

	public static function connectSocial($userid, $type, $socialVar) {

		global $conn, $_config;

		$canConnect = true;

		if(self::isLogin()) {

			$canConnect = self::createSocial($userid, $type, $socialVar);

		}

		return $canConnect;

	}

	public static function loginSocial($type, $socialVar) {

		global $conn, $_config;
		
		$returnVal = false;

		$qCheck = sqlsrv_query($conn, "select Mem_Social.*, Mem_Account.Email, Mem_Account.IsBan, Mem_Account.AllowSocialLogin from Mem_Social, Mem_Account WHERE Mem_Social.SocialID = ? AND Mem_Social.Type = ? AND Mem_Social.UserID = Mem_Account.UserID", array($socialVar['ID'], $type), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			// check is have
			$accInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);
			
			if($accInfo['IsBan'] == false && $accInfo['AllowSocialLogin'] == true) {
				self::createSession($accInfo['UserID'], $accInfo['Email'], $accInfo['Password']);
				$returnVal = true;
			}

		} else {

			/*// kiem tra xem social nay lien ket voi tai khoan nao
			$socialInfo = self::getSocial($socialVar['ID'], $type);

			if($socialInfo) {

				// social nay da ket noi voi mot tai khoan nao do
				$userInfo = self:getUserInfo($socialInfo['UserID']);

				// mo ket noi voi social do

			}*/

			$qCheck2 = sqlsrv_query($conn, "select * from Mem_Account where Email = ?", array($socialVar['Email']), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

			if(sqlsrv_num_rows($qCheck2) > 0) {

				// ton tai
				$userInfo = sqlsrv_fetch_array($qCheck2, SQLSRV_FETCH_ASSOC);

				self::createSession($userInfo['UserID'], $userInfo['Email'], $userInfo['Password']);

				// kiem tra xem da khoi tao social chua
				self::createSocial($userInfo['UserID'], $type, $socialVar);
				
				$returnVal = true;

			} else {

				// khoi tao social moi
				$userId = self::create($socialVar['Email'], "");

				if($userId > 0) {

					self::createSocial($userId, $type, $socialVar);

					self::createSession($userId, $socialVar['Email'], "");
					
					$returnVal = true;

				}
			}

			sqlsrv_free_stmt($qCheck2);

		}

		sqlsrv_free_stmt($qCheck);
		
		return $returnVal;
	}

	public static function createSocial($userid, $type, $socialVar) {

		global $conn, $_config;

		$canCreate = false;


		if(!self::checkSocial($type, $socialVar)) {

			$createSocial = sqlsrv_query($conn, "insert into Mem_Social (UserID, Type, SocialID, SocialName, SocialEmail, TimeCreate, IPCreate) VALUES (?, ?, ?, ?, ?, ?, ?)", array($userid, $type, $socialVar['ID'], utf8_encode($socialVar['Name']), $socialVar['Email'], time(), self::getUserIP()));

			if($createSocial) {

				//self::createSession($userid, $socialVar['Email']);

				sqlsrv_free_stmt($createSocial);

				$canCreate = true;

			} else {
				//echo 'loi sql '.$socialVar['ID'].'-'.$socialVar['Email'].'-'.$socialVar['Name'];
				print_r(sqlsrv_errors());

				die();

			}

		}

		return $canCreate;
	}

	public static function create($email, $password, $phone) {

		global $conn, $_config;

		$userid = 0;

		$qCheck = sqlsrv_query($conn, "select * from Mem_Account where Email = ?", array($email), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) <= 0) {

			$qInsert = sqlsrv_query($conn, "insert into Mem_Account (Email, Password, Phone, TimeCreate, IPCreate, Money) VALUES (?, ?, ?, ?, ?, ?)", array($email, $password, $phone, time(), self::getUserIP(), 0));

			if($qInsert) {

				sqlsrv_free_stmt($qInsert);

				// get userid
				$qGet = sqlsrv_query($conn, "select UserID from Mem_Account where Email = ?", array($email), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

				$userInfo = sqlsrv_fetch_array($qGet, SQLSRV_FETCH_ASSOC);

				$userid = $userInfo['UserID'];

				//$this->userid = $userid;

				sqlsrv_free_stmt($qGet);

			} else {
				print_r(sqlsrv_errors());
				die();
			}
		}

		sqlsrv_free_stmt($qCheck);

		return $userid;
	}

	public static function getUserIP() {

	    if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
	        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
	            $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
	            return trim($addr[0]);
	        } else {
	            return $_SERVER['HTTP_X_FORWARDED_FOR'];
	        }
	    }
	    else {
	        return $_SERVER['REMOTE_ADDR'];
	    }

	}

	public static function getSocialConnect($userid, $name) {

		global $conn, $_config;

		$socialInfo = false;

		$qCheck = sqlsrv_query($conn, "select * from Mem_Social where UserID = ? AND Type = ?", array($userid, $name), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$socialInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

		}

		sqlsrv_free_stmt($qCheck);

		return $socialInfo;
	}

	public static function getUserInfo($userid) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Mem_Account where UserID = ?", array($userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		$qUpdateGC = sqlsrv_query($conn, "update Mem_Account set CountGC = (select Count(*) from Log_Card where UserID = ? and Status = 1) where UserID = ?", array($userid, $userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		$qUpdateItem = sqlsrv_query($conn, "update Mem_Account set CountItem = (select Count(*) from Mem_Bag where UserID = ? and IsSend='False') where UserID = ?", array($userid, $userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}

	public static function getUserInfoLogVIP($userid) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Log_VIP where UserID = ?", array($userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfoVIP = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfoVIP;

		} else {

			return false;

		}
	}
	public static function getUserInfoLogLevelVIP($txtVip, $userid) {

		global $conn, $_config;
		$qCheck = sqlsrv_query($conn, "select * from Log_VIP where Level = ? and UserID = ? ", array($txtVip, $userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfoVIP = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfoVIP;

		} else {

			return false;

		}
	}
	
	public static function getUserInfoByField($userid, $field) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select $field from Mem_Account where UserID = ?", array($userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo[$field];

		} else {

			return false;

		}
	}
	
	public static function getFullname($userid) {

		global $conn, $_config;
		
		$fullname = 'Unknown';

		$qCheck = sqlsrv_query($conn, "select Email, Fullname from Mem_Account where UserID = ?", array($userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);
			
			if($userInfo['Fullname'] == null) {
				
				$fullname = $userInfo['Email'];
				
			} else {
				
				$fullname = $userInfo['Fullname'];
				
			}
		}
		
		sqlsrv_free_stmt($qCheck);
		
		return $fullname;
	}
	
	public static function getUserInfoByEmail($email) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Mem_Account where Email = ?", array($email), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	public static function getUserInfoByUID($userid) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Mem_Account where UserID = ?", array($userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	public static function checkSeriInfo($email) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Log_Card where Email = ?", array($email), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$seriInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $seriInfo;

		} else {

			return false;

		}
	}
	
	public static function checkFullcard($passcard) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Log_Card where Passcard = ?", array($passcard), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$fullcard = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $fullcard;

		} else {

			return false;

		}
	}
	public static function checkEmailInfo($email) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Mem_Account where Email = ?", array($email), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$emailInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $emailInfo;

		} else {

			return false;

		}
	}
	public static function getPlayerInfo($username) {

		global $conn_sv, $_config;

		$qCheck = sqlsrv_query($conn_sv, "select * from Sys_Users_Detail where Username = ?", array($username), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) == 1) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	public static function getPlayerInfoLauncher($username , $serverid) {

		include_once(dirname(__FILE__)."/../class/function.global.php");
			$conn_sv = connectTank($serverid);
	
			if(!$conn_sv) {
				return;
			}

		$qCheck = sqlsrv_query($conn_sv, "select * from Sys_Users_Detail where Username = ?", array($username), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) == 1) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	
	public static function getPlayerInfoByUserID($userid) {

		global $conn_sv, $_config;

		$qCheck = sqlsrv_query($conn_sv, "select UserID, State, UserName, NickName, Money, Win from Sys_Users_Detail where UserID = ?", array($userid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	

	public static function getListPlayerInfo($username) {

		global $conn_sv, $_config;
		
		$listAccount = array();

		$qCheck = sqlsrv_query($conn_sv, "select UserID, State, UserName, NickName, Money, Win from Sys_Users_Detail where UserName = ? AND IsExist = ?", array($username, true), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			while($userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC)) {
				
				$listAccount[] = $userInfo;
				
			}

			sqlsrv_free_stmt($qCheck);

		}
		
		return $listAccount;
	}

	public static function xuWarPlus($userid) {

		global $conn, $_config;

		$userInfo = self::getUserInfo($userid);

		$plusAdd = 0;

		if($userInfo['VIPLevel'] != 0) {
			$plusAdd += 25;
		}
		if($userInfo['VIPLevel'] != 0 and $userInfo['VIPLevel'] != 1) {
			$plusAdd += 25;
		}
		if($userInfo['VIPLevel'] != 0 and $userInfo['VIPLevel'] != 1 and $userInfo['VIPLevel'] != 2) {
			$plusAdd += 25;
		}
		if($userInfo['VIPLevel'] != 0 and $userInfo['VIPLevel'] != 1 and $userInfo['VIPLevel'] != 2 and $userInfo['VIPLevel'] != 3) {
			$plusAdd += 25;
		}
		if($userInfo['VIPLevel'] != 0 and $userInfo['VIPLevel'] != 1 and $userInfo['VIPLevel'] != 2 and $userInfo['VIPLevel'] != 3  and $userInfo['VIPLevel'] != 4) {
			$plusAdd += 50;
		}			
		if($userInfo['Phone'] != null) {
			$plusAdd += 50;
		} else if($userInfo['Fullname'] != null) {
			$plusAdd += 30;
		}

		return $plusAdd;
	}

	public static function log($userid, $typeName, $typeCode, $content, $value) {

		global $conn, $_config;

		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];

		$qInsert = sqlsrv_query($conn, "insert into Mem_History (UserID, Type, TypeCode, Content, Value, TimeCreate, IPCreate) VALUES (?, ?, ?, ?, ?, ?, ?)", array($userid, $typeName, $typeCode, $content, $value, time(), self::getUserIP()));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);
	}
	
	public static function logCard($userid, $nameCard, $serial, $passcard, $money) {

		global $conn, $_config;

		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];

		$qInsert = sqlsrv_query($conn, "insert into Log_Card (UserID, Name, Serial, Passcard, Money, TimeCreate) VALUES (?, ?, ?, ?, ?, ?)", array($userid, $nameCard, $serial, $passcard, $money, date("H:i:s Y-m-d")));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);
	}
	public static function logCardATM($userid, $nameCard, $serial, $passcard, $money , $email, $status, $usernhan, $serverid) {

		global $conn, $_config;

		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];

		$qInsert = sqlsrv_query($conn, "insert into Log_Card (UserID, Name, Serial, Passcard, Money, TimeCreate, Email, Status, Usernhan, ServerID) VALUES (?, ?, ?, ?, ?, ?, ?, ? , ?, ?)", array($userid, $nameCard, $serial, $passcard, $money, date("H:i:s Y-m-d"), $email, $status, $usernhan, $serverid));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);
	}
	
	public static function logChuyenxu($email, $xuweb, $xugame) {

		global $conn, $_config;


		$qInsert = sqlsrv_query($conn, "insert into Log_Chuyenxu (Email, Xuweb, Xugame, TimeCreate) VALUES (?, ?, ?, ?)", array($email, $xuweb, $xugame, date("H:i:s Y-m-d")));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);
	}
	
	public static function updateLogCard($userid, $serial, $pin, $status, $new_money, $desc, $serverid) {
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Log_Card SET Status = ?, Money = ?, Response = ?, ServerID = ? WHERE UserID = ? AND Serial = ? AND Passcard = ?", array($status, $new_money, $desc, $serverid, $userid, $serial, $pin));

		if($qUpdate)
			sqlsrv_free_stmt($qUpdate);
	}
	public static function updateTinhtrangthe($status, $email) {
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Log_Card SET Status = ? WHERE Email = ?", array($status, $email));

		if($qUpdate)
			sqlsrv_free_stmt($qUpdate);
	}
	public static function updateIsCard($email,$pin) {
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Log_Card SET IsCard = 1 WHERE Email = ? and Passcard = ?", array($email, $pin));

		if($qUpdate)
			sqlsrv_free_stmt($qUpdate);
	}
		public static function updateTinhtrangtheseri($status, $email) {
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Log_Card SET Status = ? WHERE Passcard = ?", array($status, $email));

		if($qUpdate)
			sqlsrv_free_stmt($qUpdate);
	}
	public static function updateUsernhan($userid, $passcard, $usernhan) {
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Log_Card Set Usernhan = ? WHERE UserID = ? AND Passcard = ?", array($usernhan, $userid, $passcard));
		$qUpdate1 = sqlsrv_query($conn, "UPDATE Log_card
SET Log_card.Email = Mem_Account.Email
FROM
    Mem_Account
WHERE
    Log_card.UserID = Mem_Account.UserID");
		if($qUpdate1)
			sqlsrv_free_stmt($qUpdate1);
		if($qUpdate)
			sqlsrv_free_stmt($qUpdate);
	}
	public static function addCoin($userid, $money, $istotal = false) {
		
		global $conn, $_config;
		
		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];
		
		$addTotal = '';
		
		if($istotal)
			$addTotal = ',TotalMoney = TotalMoney + '.$money;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Mem_Account SET Money = Money + ?, MoneyEvent = MoneyEvent + ? $addTotal WHERE UserID = ?", array($money, $money, $userid));

		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
			
			$userInfo = self::getUserInfo($userid);
			
			if($userInfo['TotalMoney'] >= 200000) {
				// buff VIPLevel
				self::updateInfo($userid, 'VIPLevel', 1);
			}
			if($userInfo['TotalMoney'] >= 500000) {
				// buff VIPLevel
				self::updateInfo($userid, 'VIPLevel', 2);
			}
			if($userInfo['TotalMoney'] >= 1000000) {
				// buff VIPLevel
				self::updateInfo($userid, 'VIPLevel', 3);
			}	
			if($userInfo['TotalMoney'] >= 2000000) {
				// buff VIPLevel
				self::updateInfo($userid, 'VIPLevel', 4);
			}	
			if($userInfo['TotalMoney'] >= 4500000) {
				// buff VIPLevel
				self::updateInfo($userid, 'VIPLevel', 5);
			}	
			if($userInfo['TotalMoney'] >= 6000000) {
				// buff VIPLevel
				self::updateInfo($userid, 'VIPLevel', 6);
			}	
			return true;
		}
		return false;
	}
	public static function getItemInfo($itemid) {

		global $conn;

		$qCheck = sqlsrv_query($conn, "select TemplateID,Name from Shop_Goods where TemplateID = ?", array($itemid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	public static function getUserduyetthe($code) {

		global $conn;

		$qCheck = sqlsrv_query($conn, "select * from ChoNap where Giftcode = ?", array($code), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	public static function checkItemInfo($templateid) {

		global $conn, $_config;

		$qCheck = sqlsrv_query($conn, "select * from Webshop_Items where TemplateID = ?", array($templateid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$templateInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $templateInfo;

		} else {

			return false;

		}
	}
	public static function additemshop($templateid, $count, $price, $validdate, $isbind) {

		global $conn, $_config;

			


		$qInsert = sqlsrv_query($conn, "insert into Webshop_Items (UserID, ServerID, TemplateID, Count, Price, VaildDate, IsBind, MultiBuy, CountBuy) VALUES (?, ?, ?, ?, ?, ?, ?, ? , ?)", array(0, 0, $templateid, $count, $price, $validdate, $isbind, 0, 0));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);
	}
	public static function guilenhnap($userid, $email, $menhgia, $status,$giftcode, $serverid, $uid) {

		global $conn, $_config;

			


		$qInsert = sqlsrv_query($conn, "insert into ChoNap (UserID, Email, MenhGia, TimeCreate, Status, Giftcode, ServerID, UID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array($userid, $email, $menhgia, date("H:i:s Y-m-d"), $status, $giftcode, $serverid, $uid));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);
	}
	
	public static function updateshop() {

		global $conn, $_config;



		$qInsert = sqlsrv_query($conn, "Update Webshop_Items set MultiBuy = 1 where TemplateID in ( select TemplateID from Shop_Goods where MaxCount > 1 )");

		if($qInsert)
			sqlsrv_free_stmt($qInsert);
	}
	public static function removeCoin($userid, $money) {
		
		global $conn, $_config;
		
		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Mem_Account SET Money = Money - ? WHERE UserID = ?", array($money, $userid));
		
		if($qUpdate) {
			
			sqlsrv_free_stmt($qUpdate);
			
			return true;
			
		}
		return false;
	}
	public static function removeXUKM($userid, $money) {
		
		global $conn, $_config;
		
		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Mem_Account SET MoneyLock = MoneyLock - ? WHERE UserID = ?", array($money, $userid));
		
		if($qUpdate) {
			
			sqlsrv_free_stmt($qUpdate);
			
			return true;
			
		}
		return false;
	}
	public static function removeMoney($userid, $field, $money) {
		
		global $conn, $_config;
		
		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Mem_Account SET $field = $field - ? WHERE UserID = ?", array($money, $userid));
		
		if($qUpdate) {
			
			sqlsrv_free_stmt($qUpdate);
			
			return true;
			
		}
		return false;
	}
	
	public static function addNumberField($userid, $field, $value) {
		
		global $conn, $_config;
		
		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Mem_Account SET $field = $field + ? WHERE UserID = ?", array($value, $userid));
		
		if($qUpdate) {
			
			sqlsrv_free_stmt($qUpdate);
			
			return true;
			
		}
		return false;
	}
	
	public static function updateInfo($userid, $field, $value) {
		
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE Mem_Account SET $field = ? WHERE UserID = ?", array($value, $userid));
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}
	}

	public static function changePassword($userid, $newpassword) {

		global $conn, $_config;

		if($userid == 0)
			$userid = $_SESSION['ss_user_id'];

		$qInsert = sqlsrv_query($conn, "update Mem_Account SET Password = ? WHERE UserID = ?", array($newpassword, $userid));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);

	}
	public static function updateInfoadmin($email, $password, $xugame, $xuweb, $isban) {

		global $conn, $_config;


		$qInsert = sqlsrv_query($conn, "update Mem_Account SET Password = ?, Money = ?, MoneyLock = ?, IsBan = ? WHERE Email = ?", array($password, $xuweb, $xugame, $isban, $email));

		if($qInsert)
			sqlsrv_free_stmt($qInsert);

	}
	public static function chargeMoney($playerInfo, $money, $serverid) {
		
		//global $conn_sv;
		$conn_sv = connectTank($serverid);
		$val = false;
		
		$results = sqlsrv_query($conn_sv,"SELECT COUNT(*) as total FROM Charge_Money", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
		if(sqlsrv_num_rows($results) > 0) {
			$data = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC);
			$ChargeID = md5($data['total']);
			
			$val = sqlsrv_query($conn_sv, "INSERT INTO Charge_Money
				([ChargeID]
				,[UserName]
				,[Money]
				,[CanUse]
				,[PayWay]
				,[NeedMoney]
				,[NickName])
			VALUES
				(?
				,?
				,?
				,?
				,?
				,?
				,?)", array($ChargeID, $playerInfo['UserName'], $money,1,0,0,$playerInfo['NickName']));

			
		}
		sqlsrv_free_stmt($results);
		return $val;
	}

	public static function getListPlayerInfos($username, $serverid) {

		$conn_sv = connectTank($serverid);
		
		$listAccount = array();

		$qCheck = sqlsrv_query($conn_sv, "select UserID, State, UserName, NickName, Money, Win from Sys_Users_Detail where UserName = ? AND IsExist = ?", array($username, true), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			while($userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC)) {
				
				$listAccount[] = $userInfo;
				
			}

			sqlsrv_free_stmt($qCheck);

		}
		
		return $listAccount;
	}
	
	public static function getCreateusernameInfo($username) {

		global $conn_sv;

		$qCheck = sqlsrv_query($conn_sv, "select * from Sys_Users_Detail where UserName = ?", array($username), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

		if(sqlsrv_num_rows($qCheck) > 0) {

			$userInfo = sqlsrv_fetch_array($qCheck, SQLSRV_FETCH_ASSOC);

			sqlsrv_free_stmt($qCheck);

			return $userInfo;

		} else {

			return false;

		}
	}
	public static function updateDuyetnap($status, $giftcode) {
		//Update nạp lần đầu
		global $conn, $_config;
		
		//$qInsert = sqlsrv_query($conn, "UPDATE NapLanDau_Data SET TotalMoney += ? where UserID = ?", array($money, $playerInfo['UserID']), array("Scrollable" => SQLSRV_CURSOR_KEYSET));		
		$qUpdate = sqlsrv_query($conn, "UPDATE ChoNap SET Status = ? where Giftcode = ?", array($status, $giftcode), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}

		
	}
	public static function updateNaplandau($money, $playerInfo) {
		//Update nạp lần đầu
		global $conn, $_config;
		
		//$qInsert = sqlsrv_query($conn, "UPDATE NapLanDau_Data SET TotalMoney += ? where UserID = ?", array($money, $playerInfo['UserID']), array("Scrollable" => SQLSRV_CURSOR_KEYSET));		
		$qUpdate = sqlsrv_query($conn, "UPDATE NapLanDau_data SET TotalMoney += ? where UserID = ?", array($money, $playerInfo), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}

		
	}
	public static function insertNaplandau($userid) {
		//Update nạp lần đầu
		global $conn, $_config;
		
		$qInsert = sqlsrv_query($conn, "INSERT INTO NapLanDau_data (UserID, TotalMoney, IsFinish) VALUES (?, ?, ?)", array($userid, 0 ,'False'), array("Scrollable" => SQLSRV_CURSOR_KEYSET));		
		
		if($qInsert) {
			sqlsrv_free_stmt($qInsert);
		}

		
	}
		public static function setNaplandau($playerInfo) {
		//Set đã kết thúc nạp lần đầu
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE NapLanDau_data SET IsFinish = 'True' where UserID = ?", array($playerInfo), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}

		
	}
	public static function CheckQuamosv($playerInfo) {
		
		global $conn_sv;
		
		$val = false;
		
		$results = sqlsrv_query($conn_sv,"SELECT ActiveType FROM Sys_Users_EventProcess where ActiveType = 4 and UserID = ?", array($playerInfo['UserID']));
		if(sqlsrv_num_rows($results) != 4) {
			$data = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC);
			
			$val = sqlsrv_query($conn_sv, "INSERT INTO Sys_Users_EventProcess
				([UserID]
				,[ActiveType]
				,[Conditions]
				,[AwardGot])
			VALUES
				(?
				,4
				,0
				,0)", array($playerInfo['UserID']));
				
			
		}
		
		sqlsrv_free_stmt($results);
		
		return $val;
	}
	
	// public static function Updatenaptichluy($money, $playerInfo) {
		
		// global $conn_sv;
		
		// $qUpdate = sqlsrv_query($conn_sv, "UPDATE Sys_Users_EventProcess SET Conditions = Conditions + ? WHERE UserID = ? and ActiveType=4", array($money, $playerInfo['UserID']));
		
		// if($qUpdate) {
			// sqlsrv_free_stmt($qUpdate);
		// }
		
	// }
	public static function CheckBXH($playerInfo) {
		
		global $conn, $_config;
		
		$val = false;
		
		$results = sqlsrv_query($conn,"SELECT ActiveType FROM BXH where ActiveType = 4 and UserID = ?", array($playerInfo['UserID']));
		if(sqlsrv_num_rows($results) != 4) {
			$data = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC);
			
			$val = sqlsrv_query($conn, "INSERT INTO BXH
				([UserID]
				,[ActiveType]
				,[Conditions]
				,[NickName])
			VALUES
				(?
				,4
				,0
				,?)", array($playerInfo['UserID'],$playerInfo['NickName']));
				
			
		}
		
		sqlsrv_free_stmt($results);
		
		return $val;
	}
	public static function BXH($money, $playerInfo) {
		
		global $conn, $_config;
		
		$qUpdate = sqlsrv_query($conn, "UPDATE BXH SET Conditions = Conditions + ? WHERE UserID = ?", array($money, $playerInfo['UserID']), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}

		
	}
	public static function Deleteketguild($email, $serverid) {
			include_once(dirname(__FILE__)."/../class/function.global.php");
			$conn_sv = connectTank($serverid);
	
			if(!$conn_sv) {
				return;
			}
		
		$qUpdate = sqlsrv_query($conn_sv, "DELETE FROM Sys_Users_Goods WHERE UserID in (select UserID from Sys_Users_Detail where UserName = ? ) and BagType = 11", array($email));

		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
			
		}
		
	}
	public static function GuiItemNap($templateid, $playerInfo) {
		
		global $conn_sv;
		//@$data->query("execute Webshop_giftcode '$idvp','$userid','$username','$hopthanh','$hopthanh','$hopthanh','$hopthanh','$StrengthenLevel','$soluong','$isbinds'");
	function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
	}
		$qUpdate = sqlsrv_query($conn_sv, "execute Cedrus_Naptichluy ?, ?, ?,'0','0','0','0','0','1','1'", array($templateid, $playerInfo['UserID'], $playerInfo['UserName']));
		$url='http://gunlove.net/kichthu/WebForm1.aspx?uid='.$playerInfo['UserID'].'&sid=1';
		
		get_data($url);					
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}
	}
	public static function ThongbaoNapthe($playerInfo, $money) {
		
		global $conn_sv;
		
		$qUpdate = sqlsrv_query($conn_sv, "INSERT INTO User_Messages (SenderID,Sender,ReceiverID,Receiver,Title,Content,Type,money) VALUES (0,'Administrator',?,?,N'Chuyển Xu Thành Công', N'Chúc mừng bạn đã chuyển $money xu thành công tại gunlove.net ! Chúc bạn chơi game vui vẻ !',51, ?)", array($playerInfo['UserID'], $playerInfo['NickName'], 0));

		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}
	}
	public static function ThongbaoXU($playerInfo, $money) {
		
		global $conn_sv;
		#hàm chạy link tự động
	function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
	}
		$qUpdate = sqlsrv_query($conn_sv, "INSERT INTO User_Messages (SenderID,Sender,ReceiverID,Receiver,Title,Content,Type,money) VALUES (0,'Administrator',?,?,N'Xu Nạp', N'Chúc mừng bạn đã chuyển $money xu thành công tại gunlove.net ! Chúc bạn chơi game vui vẻ !',51, ?)", array($playerInfo['UserID'], $playerInfo['NickName'], $money));
		$url='http://gunlove.net/kichthu/WebForm1.aspx?uid='.$playerInfo['UserID'].'&sid=1';
		
		get_data($url);					
		
		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}
	}		
	public static function ThongbaoXUKM($playerInfo, $money) {
		
		global $conn_sv;
		#hàm chạy link tự động
	function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
	}
		$qUpdate = sqlsrv_query($conn_sv, "INSERT INTO User_Messages (SenderID,Sender,ReceiverID,Receiver,Title,Content,Type,money) VALUES (0,'Administrator',?,?,N'Xu Game', N'Chúc mừng bạn đã chuyển $money xu game thành công tại gunlove.net ! Chúc bạn chơi game vui vẻ !',51, ?)", array($playerInfo['UserID'], $playerInfo['NickName'], $money));
		$url='http://gunlove.net/kichthu/WebForm1.aspx?uid='.$playerInfo['UserID'].'&sid=1';
		get_data($url);		

		if($qUpdate) {
			sqlsrv_free_stmt($qUpdate);
		}
	}		
	public static function sendMail($conn_sv, $playerid, $title, $content, $itemArray) {
		
		global $_config;
		
		$result = false;
		
		if(count($itemArray) > 0) {
			
			for($i = 0; $i < count($itemArray); $i += 5) {
				
				
				$mail =  new MailInfo(getDateTime('now'));
				$mail->Sender = "Webshop - ";
				$mail->Title = $title;
				$mail->Content = $content;
				$mail->ReceiverID = $playerid;
				
				for($j = 0; $j < 5; $j++) {
					if($itemArray[$i + $j] != null) {
						switch($j) {
							case 0:
							$mail->Annex1 = $itemArray[$i + $j]->ItemID;
							break;
							case 1:
							$mail->Annex2 = $itemArray[$i + $j]->ItemID;
							break;
							case 2:
							$mail->Annex3 = $itemArray[$i + $j]->ItemID;
							break;
							case 3:
							$mail->Annex4 = $itemArray[$i + $j]->ItemID;
							break;
							case 4:
							$mail->Annex5 = $itemArray[$i + $j]->ItemID;
							break;
						}
					} else {
						break;
					}
				}
				
				$insertMail = sqlsrv_query($conn_sv, $mail->GetQuery(), $mail->GetValues(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
				
				if($insertMail)
					sqlsrv_free_stmt($insertMail);
			}
			$result = true;
		}
		
		return $result;
	}
	
	public static function addItemBag($userid, $serverid, $templateid, $count, $isbind, $vailddate, $ismerge) {
		
		global $conn, $_config;
		
		$isDone = false;
		
		$isSuccess = false;
		
		if($ismerge) {
			// find another item not sending
			$qFind = sqlsrv_query($conn, "select TOP 1 * from Mem_Bag WHERE UserID = ? AND IsBind = ? AND TemplateID = ? AND IsSend = ? AND ServerID IN (0, ?)", array($userid, $isbind, $templateid, false, $serverid), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
			
			if(sqlsrv_num_rows($qFind) > 0) {
				// have another can merge
				$itemInfo = sqlsrv_fetch_array($qFind, SQLSRV_FETCH_ASSOC);
				
				$isSuccess = sqlsrv_query($conn, "update Mem_Bag SET Count = Count + ? WHERE ItemID = ?", array($count, $itemInfo['ItemID']), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
				
				$isDone = true;
			}
		}
		
		if(!$ismerge || !$isDone) {
			$isSuccess = sqlsrv_query($conn, "INSERT INTO Mem_Bag (UserID, TemplateID, Count, StrengthenLevel, IsBind, VaildDate, IsSend, ServerID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array($userid, $templateid, $count, 0, $isbind, $vailddate, false, $serverid));
		}
		
		return $isSuccess;
	}
	
	public static function getEventInfo($userid, $eventid, $serverid) {
		
		global $conn, $_config;
		
		$qFind = sqlsrv_query($conn, "select * from Mem_EventProcess WHERE UserID = ? AND EventID = ? AND ServerID = ?", array($userid, $eventid, $serverid), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
			
		if(sqlsrv_num_rows($qFind) > 0) {
			
			return sqlsrv_fetch_array($qFind, SQLSRV_FETCH_ASSOC);
			
		}
		
		return false;
	}
	
	public static function createKeyLauncher($userid, $email) {
		
		global $conn, $_config;
		
		$key = md5(rand(0, 99999));
		
		// delete all old key
		$qDelete = sqlsrv_query($conn, "delete from Mem_Launcher where UserID = ? AND Email = ?", array($userid, $email));
		
		sqlsrv_free_stmt($qDelete);
		
		$qCreate = sqlsrv_query($conn, "insert into Mem_Launcher (UserID, Email, KeyVerify, TimeCreate) VALUES (?, ?, ?, ?)", array($userid, $email, $key, time()));
		
		sqlsrv_free_stmt($qCreate);
		
		return $key;
	}
	
	public static function getKeyLauncher($email, $key) {
		
		global $conn, $_config;
		
		$qFind = sqlsrv_query($conn, "select * from Mem_Launcher WHERE Email = ? AND KeyVerify = ?", array($email, $key), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
			
		if(sqlsrv_num_rows($qFind) > 0) {
			
			return sqlsrv_fetch_array($qFind, SQLSRV_FETCH_ASSOC);
			
		}
		
		return false;
		
	}
	
	public static function updateCoinGame($userid, $serverid, $value, $isplus) {
		
		global $conn, $_config;
		
		$qFind = sqlsrv_query($conn, "select * from Mem_CoinGame WHERE UserID = ? AND ServerID = ?", array($userid, $serverid), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
			
		if(sqlsrv_num_rows($qFind) > 0) {
			
			//return sqlsrv_fetch_array($qFind, SQLSRV_FETCH_ASSOC);
			
			// update
			if($isplus) {
				
				$plusAdd = "Value + ?";
				
			} else {
				
				$plusAdd = "Value - ?";
				
			}
			
			$qUp = sqlsrv_query($conn, "UPDATE Mem_CoinGame SET Value = $plusAdd WHERE UserID = ? AND ServerID = ?", array($value, $userid, $serverid));
			
		} else {
			
			$qAdd = sqlsrv_query($conn, "INSERT INTO Mem_CoinGame (UserID, ServerID, Value) VALUES (?, ?, ?)", array($userid, $serverid, $value));
			
		}
		
		return true;
	}
	
	public static function getCoinGame($userid, $serverid) {
		
		global $conn, $_config;
		
		$qFind = sqlsrv_query($conn, "select * from Mem_CoinGame WHERE UserID = ? AND ServerID = ?", array($userid, $serverid), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
			
		if(sqlsrv_num_rows($qFind) > 0) {
			
			$cgInfo = sqlsrv_fetch_array($qFind, SQLSRV_FETCH_ASSOC);
			
			return $cgInfo['Value'];
		}
		
		return 0;
	}
}
?>