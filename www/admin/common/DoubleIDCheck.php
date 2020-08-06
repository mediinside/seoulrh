<?php
	include_once("../../_init.php");
	include_once $GP -> CLS . 'class.admmain.php';
	$C_AdminMain 	= new AdminMain;
	
	$args['mb_id'] = $_POST['mb_id'];
	$rst = $C_AdminMain->DobuleIDCheck($args);	

	if($rst['cnt'] > 0)
	{
		echo "false";
		exit();
	}
	else
	{
		echo "true";
		exit();
	}

?>