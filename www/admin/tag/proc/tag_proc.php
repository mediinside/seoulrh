<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.tag.php");
$C_Tag 	= new Tag;


switch($_POST['mode']){	
	
	case 'TT_AUTO_CH':
			if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
			
			$args = "";		
			$args['tmp_id'] = $tmp_id;
			$args['max_desc'] = $max_desc;
			$rst = $C_Tag->TT_AUTO_CHAGE($args);
			
			echo "true";
			exit();
	break;
	
	
	case 'TAG_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tt_idx'] 						= $tt_idx;
		$args['tt_cate'] 					= $tt_cate;
		$args['tt_tag_name'] 			= addslashes($tt_tag_name);
		$args['tt_url'] 					= addslashes($tt_url);
		$args['tt_show'] 					= $tt_show;

		$rst = $C_Tag -> Tag_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;

	
	case 'TAG_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tt_idx'] 	= $tt_idx;		
		$rst = $C_Tag -> Tag_Del($args);	
		
		echo "true";
		exit();
	
	break;
	
	
	case 'TAG_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tt_cate'] 					= $tt_cate;
		$args['tt_tag_name'] 			= addslashes($tt_tag_name);
		$args['tt_url'] 					= addslashes($tt_url);
		$args['tt_show'] 					= $tt_show;
		

		$rst = $C_Tag -> Tag_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
}
?>