<?php
	include_once("../../../../_init.php");
	include_once($GP -> CLS."/class.customer.php");
	$C_Customer 		= new Customer;

	switch($_POST['mode']){		
		//고객의 소리 삭제
		case "CS_DEL":
			if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;				
			
			$args = "";
			$args['cs_idx'] = $cs_idx;
			$rst = $C_Customer -> Cs_Consel_Del($args);
			
			echo "true";
			exit();
		break;
		
		//고객의 소리 처리
		case "CS_MODI":
			if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;				
			
			include $GP -> INC_PATH . "/xssFilter/HTML/Safe.php"; // xss filter을 include
			
			$arg = "";
			$args['tfc_idx'] 				= $tfc_idx;
			$args['tfc_result'] 			= $tfc_result;
			$args['tfc_rt_date'] 		= $tfc_rt_date;		
			
			$safe = new HTML_Safe; // xss filter 객체 생성
			$input = base64_decode($tfc_result_con); 
			$output = $safe->parse($input); // html 태그를 필터링 하여 $output에 대입			
			$tfc_result_con = $C_Func->enc_contents($output);			
			$args['tfc_result_con'] = $tfc_result_con;
			$rst = $C_Customer -> Cs_Consel_Result($args);		

			$C_Func->put_msg_and_modalclose("처리 되었습니다");		
			exit();
		break;
	}
?>