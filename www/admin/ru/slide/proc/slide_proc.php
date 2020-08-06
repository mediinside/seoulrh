<?php
include_once("../../../../_init.php");
include_once($GP -> CLS."/class.slide.php");
$C_Slide 	= new Slide;

//error_reporting(E_ALL);
//@ini_set("display_errors", 1);


switch($_POST['mode']){	
	
	
	case 'SLIDE_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
			
		//메인페이지 이미지 업로드
		$file_orName			= "ts_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_SLIDE;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main = $before_image_main;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}

		//메인페이지 이미지 업로드
		$file_orName			= "ts_m_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_SLIDE;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main_m = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main_m = $before_image_main_m;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}
		
		//메인페이지 이미지 업로드
		$file_orName			= "ts_t_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_SLIDE;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main_t = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main_t = $before_image_main_m;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}		

		
		$args = "";
		$args['ts_idx'] 					= $ts_idx;
		$args['ts_title'] 				= addslashes($ts_title);
		$args['ts_link'] 				= $ts_link;
		$args['ts_descrition'] 		= addslashes($ts_descrition);
		$args['ts_content'] 			= $C_Func->enc_contents($ts_content);
		$args['ts_img'] 					= $image_main;
		$args['ts_m_img'] 					= $image_main_m;
		$args['ts_t_img'] 					= $image_main_t;		
		$args['ts_show'] 					= $ts_show;	
		$args['ts_lang'] 					= $ts_lang;				

		$rst = $C_Slide -> Slide_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;


	case "SLIDE_IMGDEL":
			if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
			
			$args = "";
			$args['ts_idx'] = $ts_idx;
			$args['type'] = $type;
			$rst = $C_Slide -> Slide_ImgUpdate($args);
	
			@unlink($GP -> UP_SLIDE . $file);
	
			echo "true";
			exit();
		break;


	case 'SLIDE_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['ts_idx'] 	= $ts_idx;
		$result = $C_Slide ->Slide_Info($args);
		
		if($result) {			
			$ts_img = $result['ts_img'];
			$ts_m_img = $result['ts_m_img'];
			
			if($ts_img != '') {			
				@unlink($GP -> UP_SLIDE.$ts_img);
			}					
			
			if($ts_m_img != '') {			
				@unlink($GP -> UP_SLIDE.$ts_m_img);
			}
			$rst = $C_Slide -> Slide_Del($args);
		}		
		echo "true";
		exit();
	
	break;

	
	case 'SLIDE_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
			
		//메인페이지 이미지 업로드
		$file_orName			= "ts_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_SLIDE;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main = $before_image_main;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}

		//메인페이지 이미지 업로드
		$file_orName			= "ts_m_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_SLIDE;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main_m = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main_m = $before_image_main_m;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}
		
		//메인페이지 이미지 업로드
		$file_orName			= "ts_t_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_SLIDE;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main_t = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main_t = $before_image_main_t;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}		

		
		$args = "";
		$args['ts_title'] 				= addslashes($ts_title);
		$args['ts_link'] 				= $ts_link;
		$args['ts_descrition'] 		= addslashes($ts_descrition);
		$args['ts_content'] 			= $C_Func->enc_contents($ts_content);
		$args['ts_img'] 					= $image_main;
		$args['ts_m_img'] 					= $image_main_m;
		$args['ts_t_img'] 					= $image_main_t;
		$args['ts_show'] 					= $ts_show;
		$args['ts_lang'] 					= $ts_lang;		

		$rst = $C_Slide -> Slide_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
}
?>