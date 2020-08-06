<?php
	include_once("../../_init.php");
	include_once($GP->CLS."class.reserve.php");

	$C_Reserve 	= new Reserve;

	$args = '';
	$args['Area'] = $_POST['Area'];
	$rst = $C_Reserve -> Center_List_Old($args);
	
	if($rst) {
		for	($i=0; $i<count($rst); $i++) {			
			$idx = $rst[$i]['idx'];
			$Group = $rst[$i]['Group'];
			
			if($_POST['idx_of_Group'] == $idx){
				echo "<option value='" . $idx . "' selected>" . $Group . "</option>";			
			}else{			
				echo "<option value='" . $idx . "'>" . $Group . "</option>";			
			}
		}
	}
?>