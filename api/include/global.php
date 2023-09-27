<?php
if(!defined('Tu4nMR@PRoKAK4')) die('Fuck You');

# class ket noi csdl
class webshopv3 {
	# khai bao thong tin ket noi
	private $dbhostdata;
	private $dbuserdata;
	private $dbpassdata;
	private $dbdatatank;
	private $dbdatamemb;
	private $queryResult;
	private $conn;
	private $conn_memb;

	public function __construct($config) {
		$this->dbhostdata = $config['dbhostdata'];
		$this->dbuserdata = $config['dbuserdata'];
		$this->dbpassdata = $config['dbpassdata'];
		$this->dbdatatank = $config['dbdatatank'];
		$this->dbdatamemb = $config['dbdatamemb'];
		$this->sqlsrv();
		$this->sqlsrv_mem();
    }
	# Ham ket noi sqlsrv
	function sqlsrv() {
		
		
		$this->conn = sqlsrv_connect($this->dbhostdata, array("Database"=>$this->dbdatatank,"UID"=>$this->dbuserdata,"PWD"=>$this->dbpassdata,"CharacterSet" => "UTF-8"));
	}
	function sqlsrv_mem() {
		#Ket noi den database db_membership
		$this->conn_memb = sqlsrv_connect($this->dbhostdata, array("Database"=>$this->dbdatamemb,"UID"=>$this->dbuserdata,"PWD"=>$this->dbpassdata,"CharacterSet" => "UTF-8"));
		return $this->conn_memb;
	}
	# Ham thuc thi query
	public function query($query,$type = '') {
		if($type == 'mem') {
			$this->queryResult = sqlsrv_query($this->conn_memb,$query,array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
			return $this->queryResult;
		} else {
			$this->queryResult = sqlsrv_query($this->conn,$query,array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
			return $this->queryResult;
		}
	}
	
	
		# Ham thuc thi query voi parameter
	public function queryWithParam($query,$paramters) {
			$this->queryResult = sqlsrv_query($this->conn_memb,$query,$paramters,array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
			return $this->queryResult;
	}
	
	# Ham dem so dong tra ve
	public function query_num($query = NULL) {
		if($query == NULL){
            if (is_resource($this->queryResult))
                return sqlsrv_num_rows($this->queryResult);
        }
        return sqlsrv_num_rows($query);
	}
	# Ham dua du lieu vao mang
	public function query_array($query = NULL) {
        if($query == NULL) {
            if (is_resource($this->queryResult))
               return sqlsrv_fetch_array($this->queryResult,SQLSRV_FETCH_ASSOC);
        }
		return sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC);
    }
    public function update_top() {
    	$top = $this->query("SELECT TOP 10 NickName,FightPower FROM Sys_Users_Detail WHERE IsExist = 'True' ORDER by FightPower DESC");
		$Dem = 1;
		echo  '
		<table height="15" cellpadding="0" cellspacing="0" width="98%">
            <tr>
			
            <th width="43%" height="29"><font size="2" color="FF0000">Tên nhân vật</th>
            <th width="57%"><font size="2" color="FF0000">Lực chiến</th>
            </tr>
		</table>
        ';

 		while($result = $this->query_array($top)) {
     		echo  '
			<table height="15" cellpadding="0" cellspacing="0" width="98%">
            <th width="43%" height="25"><font size="2" color="blue">'.$result['NickName'].'</font></th>
            <th width="57%"><font size="2" color="blue">'.number_format((float)$result['FightPower']).'</font></th></table>
			';
	 	++$Dem;}
    }
} # Ket thuc class webshopv3
#get ip
function getIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))  //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   
    //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
