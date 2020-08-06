<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.cooperative.php");
$C_Cooperative 	= new Cooperative;


switch($_POST['mode']){	

	case "COOPERATIVE_DEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['cp_idx'] 	= $cp_idx;
		$rst = $C_Cooperative ->cooperative_Info_Del($args);
		
		echo "true";
			exit();
	break;


	case 'COOPERATIVE_MODI' :
        if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
        
        include_once($GP->CLS."class.fileup.php");
		
		//사진 업로드	
		$file_orName			= "cp_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
        $insertFileCheck	= false;
        
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_cooperative;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array("jpg","gif","png","bmp");
			$args_f['image_w'] 					= 280;
            $args_f['image_h'] 					= 300;      


			$C_Fileup = new Fileup($args_f);
			$updata		= $C_Fileup -> fileUpload();
			
			$image_main = $updata['new_file_name'];	//변경된 파일명
		}else {
			$image_main				= $before_image_main;
        }	

        
	
	
        $args = "";
        $args['cp_idx'] 			= $cp_idx;
        $args['cate1'] 		    	= $cate1;
        $args['cp_img'] 			= $image_main;
        $args['cp_name'] 			= $cp_name;
		$args['cp_place'] 			= $cp_place;
		$args['cp_addr'] 			= $cp_addr;		
        $args['cp_phone'] 			= $cp_phone;
        $args['cp_link'] 			= $cp_link;

        //print_r($args);
        //exit;

		$rst = $C_Cooperative ->cooperative_Info_Modi($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("수정 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("수정에 실패하였습니다");
		}
		exit();
	break;

	case 'COOPERATIVE_REG' :
        if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
        
        include_once($GP->CLS."class.fileup.php");
		
		//사진 업로드
		$file_orName			= "cp_img";
		$is_fileName			= $_FILES[$file_orName]['name'];
		$insertFileCheck	= false;
		if ($is_fileName) {
			$args_f = "";
			$args_f['forder'] 					= $GP -> UP_cooperative;
			$args_f['files'] 						= $_FILES[$file_orName];
			$args_f['max_file_size'] 		= 1024 * 5000;// 500kb 이하
			$args_f['able_file'] 				= array("jpg","gif","png","bmp");
			$args_f['image_w'] 					= 280;
            $args_f['image_h'] 					= 400;
            
            //print_r($args_f);

			$C_Fileup = new Fileup($args_f);
            $updata		= $C_Fileup -> fileUpload();	
                     
            $image_main = $updata['new_file_name'];

			if ($updata['error']) $insertFileCheck = true;			
				//변경된 파일명
         }
     
	
        $args = "";
        $args['cate1'] 		    	= $cate1;
        $args['cp_img'] 			= $image_main;
        $args['cp_name'] 			= $cp_name;
		$args['cp_place'] 			= $cp_place;
		$args['cp_addr'] 			= $cp_addr;		
        $args['cp_phone'] 			= $cp_phone;
        $args['cp_link'] 			= $cp_link;

		$rst = $C_Cooperative ->cooperative_Info_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;


















	case "COOPERATIVE_DEL_NEW":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['cp_idx'] 	= $cp_idx;
		$rst = $C_Cooperative ->cooperative_Info_Del_New($args);
		
		echo "true";
			exit();
	break;


	case 'COOPERATIVE_MODI_NEW' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
	
		$args = "";
		$args['cp_idx'] 				= $cp_idx;
		$args['cate1'] 					= $cate1;
		$args['cate2'] 					= $cate2;
		$args['cp_name'] 			= $cp_name;
		$args['cp_place'] 				= $cp_place;
		$args['cp_addr'] 				= $cp_addr;
		$args['np_gubun'] 			= $np_gubun;		
		$args['cp_phone'] 			= $cp_phone;
		$args['np_row_price'] 	= $np_row_price;
		$args['np_high_price'] 	= $np_high_price;
		$args['np_percent'] 		= $np_percent;
		$args['np_ck1'] 				= $np_ck1;
		$args['np_ck2'] 				= $np_ck2;
		$args['np_gita'] 				= $np_gita;

		$rst = $C_Cooperative ->cooperative_Info_Modi_New($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("수정 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("수정에 실패하였습니다");
		}
		exit();
	break;


	case 'COOPERATIVE_REG_NEW' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
	
		$args = "";
		$args['cate1'] 					= $cate1;
		$args['cate2'] 					= $cate2;
		$args['cp_name'] 			= $cp_name;
		$args['cp_place'] 				= $cp_place;
		$args['cp_addr'] 				= $cp_addr;
		$args['np_gubun'] 			= $np_gubun;		
		$args['cp_phone'] 			= $cp_phone;
		$args['np_row_price'] 	= $np_row_price;
		$args['np_high_price'] 	= $np_high_price;
		$args['np_percent'] 		= $np_percent;
		$args['np_ck1'] 				= $np_ck1;
		$args['np_ck2'] 				= $np_ck2;
		$args['np_gita'] 				= $np_gita;

		$rst = $C_Cooperative ->cooperative_Info_Reg_New($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;






}
?>