<?
	include_once  '../../_init.php';
	
	$args = '';
	$tc_cate2 = $_POST['tc_cate2'];	
	$tc_cate3 = $_POST['tc_cate3'];
	
	echo "<option value=''>:::선택:::</option>";
	
	$arr_tmp = $GP->CATE5[$tcs_cate2];		
	foreach ($arr_tmp as $key => $val) {
		if($key == $tcs_cate3) {
			echo "<option value='" . $key . "' selected>" . $val . "</option>";
		}else{
			echo "<option value='" . $key . "'>" . $val . "</option>";
		}
	}	
	exit();
?>