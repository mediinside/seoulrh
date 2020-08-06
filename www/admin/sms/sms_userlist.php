<?php
	include_once("../../_init.php");
	include_once $GP -> CLS . 'class.sms.php';
	$C_Sms 	= new Sms;
	
	$args = '';
	$args['mb_gr_code'] = $_POST['GroupCode'];
	$rst = $C_Sms->Sms_Mem_Group_List($args);	
	
	if(count($rst) > 0)
	{
		header("Content-type: text/xml;charset=utf-8");
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		echo "<UserGroup>";
			for($i=0; $i<count($rst); $i++) {
				$mb_name = $rst[$i]['mb_name'];
				$mb_mobile = $rst[$i]['mb_mobile'];
				echo "<User id='$mb_mobile'>$mb_name | $mb_mobile</User>";
			}
		echo"</UserGroup>";
	}
	else
	{
		header("Content-type: text/xml;charset=utf-8");
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		echo "<UserGroup>";
		echo "<User id=''></User>";
		echo "</UserGroup>";
	}
	
?>