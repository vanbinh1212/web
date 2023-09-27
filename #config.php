<?php
if(!defined("SNAPE_VN")) die ('No access');

error_reporting(E_ALL ^ E_NOTICE);
$_config['config_xu'] = 1;// xu nhan duoc = coin * $_config['config_xu'];
$_config['MOMO']['SDT'] = '0833060331'; //cai nay ong edit
$_config['MOMO']['Name'] = 'Trần Văn Bình'; //edit di

// config site
$_config['page']['title']                          = 'Gunnyae';
$_config['page']['fullurl']                        = 'https://gunnyae.com'; //'http://gunnyae.com';

//config recaptcha
$_config['recaptcha']['sitekey']                   = '6LcEGAwTAAAAAH5D3cDAQiZJ684QexJwubF8SVsm';
$_config['recaptcha']['secret']                    = '6LcEGAwTAAAAANItariwFHbMYN_5NdKqOGkoxKlz';
$_config['recaptcha']['language']                  = 'vi';

// config card
$_config['recharge']['uid']                        = 2137;
$_config['recharge']['user']                       = '55e25e5ca976f';
$_config['recharge']['pass']                       = '727529721_H171050711823280_pay123';

//config social
$_config['social']['google']['id']                 = '133874406594-ut8tnjr366c3ffs9h484rlmtbbpt54kg.apps.googleusercontent.com';
$_config['social']['google']['secret']             = 'Pdu9AIPr8l8FysrXqxn9RZiD';
$_config['social']['yahoo']['id']                  = 'dj0yJmk9TnoyNm00OTVTVU1SJmQ9WVdrOVNGaEpSRFV6TmpJbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD02Mg--';
$_config['social']['yahoo']['secret']              = 'ff832bcaadb527d1ca4485dd6a50cdec5a73ec00';
$_config['social']['yahoo']['domain']              = 'gunnyae.com';
$_config['social']['facebook']['id']               = '504328216590208';
$_config['social']['facebook']['secret']           = '379185e75e94d4da8978532608228c8a';

#Config Contact
$_config['Contact']['FB']         = 'https://zalo.me/g/hdpeza017';
$_config['Contact']['FB1']         = 'https://zalo.me/g/hdpeza017';
$_config['Contact']['Email']         = '';
$_config['Contact']['Zalo']         = '0833060331';

// config festury
$_config['effect']['loading_max_count']            = 10;


// config function
$_config['function']['code_anti_hack_session']     = 'eth34sfgerherh3sgsnhje'; // chong hack session
$_config['function']['news_per_page']              = 10; // tin tuc moi trang
$_config['function']['his_per_page']               = 20; // gd mỗi trang
$_config['function']['ws_per_page']                = 12; // item webshop mỗi trang
$_config['function']['url_resource']               = 'http://gunae.click/res/'; //'http://gunnyae.com:82/';
$_config['function']['md5_center']                 = 'gsgej543645h45hdht4addbkssksSogeskg';
$_config['function']['key_request']                = 'LAMPROVIP-DIGGORY-CYRUS-22111999-LOGINWEBKEY';
$_config['function']['fee_seller_webshop']         = 10;

// config sql server
$_config['sql']['host']                            = 'WIN-L1SSEEUPNP6';
$_config['sql']['username']                        = 'sa';
$_config['sql']['password']                        = '@admin12211993';
$_config['sql']['database']                        = 'Member';

// cur id = 2

$_config['panel']['Administrator'] = 'd003'; // 1 email login

// # this function make connect to sqlsrv. don't modify it
$connectionInfo = array("Database" => $_config['sql']['database'], "UID" => $_config['sql']['username'], "PWD" => $_config['sql']['password'], "CharacterSet" => "UTF-8");

$conn = sqlsrv_connect($_config['sql']['host'], $connectionInfo);

if(!$conn) {
	die("No avalible now!");
} 
?>