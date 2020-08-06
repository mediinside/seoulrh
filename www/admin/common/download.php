<?php
	include_once("../../_init_.php");
	include_once($GP -> CLS."class.filedown.php");


	$C_FileDown = new FileDownload;
	$file			= $_GET['file'];			//file 경로 + file명
	$name			= $_GET['name'];			//다운받을 파일명
	$downview = $_GET['downview'];

	if($_GET['speed']){
		$speed		= $_GET['speed'];
	}else{
		$speed = "5";
	}

	$limit		= $_GET['limit'];
	//$file = $file . '/' . $name;

	$rst = $C_FileDown -> fDown($file, $name, $downview, $speed, $limit);
	exit;
?>