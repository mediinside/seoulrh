<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.member.php");
include_once($GP -> CLS . "class.mail.php");
$C_Member 	= new Member;


switch($_POST['mode']){

	case 'MEM_PWD_CK' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
	
		$mb_pwd = trim($mb_pwd);
	
		$args = "";
		$args['mb_code'] 				= $mb_code;
		$args['mb_password']  = md5($mb_pwd);
		$rst = $C_Member -> Mem_Admin_Pwd_CK($args);
		
		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;

	case "MEM_EMAIL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;

		
		if($type == "S" || $type == "P") {
			$arr_email = explode(",", $receive_email);
			$arr_name = explode(",", $receive_name);
		}else {
			$args = '';
			$args['mb_level'] = $mb_level;
			$rst = $C_Member -> Mem_Group_All($args);

			$arr_email = array();
			$arr_name = array();
			for($i=0; $i<count($rst); $i++) {
				$arr_email[] = $rst[$i]['mb_email'];
				$arr_name[] = $rst[$i]['mb_name'];
			}
		}
		
		$test_j = 0;
		$test_i = 0;


		echo "메일이 현재 발송중입니다. 잠시 기다려주세요";
		
		for($i=0; $i<count($arr_email); $i++) {
			$rc_email = $arr_email[$i];
			$rc_name = $arr_name[$i];

			$C_SendMail = new SendMail();
			$C_SendMail -> setUseSMTPServer(true);
			$C_SendMail -> setSMTPServer($GP -> SMTP_IP, $GP -> SMTP_PORT);
			$C_SendMail -> setSMTPUser($GP -> SMTP_USER);
			$C_SendMail -> setSMTPPasswd($GP -> SMTP_PASS);

			$C_SendMail->addTo($rc_email, $rc_name); 

			$C_SendMail -> setSubject($email_subject);
			$C_SendMail -> setMailBody($email_content, true);
			$C_SendMail -> setFrom($sender_email, $sender_name);
			$C_SendMail -> addTo($receive_email, $receive_name);

			$sendRst = $C_SendMail->send();

			$send_rst = 0;
			if ($sendRst) {
				$send_rst = 1;
				$test_i++;
			}
			$C_SendMail = '';
			usleep(500000);
			$test_j++;
		}

		$msg = $test_j . '건중 ' . $test_i . '건의 메일을 발송하였습니다.';
		
		$C_Func->put_msg_and_modalclose($msg);		
		exit();
	break;

	case 'MEM_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['mb_code'] 	= $mb_code;
		$rst = $C_Member -> Mem_Info_Del($args);
		
		echo "true";
			exit();
	break;	

	case 'MEM_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
	
		$args = "";
		$args['mb_code'] 				= $mb_code;
		$args['mb_name'] 				= $mb_name;
		$args['mb_email'] 				= $mb_email;		
		$args['mb_birthday'] 			= $mb_birthday;
		$args['mb_address1'] 			= $m_addr;
		$args['mb_address2'] 			= $m_addr_sub;
		$args['mb_load_address1'] 			= $mb_load_addr1;
		$args['mb_load_address2'] 			= $mb_load_addr2;
		$args['mb_sex'] 				= $mb_sex;
		$args['mb_zip_code'] 			= $m_post1 . "-". $m_post2;
		$args['mb_mobile'] 				= $m_mobile1 . "-". $m_mobile2 . "-". $m_mobile3;
		$rst = $C_Member -> Mem_Info_Modify($args);
		
		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;
	
}
?>
