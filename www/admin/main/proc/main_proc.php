<?php
include_once("../../../_init.php");
include_once($GP -> INC_ADM_PATH."inc.adm_auth.php");
include_once($GP -> CLS."/class.admmain.php");
$C_AdminMain 	= new AdminMain;


switch($_POST['mode']){
	
	case "ADMINAUTHDEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = '';
		$args['tl_idx'] = $tl_idx;		
		$rst = $C_AdminMain -> Admin_Auth_Del($args);

		echo "true";
		exit();
	break;
	
	case "ADMINAUTHMODI":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = '';
		$args['tl_level'] = $tl_level;
		
		$tl_fld = "";
		$tl_fld_sub = "";
		if(is_array($tl_folder)){
			for($i=0; $i<count($tl_folder); $i++) {
				$arr_tmp = explode("|", $tl_folder[$i]);
				$tl_fld .= $arr_tmp[0] .",";
				$tl_fld_sub .= $arr_tmp[1] .",";
			}
		}
		$tl_fld = rtrim($tl_fld,",");
		$tl_fld_sub = rtrim($tl_fld_sub,",");

		$tl_bs = "";
		
		if(is_array($tl_bbs)){
			for($i=0; $i<count($tl_bbs); $i++) {
				$tl_bs .= $tl_bbs[$i] .",";
			}
		}
		$tl_bs = rtrim($tl_bs,",");
		
		$args['tl_bbs'] = $tl_bs;		
		$args['tl_folder'] = $tl_fld;
		$args['tl_folder_sub'] = $tl_fld_sub;
		$args['tl_idx'] = $tl_idx;
		$args['tl_bbs'] = $tl_bs;
		
		$rst = $C_AdminMain -> Admin_Auth_Modi($args);


		$C_Func->put_msg_and_modalclose("수정 되었습니다");
		exit();
	
	break;
	
	case 'ADMINAUTHREG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = '';
		$args['tl_level'] = $tl_level;
		
		$tl_fld = "";
		$tl_fld_sub = "";
		if(is_array($tl_folder)){
			for($i=0; $i<count($tl_folder); $i++) {
				$arr_tmp = explode("|", $tl_folder[$i]);
				$tl_fld .= $arr_tmp[0] .",";
				$tl_fld_sub .= $arr_tmp[1] .",";
			}
		}
		$tl_fld = rtrim($tl_fld,",");
		$tl_fld_sub = rtrim($tl_fld_sub,",");
		
		$args['tl_folder'] = $tl_fld;
		$args['tl_folder_sub'] = $tl_fld_sub;


		$tl_bs = "";
		
		if(is_array($tl_bbs)){
			for($i=0; $i<count($tl_bbs); $i++) {
				$tl_bs .= $tl_bbs[$i] .",";
			}
		}
		$tl_bs = rtrim($tl_bs,",");
		
		$args['tl_bbs'] = $tl_bs;
		
		$rst = $C_AdminMain -> Admin_Auth_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
	case 'ADMINDEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['mb_code'] 	= $mb_code;
		$rst = $C_AdminMain -> Admin_Info_Del($args);
		
		echo "true";
			exit();
	break;
	
	case 'ADMINMODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['mb_code'] 						= $mb_code;
		$args['mb_id'] 							= $mb_id;
		$args['mb_name'] 						= $mb_name;
		
		if($mb_password != '') {
			$args['mb_password'] 				= $C_Func->encrypt_md5($mb_password, $GP -> PASS);	
		}else{
			$args['mb_password'] 				= $old_mb_password;	
		}
		
		$args['mb_email'] 					= $mb_email;
		$args['mb_phone'] 					= $mb_phone;
		$args['mb_level'] 					= $mb_level;
		$args['tl_idx'] 						= $tl_idx;

		$rst = $C_AdminMain -> Admin_Info_Modify($args);
		
		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;
	
	
	case 'ADMINREG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['mb_id'] 						= $mb_id;
		$args['mb_name'] 					= $mb_name;		
		$args['mb_password'] 				= $C_Func->encrypt_md5($mb_password, $GP -> PASS);			
		$args['mb_email'] 					= $mb_email;
		$args['mb_phone'] 					= $mb_phone;
		$args['mb_level'] 					= $mb_level;
		$args['tl_idx'] 						= $tl_idx;

		$rst = $C_AdminMain -> Admin_Info_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
}
?>