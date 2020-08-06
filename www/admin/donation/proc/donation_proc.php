<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.donation.php");
$C_donation 	= new donation;

//error_reporting(E_ALL);
//ini_set("display_errors", 1);


switch($_POST['mode']){	
	
	
	case 'donation_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
			
		//메인페이지 이미지 업로드
		$file_orName			= "do_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_donation;
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
		$file_orName			= "do_img2";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_donation;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main2 = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main2 = $before_image_main2;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}

		
		$args = "";
		$args['do_idx'] 					= $do_idx;
		$args['do_title'] 				= addslashes($do_title);
		$args['do_link'] 				= $do_link;
		$args['do_descrition'] 		= addslashes($do_descrition);
		$args['do_content'] 			= $C_Func->enc_contents($do_content);
		$args['do_img'] 					= $image_main;
		$args['do_img2'] 					= $image_main2;
		$args['do_show'] 					= $do_show;
		$args['do_lang'] 					= "kor";	
        $args['do_type']					= $do_type;
        
        //print_r($args);
        //exit;

		$rst = $C_donation ->donation_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;


	case "donation_IMGDEL":
			if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
			
			$args = "";
			$args['do_idx'] = $do_idx;
			$args['type'] = $type;
			$rst = $C_donation ->donation_ImgUpdate($args);
	
			@unlink($GP -> UP_donation . $file);
	
			echo "true";
			exit();
		break;


	case 'donation_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['do_idx'] 	= $do_idx;
		$result = $C_donation ->donation_Info($args);
		
		if($result) {			
			$do_img = $result['do_img'];
			$do_img2 = $result['do_img2'];
			
			if($do_img != '') {			
				@unlink($GP -> UP_donation.$do_img);
			}					
			
			if($do_img2 != '') {			
				@unlink($GP -> UP_donation.$do_img2);
			}
			$rst = $C_donation ->donation_Del($args);
		}		
		echo "true";
		exit();
	
	break;

	
	case 'donation_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		include_once($GP->CLS."class.fileup.php");
			
		//메인페이지 이미지 업로드
		$file_orName			= "do_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_donation;
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
		$file_orName			= "do_img2";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_donation;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array();

			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();

			if ($updata['error']) 
				$insertFileCheck = true;
			$image_main2 = $updata['new_file_name'];	//변경된 파일명
		}else{
			$image_main2 = $before_image_main2;
		}
		
		if($insertFileCheck) {
			$C_Func->put_msg_and_modalclose($updata['error']);
		}

		
		$args = "";
		$args['do_title'] 				= addslashes($do_title);
		$args['do_link'] 				= $do_link;
		$args['do_descrition'] 		= addslashes($do_descrition);
		$args['do_content'] 			= $C_Func->enc_contents($do_content);
		$args['do_img'] 					= $image_main;
		$args['do_img2'] 					= $image_main2;
		$args['do_show'] 					= $do_show;		
		$args['do_lang'] 					= "kor";
		$args['do_type']					= $do_type;

		$rst = $C_donation ->donation_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
    break;  

    
	case 'donation2_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
        $args = "";       
        $args['do_idx'] 	= $do_idx; 
        $args['do_cidx']					= $do_cidx;
        $args['do_year'] 		= addslashes($do_year);
        $args['do_gubun'] 		= $do_gubun;
        $args['do_select'] 		= addslashes($do_select);      
        $args['do_pay'] 		= $do_pay;    
        $args['do_color'] 		= $do_color;     
        $args['do_img'] 		= $do_img;        
        $args['do_show'] 		= addslashes($do_show);
        $args['do_type']					= $do_type;

		$rst = $C_donation -> donation2_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
    break;

    case 'donation2_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
				
        $args = "";      
        $args['do_idx'] 	= $do_idx;  
        $args['do_cidx']		= $do_cidx;
        $args['do_year'] 		= addslashes($do_year);
        $args['do_gubun'] 		= $do_gubun;
        $args['do_select'] 		= addslashes($do_select);      
        $args['do_pay'] 		= $do_pay;    
        $args['do_color'] 		= $do_color;     
        $args['do_img'] 		= $do_img;        
        $args['do_show'] 		= addslashes($do_show);
        $args['do_type']		= $do_type;        

		$rst = $C_donation -> donation2_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
    break;
    
    case 'donation2_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['do_idx'] 	= $do_idx;
		$result = $C_donation ->donation2_Info($args);
		
		if($result) {			
			$do_img = $result['do_img'];
			$do_m_img = $result['do_m_img'];
			
			if($do_img != '') {			
				@unlink($GP -> UP_donation.$do_img);
			}					
			
			if($do_m_img != '') {			
				@unlink($GP -> UP_donation.$do_m_img);
			}
			$rst = $C_donation -> donation2_Del($args);
		}		
		echo "true";
		exit();
	
	break;

    
	
	case 'donation3_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['do_idx'] 	= $do_idx;
		$result = $C_donation ->donation3_Info($args);
		
		if($result) {			
			$do_img = $result['do_img'];
			$do_m_img = $result['do_m_img'];
			
			if($do_img != '') {			
				@unlink($GP -> UP_donation.$do_img);
			}					
			
			if($do_m_img != '') {			
				@unlink($GP -> UP_donation.$do_m_img);
			}
			$rst = $C_donation -> donation3_Del($args);
		}		
		echo "true";
		exit();
	
	break;

	
	case 'donation3_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		

		
		$args = "";
        $args['do_year'] 		= addslashes($do_year);
        $args['do_gubun'] 		= addslashes($do_gubun);
        $args['do_day'] 		= addslashes($do_day);
        $args['do_show'] 		= addslashes($do_show);
        $args['do_type']					= $do_type;
		$args['do_content'] 		= $C_Func->enc_contents($do_content);		

		$rst = $C_donation -> donation3_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
    break;
    
    case 'donation3_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
				
		$args = "";
        $args['do_idx'] 					= $do_idx;
        $args['do_gubun'] 					= $do_gubun;
        $args['do_year'] 		= addslashes($do_year);
        $args['do_day'] 		= addslashes($do_day);	
		$args['do_content'] 			= $C_Func->enc_contents($do_content);	
		$args['do_show'] 					= $do_show;
		$args['do_type']					= $do_type;

		$rst = $C_donation -> donation3_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;

	
}
?>