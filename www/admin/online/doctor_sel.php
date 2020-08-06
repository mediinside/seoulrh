<?php
	include_once("../../_init.php");
	include_once($GP->CLS."class.doctor.php");

	$C_Doctor = new Doctor();

	$args = '';
	$args['dr_clinic'] = $_POST['dr_clinic'];
	$rst = $C_Doctor -> Doctor_Center_List($args);
	
	if($rst) {
		for	($i=0; $i<count($rst); $i++) {			
			$dr_idx = $rst[$i]['dr_idx'];
			$dr_name = $rst[$i]['dr_name'];
			$dr_clinic = $rst[$i]['dr_clinic'];
			// $dr_center = $rst[$i]['dr_center'];
			
			if($_POST['dr_idx'] == $dr_idx){
				echo "<option value='" . $dr_idx . "' selected>[" . $GP -> CLINIC_TYPE[$dr_clinic] .  "] " . $dr_name . "</option>";			
			}else{			
				echo "<option value='" . $dr_idx . "'>[" . $GP -> CLINIC_TYPE[$dr_clinic] .  "] " . $dr_name . "</option>";			
			}
		}
	}
?>