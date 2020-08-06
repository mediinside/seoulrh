<?
	include_once  '../../_init.php';
	
	$args = '';
	$tcs_cate1 = $_POST['tcs_cate1'];	
	$tcs_cate2 = $_POST['tcs_cate2'];
	
	echo "<option value=''>:::선택:::</option>";
	
	$arr_tmp = $GP->CATE4[$tcs_cate1];		
	foreach ($arr_tmp as $key => $val) {
		if($key == $tcs_cate2) {
			echo "<option value='" . $key . "' selected>" . $val . "</option>";
		}else{
			echo "<option value='" . $key . "'>" . $val . "</option>";
		}
	}	
	exit();
?>