#get uid
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
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
# Ham them user moi
function addnewusernick($username,$password,$ip,$server,$passcap2,$nickname,$sex) {
	global $data;
	$ketqua = null;
	$q = $data->query("SELECT * FROM Ws_username WHERE UserName= '$username'",'mem');
	if($data->query_num($q) <> 0)
	$ketqua.= 'Tên tài khoản đã được sử dụng<br>';
	if($ketqua <> null) return $ketqua;
	else {
		$password2 = strtoupper(md5($password));
		$passtow = strtoupper(md5($passcap2));
		#Thông tin acc mới
		$xu_game=1000;
		$coin_star=0;
		$xu_khoa=0;
		$vang=1000000;
		$kinhnghiem=3490849; // Level 30
		$data->query("execute Mem_Users_CreateUser 'DanDanTang','$username','$password2','0','1','MD5','false'",'mem');
		$data->query("INSERT INTO Ws_username (UserName,Password,ip,server,passtow,diemthuong) VALUES ('$username','$password2','$ip','$server','$passtow','$coin_star')",'mem');
		$data->query("execute SP_Account_Register '$username','$password',N'$nickname','$ip','$sex','$xu_game','$vang','$kinhnghiem','$xu_khoa'");
		return 1;
	}
}
#chống lỗi SQL
 function cleanup_text($data)
        {
                        //Khai báo biến cục bộ.
            global $connect;
            if(ini_get('magic_quotes_gpc'))
            {
                $data= stripslashes($data);
            }
            return mysql_real_escape_string($data,$connect);
        }
#

function loadgoxu($id) {
	global $data;
	$q=@$data->query("SELECT GoXu,NickName,Money,TichLuy FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['GoXu'];
	}
}

function loadBagType($id) {
	global $data;
	$q=@$data->query("SELECT ItemID,UserID,BagType FROM Sys_Users_Detail WHERE UserID ='$id',BagType='$BagType'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['BagType'];
	}
}
#load nick name
function loadnickname($user) {
	global $data;
	$q=@$data->query("SELECT NickName FROM Sys_Users_Detail WHERE UserName ='$user'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['NickName'];
	}
}
#load userid
function loaduid($user) {
	global $data;
	$q=@$data->query("SELECT userid FROM Sys_Users_Detail WHERE UserName ='$user'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['userid'];
	}
}
#Load online
function loadonl($id) {
	global $data;
	$q=@$data->query("SELECT GoXu FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['GoXu'];
	}
}
#Load lực chiến
function loadlucchien($user) {
	global $data;
	$q=@$data->query("SELECT FightPower FROM Sys_Users_Detail WHERE username ='$user'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['FightPower'];
	}
}
#Load level
function loadlevel2($id) {
	global $data;
	$q=@$data->query("SELECT Grade FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['Grade'];
	}
}

