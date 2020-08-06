<?php
	include_once  '../../_init.php';
	include_once($GP->CLS."class.reserve.php");

	$C_Reserve = new Reserve();

	$args = array();
	$args['dr_idx'] = $_POST['dr_idx'];
	$args['cp_date'] = $_POST['date'];	
	$args['rd_s_time'] = $_POST['rd_s_time'];
	$rst = $C_Reserve->Doctor_Time_List_Adm($args);

	if($rst) {
		for	($i=0; $i<count($rst); $i++) {			
			$cp_idx = $rst[$i]['cp_idx'];
			$cp_s_time = $rst[$i]['cp_s_time'];
			$cp_e_time = $rst[$i]['cp_e_time'];
			
			if($_POST['rd_s_time'] == $cp_s_time) {
				echo "<option value='" . $cp_idx . "' selected>". $cp_s_time ."</option>";			
			}else {
				echo "<option value='" . $cp_idx . "'>". $cp_s_time ."</option>";			
			}
		}
	}
?>