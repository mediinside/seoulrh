<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.history.php");
$C_history 	= new history;

//error_reporting(E_ALL);
//@ini_set("display_errors", 1);


switch($_POST['mode']){	
	
	
	case 'history_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
				
		$args = "";
        $args['h_idx'] 					= $h_idx;
        $args['h_year'] 		= addslashes($h_year);
        $args['h_day'] 		= addslashes($h_day);	
		$args['h_content'] 			= $C_Func->enc_contents($h_content);	
		$args['h_show'] 					= $h_show;
		$args['h_type']					= $h_type;

		$rst = $C_history -> history_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;


	
	case 'history_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['h_idx'] 	= $h_idx;
		$result = $C_history ->history_Info($args);
		
		if($result) {			
			$h_img = $result['h_img'];
			$h_m_img = $result['h_m_img'];
			
			if($h_img != '') {			
				@unlink($GP -> UP_history.$h_img);
			}					
			
			if($h_m_img != '') {			
				@unlink($GP -> UP_history.$h_m_img);
			}
			$rst = $C_history -> history_Del($args);
		}		
		echo "true";
		exit();
	
	break;

	
	case 'history_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		

		
		$args = "";
        $args['h_year'] 		= addslashes($h_year);
        $args['h_day'] 		= addslashes($h_day);
        $args['h_show'] 		= addslashes($h_show);
		$args['h_content'] 		= $C_Func->enc_contents($h_content);
		$args['h_type']			= $h_type;

		$rst = $C_history -> history_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
}
?>