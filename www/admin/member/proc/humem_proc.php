<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.member.php");
include_once($GP -> CLS . "class.mail.php");
$C_Member 	= new Member;

//print_r($_POST);


switch($_POST['mode']){

	case 'HUEMAIL_USER_DEL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = '';
		$args['arr_idx'] = $arr_idx;
		$rst = $C_Member -> Mem_Info_Del_Real_Sel($args);
		
		echo "true";
		exit();
	break;

	case 'MEM_DEL_REAL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['mb_code'] 	= $mb_code;
		$rst = $C_Member -> Mem_Info_Del_Real($args);
		
		echo "true";
			exit();
	break;	

	case 'HUEMAIL_ALL' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$now_day = date('Y-m-d');
		$after_day = $C_Func->request_add_day($now_day, '29');
		$arr_day = explode("-",$after_day);
		$after_one_year = $arr_day[0] . "년 " . $arr_day[1] ."월 " . $arr_day[2] ."일";

		$before_one_year = $C_Func->request_minus_day($now_day, '365');

		$args = '';
		$args['ck_year'] = $before_one_year;
		$data = $C_Member->HuMail_info_All($args);

		$test_j = 0;
		$test_i = 0;

		$sender_email = $GP -> Admin_Email;
		$sender_name = $GP -> Admin_HP_NAME;
		
		for($i=0; $i<count($data); $i++) {
			$idx = $data[$i]['mb_code'];
			$rc_email = $data[$i]['mb_email'];
			$rc_name = $data[$i]['mb_name'];

			$C_SendMail = new SendMail();
			$C_SendMail -> setUseSMTPServer(false);
			$C_SendMail -> setSMTPServer($GP -> SMTP_SERVER, $GP -> SMTP_PORT);
			$C_SendMail -> setSMTPUser($GP -> SMTP_USER);
			$C_SendMail -> setSMTPPasswd($GP -> SMTP_PASS);

			$C_SendMail->addTo($rc_email, $rc_name); 

			$email_subject = "휴면계정 개인정보 파기에 대한 사전 안내";

			$mailFormDir = @file_get_contents($GP -> HOME."admin/member/humail.html");
			$contents = str_replace("@@imsi_dayTxt@@","$after_one_year", $mailFormDir);

			$C_SendMail -> setSubject($email_subject);
			$C_SendMail -> setMailBody($contents, true);
			$C_SendMail -> setFrom($sender_email, $sender_name);
			$C_SendMail -> addTo($receive_email, $receive_name);

			$sendRst = $C_SendMail->send();

			//print_r($sendRst);

			$send_rst = 0;
			if ($sendRst) {
				$send_rst = 1;
				$args['mb_code'] = $idx;
				$C_Member->HUMAIL_SEND_UPDATE($args);

				$test_i++;
			}
			$C_SendMail = '';
			usleep(100);
			$test_j++;
		}

		echo $msg = $test_j . '건중 ' . $test_i . '건의 메일을 발송하였습니다.';
		$C_Func->put_msg_and_go($msg, 'mem_hu_list.php');

	break;
	
	case 'HUEMAIL_SEND' :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;

		$now_day = date('Y-m-d');
		$after_day = $C_Func->request_add_day($now_day, '29');
		$arr_day = explode("-",$after_day);
		$after_one_year = $arr_day[0] . "년 " . $arr_day[1] ."월 " . $arr_day[2] ."일";
			
		$args = '';
		$args['arr_idx'] = $arr_idx;
		$data = $C_Member->HuMail_info_Sel($args);

		
		$test_j = 0;
		$test_i = 0;

		$sender_email = $GP -> Admin_Email;
		$sender_name = $GP -> Admin_HP_NAME;
		
		for($i=0; $i<count($data); $i++) {
			$idx = $data[$i]['mb_code'];
			$rc_email = $data[$i]['mb_email'];
			$rc_name = $data[$i]['mb_name'];

			$C_SendMail = new SendMail();
			$C_SendMail -> setUseSMTPServer(false);
			$C_SendMail -> setSMTPServer($GP -> SMTP_SERVER, $GP -> SMTP_PORT);
			$C_SendMail -> setSMTPUser($GP -> SMTP_USER);
			$C_SendMail -> setSMTPPasswd($GP -> SMTP_PASS);

			$C_SendMail->addTo($rc_email, $rc_name); 

			$email_subject = "휴면계정 개인정보 파기에 대한 사전 안내";

			$mailFormDir = @file_get_contents($GP -> HOME."admin/member/humail.html");
			$contents = str_replace("@@imsi_dayTxt@@","$after_one_year", $mailFormDir);

			$C_SendMail -> setSubject($email_subject);
			$C_SendMail -> setMailBody($contents, true);
			$C_SendMail -> setFrom($sender_email, $sender_name);
			$C_SendMail -> addTo($receive_email, $receive_name);

			$sendRst = $C_SendMail->send();

			$send_rst = 0;
			if ($sendRst) {
				$send_rst = 1;
				$args['mb_code'] = $idx;
				$C_Member->HUMAIL_SEND_UPDATE($args);

				$test_i++;
			}
			$C_SendMail = '';
			usleep(100);
			$test_j++;
		}

		echo $msg = $test_j . '건중 ' . $test_i . '건의 메일을 발송하였습니다.';
		$C_Func->put_msg_and_go($msg, '../mem_hu_list.php');
	break;
}

?>