<?php
session_start();
ini_set("memory_limit" , -1);
error_reporting(0);

$_SERVER['DOCUMENT_ROOT'] ="/home1/seoulrh/www" ;

$_DEF_PATH = str_replace('\\', '/', dirname(__FILE__));
$_DEF_PATH = explode('/',$_DEF_PATH);
array_pop($_DEF_PATH);
$_DEF_PATH = implode('/',$_DEF_PATH);


include_once  $_DEF_PATH . '/_INC/config.inc';
include_once $GP -> CLS . 'class.func.php';
include_once $GP -> CLS . 'class.button.php';
$C_Func = new Func();
$C_Button = new Button();
include_once $GP -> CLS . 'class.dbconn.php';
$C_DB = new Dbconn($GP -> DB);

$_WWW_PATH = "/www" ;
$mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';		
		
$mobile_ck = 0;	
$ssl_url = $GP -> HTTPS . ":" . $GP -> HTTPS_PORT;
$sc_url = $GP -> SERVICE_DOMAIN;
if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
	$mobile_ck = 1;
	$ssl_url = "";
	$sc_url = "";
}

// 쿼리문 보이기
if ($_SERVER["REMOTE_ADDR"] == '210.90.202.198') {
	$qsee = '1';
}else{
	$qsee = '0';
}

$language = explode("/",$_SERVER['SCRIPT_NAME']);
//print_r($language);
if($language[1] == "admin"){
	if($language[2] == "eng" || $language[2] == "ru" || $language[2] == "mong" || $language[2] == "prevention") $lang = $language[2];
}else{
	if($language[1] == "eng" || $language[1] == "ru" || $language[1] == "mng") $lang = $language[1];
	if($language[1] == "mng") $lang = "mong";
	if($language[1] == "rus") $lang = "ru";
}
//echo $lang;
?>