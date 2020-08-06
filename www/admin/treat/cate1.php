<?
	include_once  '../../_init.php';
	
	$args = '';
	$tc_cate1 = $_POST['tc_cate1'];	
	$tc_cate2 = $_POST['tc_cate2'];
	
	echo "<option value=''>:::선택:::</option>";
	
	$arr_tmp = $GP->CATE2[$tc_cate1];	
	foreach ($arr_tmp as $key => $val) {
		if($key == $tc_cate2) {
			echo "<option value='" . $key . "' selected>" . $val . "</option>";
		}else{
			echo "<option value='" . $key . "'>" . $val . "</option>";
		}
	}	
	exit();
?>