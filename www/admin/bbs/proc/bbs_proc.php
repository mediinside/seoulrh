<?php
include_once("../../../_init.php");
include_once($GP -> INC_ADM_PATH_NEW."inc.adm_auth.php");
include_once($GP -> CLS."/class.jhboard.php");
$C_JHBoard 	= new JHBoard;


switch($_POST['mode']){
	
	case 'BBS_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['jb_code'] 						= $jb_code;
		$args['jba_title'] 						= $jba_title;
		$args['jba_read_level'] 		= $jba_read_level;
		$args['jba_write_level'] 		= $jba_write_level;
		$args['jba_reply_level'] 		= $jba_reply_level;
		$args['jba_comment_level'] 	= $jba_comment_level;
		$args['jba_comment_use'] 		= $jba_comment_use;
		$args['jba_list_use'] 			= $jba_list_use;
		$args['jba_list_scale'] 		= $jba_list_scale;
		$args['jba_skin_dir'] 			= $jba_skin_dir;
		$rst = $C_JHBoard -> BBS_Info_Modify($args);
		
		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;
}
?>