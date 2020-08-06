<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.treat.php");
$C_Treat 	= new Treat;


switch($_POST['mode']){
	
	
	case "TREAT_SHOW_DEL" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tcs_idx'] = $tcs_idx;
		$rst = $C_Treat -> TREAT_Show_Del($args);

		echo "true";
		exit();
	
	break;
	
	case "TREAT_SHOW_MODI":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tcs_idx'] = $tcs_idx;
		$args['tc_idx_1'] = $tc_idx_1;
		$args['tc_title_1'] = $tc_title_1;
		$args['tc_idx_2'] = $tc_idx_2;
		$args['tc_title_2'] = $tc_title_2;
		$args['tcs_cate1'] = $tcs_cate1;
		$args['tcs_cate2'] = $tcs_cate2;
		$args['tcs_cate3'] = $tcs_cate3;		
		
		$rst = $C_Treat -> TREAT_Show_Modi($args);


		$C_Func->put_msg_and_modalclose("수정 되었습니다");
		exit();
	break;
	
	case "TREAT_SHOW_REG":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tc_idx_1'] = $tc_idx_1;
		$args['tc_title_1'] = $tc_title_1;
		$args['tc_idx_2'] = $tc_idx_2;
		$args['tc_title_2'] = $tc_title_2;
		$args['tcs_cate1'] = $tcs_cate1;
		$args['tcs_cate2'] = $tcs_cate2;
		$args['tcs_cate3'] = $tcs_cate3;
		
		$rst = $C_Treat -> TREAT_Show_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
	case 'TREAT_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
		
		//메인페이지 이미지 업로드
		$file_orName			= "tc_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_TREAT;
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
		
		
		$file_save_path = $GP -> UP_TREAT;
		//에디터
		if($img_full_name != "") {
			$Arr_img = explode(',', $img_full_name);	
			$img_name = "";
			for	($i=0; $i<count($Arr_img); $i++) {		
				if(ereg($C_Func->escape_ereg($Arr_img[$i]), $C_Func->escape_ereg($tc_content))) {		
					$img_name .= trim($Arr_img[$i]) . ",";		
				}else{
					@unlink($file_save_path . $Arr_img[$i]);
				}
			}
			$img_name = rtrim($img_name , ",");
			
			$args['tc_editor_img'] = $img_name;
		}

		
		$args = "";
		$args['tc_idx'] 						= $tc_idx;
		$args['tc_cate1'] 					= $tc_cate1;
		$args['tc_cate2'] 					= $tc_cate2;
		$args['tc_cate3'] 					= $tc_cate3;
		$args['tc_img'] 						= $image_main;		
		$args['tc_title'] 					= addslashes($tc_title);
		$args['tc_summary'] 				= addslashes($tc_summary);
		$args['tc_content'] 				= $C_Func->enc_contents($tc_content);

		$rst = $C_Treat -> TREAT_Info_Modify($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;
	
	case "TREAT_IMGDEL" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tc_idx'] = $tc_idx;
		$rst = $C_Treat -> TREAT_ImgUpdate($args);

		@unlink($GP -> UP_TREAT . $tc_img);

		echo "true";
		exit();
	break;
	
	case 'TREAT_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['tc_idx'] 	= $tc_idx;
		$result = $C_Treat ->TREAT_Info($args);
		
		if($result) {			
			$tc_img = $result['tc_img'];
			$tc_editor_img = $result['tc_editor_img'];
			
			if($tc_img != '') {			
				@unlink($GP -> UP_TREAT.$tc_img);
			}
			
			if($tc_editor_img != '') {
				$tmp_arr = explode(',', $tc_editor_img);				
				for($i=0; $i<count($tmp_arr); $i++) {
					@unlink($GP -> UP_TREAT.$tmp_arr[$i]);
				}
			}			
			$rst = $C_Treat -> TREAT_Info_Del($args);
		}
		
		echo "true";
		exit();
	
	break;
	
	
	case 'TREAT_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
		
		//메인페이지 이미지 업로드
		$file_orName			= "tc_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_TREAT;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array("jpg","gif","png","bmp");

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) $insertFileCheck = true;
			$image_main = $updata['new_file_name'];	//변경된 파일명
		}
		
		
		$file_save_path = $GP -> UP_TREAT;
		//에디터
		if($img_full_name != "") {
			$Arr_img = explode(',', $img_full_name);	
			$img_name = "";
			for	($i=0; $i<count($Arr_img); $i++) {		
				if(ereg($C_Func->escape_ereg($Arr_img[$i]), $C_Func->escape_ereg($tc_content))) {		
					$img_name .= trim($Arr_img[$i]) . ",";		
				}else{
					@unlink($file_save_path . $Arr_img[$i]);
				}
			}
			$img_name = rtrim($img_name , ",");
			
			$args['tc_editor_img'] = $img_name;
		}

		
		$args = "";
		$args['tc_cate1'] 					= $tc_cate1;
		$args['tc_cate2'] 					= $tc_cate2;
		$args['tc_cate3'] 					= $tc_cate3;
		$args['tc_img'] 						= $image_main;		
		$args['tc_title'] 					= addslashes($tc_title);
		$args['tc_summary'] 				= addslashes($tc_summary);
		$args['tc_content'] 			  = $C_Func->enc_contents($tc_content);

		$rst = $C_Treat -> TREAT_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
}
?>