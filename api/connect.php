<?php
if(!defined('Tu4nMR@PRoKAK4')) die('Fuck You');
include('SQL_jni.php');
# Khai bao cac duong link
define('linkserver', './');
define('nameweb', 'GUN36');
define('login', 'http://gunae.click/');
define('link_forum', 'https://www.facebook.com/swatducsine');
define('link_trangchu','./');
define('link_resource','http://resstagging.gn.zing.vn/image/');
define('link_resource1','http://resstagging.gn.zing.vn/image/');
define('link_sever1','./');
define('link_taonv', './');
define('rename', 100000); # phí đổi tên
define('server', 1);
define('linkDownloadLauncher', 'http://gunae.click/Launcher.rar');

$tieude='Server GUN';
$link_game='Play';
$link_game2='Play';
$link_game3='Play';
# Chỉnh giá chức năng



$gia_phu_kien =10000; // điểm
$gia_phu_kien_all =500000;
$hack =0;
# Mới 
$max_hop_thanh=999;
$gia_doi_gioi=50000;
$min_hop_thanh=0;
$gia_hop_thanh=150; // Điểm
$gia_doi_ten=10000; // xu khóa
$Gia_Dat_Vang=5000;// Nếu giá lơn hơn 1000 sẽ trừ xu khóa nhỏ hơn 1000 thì sẽ trừ bằng điểm
$so_ngay_dat_vang=1;
$gia_doi_mau=10000; // Nếu giá lơn hơn 1000 sẽ trừ xu khóa nhỏ hơn 1000 thì sẽ trừ bằng điểm
# Khai bao thong tin ket noi
$config['dbhostdata'] = 'WIN-L1SSEEUPNP6';							# Server name cua mssql

$config['dbuserdata'] = 'sa';							# User name cua mssql ( mac dinh la sa )

$config['dbpassdata'] = '@admin12211993';						# Pass cua mssql ( pass cua sa )

$config['dbdatatank'] = 'Project_Player34';						# Db chua du lieu ( mac dinh la Db_Player )

$config['dbdatamemb'] = 'Member';						# Db membership
$dbshop='Member.dbo';
$connectionInfo = array("Database" => $config['dbdatatank'], "UID" => $config['dbuserdata'], "PWD" => $config['dbpassdata'], "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($config['dbhostdata'], $connectionInfo);
# Include class webshopv3
include_once 'include/global.php';

# Khoi tao class ket noi mssql
$data = new webshopv3($config);
?>