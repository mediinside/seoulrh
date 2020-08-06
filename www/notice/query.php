<?php
include ($_SERVER['DOCUMENT_ROOT'] . "/www/_init.php");



switch ($jb_code)
{		
	default :
		$index_page = "notice.php";	// 기본
		break;
}

$query_page = "query.php";

include $GP -> INC_PATH . "/board_insert.php";
?>