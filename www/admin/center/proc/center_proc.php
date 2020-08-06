<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.seoulrh.php");
$C_seoulrh 	= new seoulrh;

//error_reporting(E_ALL);
//@ini_set("display_errors", 1);


switch($_POST['mode']){	
	
	
	case 'seoulrh_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
				
		$args = "";
        $args['s_idx'] 					= $s_idx;
        $args['s_year'] 		        = addslashes($s_year);
        $args['s_content1'] 			= $C_Func->enc_contents($s_content1);	
        $args['s_content2'] 			= $C_Func->enc_contents($s_content2);
        $args['s_content3'] 			= $C_Func->enc_contents($s_content3);
        $args['s_content4'] 			= $C_Func->enc_contents($s_content4);
        $args['s_content5'] 			= $C_Func->enc_contents($s_content5);
        $args['s_content6'] 			= $C_Func->enc_contents($s_content6);
        $args['s_content7'] 			= $C_Func->enc_contents($s_content7);
		$args['s_show'] 				= $s_show;
		$args['s_type']					= $s_type;

		$rst = $C_seoulrh -> seoulrh_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;


	
	case 'seoulrh_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['s_idx'] 	= $s_idx;
		$result = $C_seoulrh ->seoulrh_Info($args);
		
		if($result) {			
			$s_img = $result['s_img'];
			$s_m_img = $result['s_m_img'];
			
			if($s_img != '') {			
				@unlink($GP -> UP_seoulrh.$s_img);
			}					
			
			if($s_m_img != '') {			
				@unlink($GP -> UP_seoulrh.$s_m_img);
			}
			$rst = $C_seoulrh -> seoulrh_Del($args);
		}		
		echo "true";
		exit();
	
	break;

	
	case 'seoulrh_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		

		
		$args = "";
        $args['s_year'] 		= addslashes($s_year);
        $args['s_content1'] 		= $C_Func->enc_contents($s_content1);
        $args['s_content2'] 		= $C_Func->enc_contents($s_content2);
        $args['s_content3'] 		= $C_Func->enc_contents($s_content3);
        $args['s_content4'] 		= $C_Func->enc_contents($s_content4);
        $args['s_content5'] 		= $C_Func->enc_contents($s_content5);
        $args['s_content6'] 		= $C_Func->enc_contents($s_content6);
        $args['s_content7'] 		= $C_Func->enc_contents($s_content7);
        $args['s_show'] 		= addslashes($s_show);		
		$args['s_type']			= $s_type;

		$rst = $C_seoulrh -> seoulrh_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
}
?>