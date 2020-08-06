<?php
	include_once("../../_init.php");
	include_once($GP->CLS."class.reserve.php");

	$C_Reserve 	= new Reserve;

	$args = '';
	$args['idx_of_Group'] = $_POST['idx_of_Group'];
	$rst = $C_Reserve -> Doctor_List_Old($args);
	
	if($rst) {
		for	($i=0; $i<count($rst); $i++) {			
			$idx = $rst[$i]['idx'];
			$Doctor_Name = $rst[$i]['Doctor_Name'];
			
			if($_POST['idx_of_Doctor'] == $idx){
				echo "<option value='" . $idx . "' selected>" . $Doctor_Name . "</option>";			
			}else{			
				echo "<option value='" . $idx . "'>" . $Doctor_Name . "</option>";			
			}
		}
	}
?>