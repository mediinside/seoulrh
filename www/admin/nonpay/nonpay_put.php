<?php
	include_once("../../_init.php");

	
	include_once($GP -> CLS."class.dbconn.php");	
	$Dbcon = new Dbconn();
	
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	
	//exit();
	$file_path = $GP -> HOME."/admin/nonpay/Book1.csv";

	$fp = fopen($file_path, "r");
	$k= 1;
	while($data = fgetcsv($fp, 1000000, ",")) {
		$cate1 =  $data[0];
		$np_item =  $data[1];		
		$np_price =  str_replace(",","",$data[2]);			
		$np_regdate =  $data[3];			
		
		
			$qry = "
				INSERT INTO
				tblNonPay_new
				(
				np_idx,
				cate1,			
				cate2,
				np_bunru,	
				np_item,	
				np_code,	
				np_gubun,	
				np_price,	
				np_row_price,	
				np_high_price,	
				np_percent,
				np_ck1,
				np_ck2,
				np_gita,
				np_regdate
				)
				VALUES
				(
				''
				, '$cate1'
				, '$cate2'
				, '$np_bunru'
				, '$np_item'
				, '$np_code'
				, '$np_gubun'
				, '$np_price'
				, '$np_row_price'
				, '$np_high_price'
				, '$np_percent'
				, '$np_ck1'
				, '$np_ck2'
				, '$np_gita'
				, '$np_regdate'
				)
			";
			echo $qry . "<br><br>";
	
		
		//mysql_query($qry);
	
	}
	
?>