function loadleve_f($user) {
	global $data;
	$q=@$data->query("SELECT TOP 100 Money FROM Log_Card WHERE UserName ='$user' order by Money DESC");
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['Money'];
	}
}
#Load Giới tính
function gioitinh($id) {
	global $data;
	$q=@$data->query("SELECT sex FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['sex'];
	}
}
#Load level
function kethon($id) {
	global $data;
	$q=@$data->query("SELECT SpouseID FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['SpouseID'];
	}
}
#Load Xu khóa
function loadxukhoa($id) {
	global $data;
	$q=@$data->query("SELECT GiftToken FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['GiftToken'];
	}
}
#Load Cash
function loadC($id) {
	global $data;
	$q=@$data->query("SELECT TichLuy FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['TichLuy'];
	}
}
#Load mem vuo
function loadbaotri($id) {
	global $data;
	$q=@$data->query("Select baotri from ws_bao_tri where ID='$id'","mem");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['baotri'];
	}
}
# Ham load xu user
function loadx($id) {
	global $data;
	$q=@$data->query("SELECT GoXu,NickName,Money FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['Money'];
	}
}
# Ham check online
function checkonline($id) {
	global $data;
	$q=@$data->query("SELECT State FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['State'];
	}
}
# Ham check bagetype
function checkBagType($BagType) {
	global $data;
	$q=@$data->query("SELECT State FROM Sys_Users_Goods WHERE BagType ='$BagType'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['BagType'];
	}
}
# Ham load xu user
function loadviptl($id) {
	global $data;
	$q=@$data->query("SELECT vip FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['vip'];
	}
}
function loadwin($id) {
	global $data;
	$q=@$data->query("SELECT win FROM Sys_Users_Detail WHERE UserID ='$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['win'];
	}
}
# Ham load cash user
function loadcash($id) {
	global $data;
	$q=@$data->query("SELECT Cash FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['Cash'];
	}
}
# Ham load tiền ứng
function loadtien($id) {
	global $data;
	$q=@$data->query("SELECT tien_ung FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['tien_ung'];
	}
}
# Ham load cash user
function loadbanacc($id) {
	global $data;
	$q=@$data->query("SELECT banacc FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['banacc'];
	}
}
function loadvaycoin($id) {
	global $data;
	$q=@$data->query("SELECT Vaycoin FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['Vaycoin'];
	}
}
function loadvquaytichluy($id) {
	global $data;
	$q=@$data->query("SELECT vquaytichluy FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['vquaytichluy'];
	}
}
function loadvquayvip($id) {
	global $data;
	$q=@$data->query("SELECT vquayvip FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['vquayvip'];
	}
}
#Load điểm
function loaddiemthuong($id) {
	global $data;
	$q=@$data->query("SELECT DiemThuong FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['DiemThuong'];
	}
}
#Load Khuyến mại
function loadkhuyenmai($id) {
	global $data;
	$q=@$data->query("SELECT Khuyenmai FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['Khuyenmai'];
	}
}
# Ham load xuweb user
function loadxuweb($id) {
	global $data;
	$q=@$data->query("SELECT xuweb FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['xuweb'];
	}
}
# Ham load cường hóa
function loadcuonghoa($id) {
	global $data;
	$q=@$data->query("SELECT Cuonghoa FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['Cuonghoa'];
	}
}
# Ham load bird user
function loadbird($id) {
	global $data;
	$q=@$data->query("SELECT bird FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['bird'];
	}
}
function loadcashdaugia($id) {
	global $data;
	$q=@$data->query("SELECT cashdaugia FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['cashdaugia'];
	}
}
# Ham load hũ vàng
function loadhuvang($id) {
	global $data;
	$q=@$data->query("SELECT huvang FROM ws_loghuvang where stt='$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['huvang'];
	}
}
# Ham load tichluy user
function loadtichluy($id) {
	global $data;
	$q=@$data->query("SELECT point FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['point'];
	}
}
# Khóa tk
function loadblock($id) {
	global $data;
	$q=@$data->query("SELECT block FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return '0';
	} else {
		$obj = @$data->query_array($q);
		return $obj['block'];
	}
}
function loadajax($id) {
	global $data;
	$q=@$data->query("SELECT * FROM shop_item_coin Where CategoryID='$id'");
	return $q;
}
# Ham load vòng quay user
function loadvongquay($id) {
	global $data;
	$q=@$data->query("SELECT quaybox FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['quaybox'];
	}
}
# Ham load vòng quay khóa user
function loadvongquaykhoa($id) {
	global $data;
	$q=@$data->query("SELECT vquaykhoa FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['vquaykhoa'];
	}
}
# Ham load tiền mua acc
function vnd($id) {
	global $data;
	$q=@$data->query("SELECT vnd FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['vnd'];
	}
}
# Ham load vòng quay khóa user
function loadlucky($id) {
	global $data;
	$q=@$data->query("SELECT lucky FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['lucky'];
	}
}
# Ham load ott user
function loadott($id) {
	global $data;
	$q=@$data->query("SELECT ott FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['ott'];
	}
}
# Ham load kichhoat user
function loadkichhoat($id) {
	global $data;
	$q=@$data->query("SELECT kichhoat FROM ws_username WHERE UserID = '$id'",'mem');
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['kichhoat'];
	}
}
# Ham load vip user
function loadvip($id) {
	global $data;
	$q=@$data->query("SELECT capvip FROM ws_logvip WHERE username = '$id'");
	if(@$data->query_num($q) == 0) {
		return 'Null';
	} else {
		$obj = @$data->query_array($q);
		return $obj['capvip'];
	}
}
# Ham tieu phi xu
function loadxu($id) {
	global $data;
	$shopthanh = @$data->query("SELECT Cash FROM Log_HopThanh Where UserID = '$id'");
	$scuonghoa = @$data->query("SELECT CashPay FROM Log_CuongHoa Where UserID = '$id'");
	$sshop = @$data->query("SELECT Cash FROM Log_Shop Where UserID = '$id'");
	$sdoiten = @$data->query("SELECT Cash FROM Log_DoiTen Where UserID = '$id'");
	
	$hopthanh = 0;
	$cuonghoa = 0;
	$shop = 0;
	$doiten = 0;
	while($tonghopthanh = @$data->query_array($shopthanh)) {
	    $hopthanh += $tonghopthanh['Cash'];
	}
	while($tongcuonghoa = @$data->query_array($scuonghoa)) {
	    $cuonghoa += $tongcuonghoa['CashPay'];
	}
	while($tongshop = @$data->query_array($sshop)) {
	    $shop += $tongshop['Cash'];
	}
	while($tongdoiten = @$data->query_array($sdoiten)) {
	    $doiten += $tongdoiten['Cash'];
	}
	$TongTienSuDung = $hopthanh + $cuonghoa + $shop + $doiten;
	return $TongTienSuDung;
}
#
function loaduser($userid) {
	global $data;
	$q = @$data->query("SELECT UserName FROM ws_username WHERE UserId = '$userid'");
	if(@$data->query_num($q) <> 0) {
		$info = @$data->query_array($q);
		return $info['UserName'];
	}
}
function loadphone($userid) {
	global $data;
	$q = @$data->query("SELECT Phone FROM ws_username WHERE UserId = '$userid'",'mem');
	if(@$data->query_num($q) <> 0) {
		$info = @$data->query_array($q);
		return $info['Phone'];
	}
} 
# Load gia vat pham
function loadprice($id) {
	global $data;
	$q = @$data->query("SELECT Price FROM shop_item_coin WHERE id = '$id'",'mem');
	if(@$data->query_num($q) <> 0) {
		$info = @$data->query_array($q);
		return $info['Price'];
	}
}
# Load gia xu vat pham
function loadpricexu($id) {
	global $data;
	$q = @$data->query("SELECT xu FROM shop_item_vip WHERE id = '$id'",'mem');
	if(@$data->query_num($q) <> 0) {
		$info = @$data->query_array($q);
		return $info['xu'];
	}
}
# Load gia vat pham tichluy
function loadpricetichluy($id) {
	global $data;
	$q = @$data->query("SELECT Price FROM shop_item_vip WHERE id = '$id'",'mem');
	if(@$data->query_num($q) <> 0) {
		$info = @$data->query_array($q);
		return $info['Price'];
	}
}
# Ham load thong tin vat pham
function infoItem($id) {
	global $data;
	$q = @$data->query("SELECT TemplateID, Name,Pic,Property7,Property8,CategoryID,NeedSex,Attack,Defence,Agility,Luck,MaxCount,Description FROM Shop_Goods WHERE TemplateID = '$id'");
	if(@$data->query_num($q) <> 0) {
		$info = @$data->query_array($q);
		return $info;
	}
}
# Ham chuyen trang
function chuyentrang($link) {
	echo '<script type="text/javascript">window.location="'.$link.'";</script>';
}
# Ham thong bao
function thongbao($text) {
	echo '<script type="text/javascript">alert("'.$text.'");</script>';
}
function canhbao($text) {
	echo '<script>// <![CDATA[
smoke.alert("'.$text.'", function(e){
	
}, {
	ok: "Đồng ý",
	cancel: "Nope",
	classname: "custom-class"
});
// ]]></script>
';
}
# Ham load hinh anh vat pham
function loadimage($image,$loaivp,$sex) {
	# Xem gioi tinh
	switch($sex) {
		case 1:
		$ml = 'm';
		break;
		case 2:
		$ml = 'f';
		break;
		default:
		$ml = 'f';
		break;
	}
	switch($loaivp) {
		case 1:
		$link = 'equip/'.$ml.'/head/'.$image.'/icon_1.png';
		break;
		case 2:
		$link = 'equip/'.$ml.'/glass/'.$image.'/icon_1.png';
		break;
		case 3:
		$link = 'equip/'.$ml.'/hair/'.$image.'/icon_1.png';
		break;
		case 4:
		$link = 'equip/'.$ml.'/eff/'.$image.'/icon_1.png';
		break;
		case 5:
		$link = 'equip/'.$ml.'/cloth/'.$image.'/icon_1.png';
		break;
		case 6:
		$link = 'equip/'.$ml.'/face/'.$image.'/icon_1.png';
		break;
		case 7:
		$link = 'arm/'.$image.'/00.png';
		break;
		case 8:
		$link = 'equip/armlet/'.$image.'/icon.png';
		break;
		case 9:
		$link = 'equip/ring/'.$image.'/icon.png';
		break;
		case 11:
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		case 12:
		$link = 'task/'.$image.'/icon.png';
		break;
		case 13:
		$link = 'equip/'.$ml.'/suits/'.$image.'/icon_1.png';
		break;
		case 15:
		$link = 'equip/wing/'.$image.'/icon.png';
		break;
		case 14:
		$link = 'equip/necklace/'.$image.'/icon.png';
		break;
		case 16:
		$link = 'specialprop/chatBall/'.$image.'/icon.png';
		break;
		case 17;
		$link = 'equip/offhand/'.$image.'/icon.png';
		break;
		case 18;
		$link = 'cardbox/'.$image.'/icon.png';
		break;case 19;
		$link = 'prop/'.$image.'/icon.png';
		break;
		case 20;
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		case 30;
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		case 32;
		$link = 'farm/crops/'.$image.'/seed.png';
		break;
		case 34;
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		case 35;
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		case 40;
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		case 50;
		$link = 'petequip/arm/'.$image.'/icon.png';
		break;
		case 27;
		$link = 'arm/'.$image.'/00.png';
		break;
		case 52;
		$link = 'petequip/cloth/'.$image.'/icon.png';
		break;
		case 51;
		$link = 'petequip/hat/'.$image.'/icon.png';
		break;
		case 61:
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		case 62:
		$link = 'unfrightprop/'.$image.'/icon.png';
		break;
		default:
		$link = NULL;
		break;
	}
	return $link;
} # Ket thuc ham loadimgae
function loadimagepet($image) {
	$link = 'pet/'.$image.'/icon1.png';
	return $link;
}

#Launcher
function connectTank($serverid) {
	
	global $conn;
	
	$check = sqlsrv_query($conn, "select * from Server_List WHERE ServerID = ?" , array($serverid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));
	
	if(sqlsrv_num_rows($check) > 0) {
		
		$svInfo = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);
		
		//$connectionInfo = array("Database" => $config['dbdatamemb'], "UID" => $config['dbuserdata'], "PWD" => $config['dbpassdata'], "CharacterSet" => "UTF-8");

		$conn_sv = sqlsrv_connect($svInfo['Host'], $sqlsrv_mem);
		
	}
	
	sqlsrv_free_stmt($check);
	
	return $conn_sv;
}

function checkuserValid($mail, $pass)
{
	global $conn;
	
	$ishave = false;

	$qCheck = sqlsrv_query($conn, "select * from Ws_Username WHERE Email = ? AND Password = ? AND Block = ?", array($mail, $pass, 0), array( "Scrollable" => SQLSRV_CURSOR_KEYSET));

	if(sqlsrv_num_rows($qCheck) > 0) {
		$ishave = true;
	}
	
	sqlsrv_free_stmt($qCheck);

	return $ishave;
}
?>