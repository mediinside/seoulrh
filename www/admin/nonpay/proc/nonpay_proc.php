<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.nonpay.php");
$C_Nonpay 	= new Nonpay;


switch($_POST['mode']){	

	case "NONPAY_DEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['np_idx'] 	= $np_idx;
		$rst = $C_Nonpay -> NonPay_Info_Del($args);
		
		echo "true";
			exit();
	break;


	case 'NONPAY_MODI' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
	
		$args = "";
		$args['np_idx'] 				= $np_idx;
		$args['cate1'] 				= $cate1;
		$args['cate2'] 				= $cate2;
		$args['np_bunru'] 			= $np_bunru;
		$args['np_item'] 				= $np_item;
		$args['np_code'] 			= $np_code;
		$args['np_gubun'] 			= $np_gubun;		
		$args['np_price'] 			= $np_price;
		$args['np_row_price'] 		= $np_row_price;
		$args['np_high_price'] 		= $np_high_price;
		$args['np_percent'] 			= $np_percent;
		$args['np_ck1'] 				= $np_ck1;
		$args['np_ck2'] 				= $np_ck2;
		$args['np_gita'] 				= $np_gita;

		$rst = $C_Nonpay -> NonPay_Info_Modi($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("수정 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("수정에 실패하였습니다");
		}
		exit();
	break;

	case 'NONPAY_REG' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
	
		$args = "";
		$args['cate1'] 				= $cate1;
		$args['cate2'] 				= $cate2;
		$args['np_bunru'] 			= $np_bunru;
		$args['np_item'] 				= $np_item;
		$args['np_code'] 			= $np_code;
		$args['np_gubun'] 			= $np_gubun;		
		$args['np_price'] 			= $np_price;
		$args['np_row_price'] 		= $np_row_price;
		$args['np_high_price'] 		= $np_high_price;
		$args['np_percent'] 			= $np_percent;
		$args['np_ck1'] 				= $np_ck1;
		$args['np_ck2'] 				= $np_ck2;
		$args['np_gita'] 				= $np_gita;

		$rst = $C_Nonpay -> NonPay_Info_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;


















	case "NONPAY_DEL_NEW":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['np_idx'] 	= $np_idx;
		$rst = $C_Nonpay -> NonPay_Info_Del_New($args);
		
		echo "true";
			exit();
	break;


	case 'NONPAY_MODI_NEW' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
	
		$args = "";
		$args['np_idx'] 				= $np_idx;
		$args['cate1'] 					= $cate1;
		$args['cate2'] 					= $cate2;
		$args['np_bunru'] 			= $np_bunru;
		$args['np_item'] 				= $np_item;
		$args['np_code'] 				= $np_code;
		$args['np_gubun'] 			= $np_gubun;		
		$args['np_price'] 			= $np_price;
		$args['np_row_price'] 	= $np_row_price;
		$args['np_high_price'] 	= $np_high_price;
		$args['np_percent'] 		= $np_percent;
		$args['np_ck1'] 				= $np_ck1;
		$args['np_ck2'] 				= $np_ck2;
		$args['np_gita'] 				= $np_gita;

		$rst = $C_Nonpay -> NonPay_Info_Modi_New($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("수정 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("수정에 실패하였습니다");
		}
		exit();
	break;


	case 'NONPAY_REG_NEW' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
	
		$args = "";
		$args['cate1'] 					= $cate1;
		$args['cate2'] 					= $cate2;
		$args['np_bunru'] 			= $np_bunru;
		$args['np_item'] 				= $np_item;
		$args['np_code'] 				= $np_code;
		$args['np_gubun'] 			= $np_gubun;		
		$args['np_price'] 			= $np_price;
		$args['np_row_price'] 	= $np_row_price;
		$args['np_high_price'] 	= $np_high_price;
		$args['np_percent'] 		= $np_percent;
		$args['np_ck1'] 				= $np_ck1;
		$args['np_ck2'] 				= $np_ck2;
		$args['np_gita'] 				= $np_gita;

		$rst = $C_Nonpay -> NonPay_Info_Reg_New($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;






}
?>