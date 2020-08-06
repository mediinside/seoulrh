<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.popup.php");
$C_Popup 	= new Popup;


switch($_POST['mode']){
	
	case 'POPUP_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
		
		//메인페이지 이미지 업로드
		$file_orName			= "pop_file";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_POPUP;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array("jpg","gif","png","bmp");

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) $insertFileCheck = true;
			$image_main = $updata['new_file_name'];	//변경된 파일명
		}else {
			$image_main				= $before_image_main;
		}
		
		
		$file_save_path = $GP -> UP_POPUP;
		//에디터
		if($img_full_name != "") {
			$Arr_img = explode(',', $img_full_name);	
			$img_name = "";
			for	($i=0; $i<count($Arr_img); $i++) {		
				if(ereg($C_Func->escape_ereg($Arr_img[$i]), $C_Func->escape_ereg($pop_contents))) {		
					$img_name .= trim($Arr_img[$i]) . ",";		
				}else{
					@unlink($file_save_path . $Arr_img[$i]);
				}
			}
			$img_name = rtrim($img_name , ",");
			
			$args['editor_img_code'] = $img_name;
		}

		
		$args = "";
		$args['pop_idx'] 					= $pop_idx;
		$args['pop_type'] 					= $pop_type;
		$args['pop_file_name'] 			= $image_main;		
		$args['pop_start_date'] 		= $pop_start_date;
		$args['pop_end_date'] 			= $pop_end_date;
		$args['pop_cookie'] 				= $pop_cookie;
		$args['pop_title'] 					= $pop_title;
		$args['pop_use'] 						= $pop_use;
		$args['pop_contents'] 			= $C_Func->enc_contents($pop_contents);
		$args['pop_link_url'] 			= $pop_link_url;
		$args['pop_width'] 					= $pop_width;
		$args['pop_height'] 				= $pop_height;
		$args['pop_scroll'] 				= $pop_scroll;
		$args['pop_x_position'] 		= $pop_x_position;
		$args['pop_y_position'] 		= $pop_y_position;
		$args['pop_type2'] 					= $pop_type2;


		$rst = $C_Popup -> Popup_Info_Modify($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;
	
	case "POPUP_IMGDEL" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['pop_idx'] = $pop_idx;
		$rst = $C_Popup -> Popup_ImgUpdate($args);

		@unlink($GP -> UP_POPUP . $pop_file);

		echo "true";
		exit();
	break;
	
	case 'POPUP_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['pop_idx'] 	= $pop_idx;
		$result = $C_Popup ->PopUp_Info($args);
		
		if($result) {			
			$pop_file_name = $result['pop_file_name'];
			$editor_img_code = $result['editor_img_code'];
			
			if($pop_file_name != '') {			
				@unlink($GP -> UP_POPUP.$pop_file_name);
			}
			
			if($editor_img_code != '') {
				$tmp_arr = explode(',', $editor_img_code);				
				for($i=0; $i<count($tmp_arr); $i++) {
					@unlink($GP -> UP_POPUP.$tmp_arr[$i]);
				}
			}			
			$rst = $C_Popup -> Popup_Info_Del($args);
		}
		
		echo "true";
		exit();
	
	break;
	
	
	case 'POPUP_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
		
		//메인페이지 이미지 업로드
		$file_orName			= "pop_file";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_POPUP;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array("jpg","gif","png","bmp");

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) $insertFileCheck = true;
			$image_main = $updata['new_file_name'];	//변경된 파일명
		}
		
		
		$file_save_path = $GP -> UP_POPUP;
		//에디터
		if($img_full_name != "") {
			$Arr_img = explode(',', $img_full_name);	
			$img_name = "";
			for	($i=0; $i<count($Arr_img); $i++) {		
				if(ereg($C_Func->escape_ereg($Arr_img[$i]), $C_Func->escape_ereg($pop_contents))) {		
					$img_name .= trim($Arr_img[$i]) . ",";		
				}else{
					@unlink($file_save_path . $Arr_img[$i]);
				}
			}
			$img_name = rtrim($img_name , ",");
			
			$args['editor_img_code'] = $img_name;
		}

		
		$args = "";
		$args['pop_type'] 					= $pop_type;
		$args['pop_file_name'] 			= $image_main;		
		$args['pop_start_date'] 		= $pop_start_date;
		$args['pop_end_date'] 			= $pop_end_date;
		$args['pop_cookie'] 				= $pop_cookie;
		$args['pop_title'] 					= $pop_title;
		$args['pop_use'] 						= $pop_use;
		$args['pop_contents'] 			= $C_Func->enc_contents($pop_contents);
		$args['pop_link_url'] 			= $pop_link_url;
		$args['pop_width'] 					= $pop_width;
		$args['pop_height'] 				= $pop_height;
		$args['pop_scroll'] 				= $pop_scroll;
		$args['pop_x_position'] 		= $pop_x_position;
		$args['pop_y_position'] 		= $pop_y_position;
		$args['pop_type2'] 					= $pop_type2;


		$rst = $C_Popup -> Popup_Info_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
}
?>