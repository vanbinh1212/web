<?php
session_start();
define("SNAPE_VN", true);

error_reporting(E_ALL ^ E_NOTICE);

if(!$_SERVER['REQUEST_METHOD'] === 'POST') die();

include_once dirname(__FILE__).'/../#config.php';

include_once dirname(__FILE__).'/../class/class.account.php';

include_once dirname(__FILE__).'/../class/function.global.php';

$id = $_GET['id'];

if(!is_numeric($id) || $id <= 0) die();

if(!account::isLogin()) {
	die('-0');
}

$serverInfo = loadserver($id);

if($serverInfo == null)
	die('--0');

echo account::getCoinGame($_SESSION['ss_user_id'], $id);
?>