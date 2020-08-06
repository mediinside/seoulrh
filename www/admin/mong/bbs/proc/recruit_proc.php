<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.recruit.php");
include_once($GP -> CLS . "class.sms.php");
include_once($GP -> CLS . "class.api.php");
include_once($GP -> CLS . "class.mail.php");

$C_Sms 	= new Sms;
$C_Api = new Api;
$C_Recruit 	= new Recruit;

error_reporting(E_ALL);
ini_set("display_errors", 1);

//발송히스토리 기억
	function SMS_send_history($args) {
		global $C_Api, $GP, $C_Func;		
	
		$year = date("Y");		//년
		$table = "tblSmsSendList_". $year;	 //생성테이블명		
		$ck_table = $C_Func->TableExists($table, $GP->DB_TABLE);	//테이블 존재여부		
		
		if($ck_table)	{ //테이블이 존재하면 등록
			$args['table'] = $table;
			$rst = $C_Api -> SMS_Send_Insert($args);
		}else{
			$args['table'] = $table;
			$rst = $C_Api -> Creat_Sms_Table($args);
			
			if($rst) {
				$rst = $C_Api -> SMS_Send_Insert($args);
			}
		}		
	}

switch($_POST['mode']){

	case "RECRUIT_EMAIL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$arr_email = explode(",", $receive_email);
		$arr_name = explode(",", $receive_name);
		
		$test_j = 0;
		$test_i = 0;
		
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
			//$C_SendMail -> addTo($receive_email, $receive_name);

			$sendRst = $C_SendMail->send();

			$send_rst = 0;
			if ($sendRst) {
				$send_rst = 1;
				$test_i++;
			}
			$C_SendMail = '';
			usleep(100);
			$test_j++;
		}

		$msg = $test_j . '건중 ' . $test_i . '건의 메일을 발송하였습니다.';

		$C_Func->put_msg_and_modalclose($msg);		
		exit();
	break;

	case "SMS_SEND" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		if($txtReceiver != "")
		{
			$receiver_phone = $txtReceiver;
			$send_num = 1;
		}else{
			$receiver_phone = str_replace(";",",",$phone);
			$Arr_receiver = explode(";", $phone);
			$send_num = count($Arr_receiver);
		}
		
		$arr_s_user = explode('-', $txtSender);
		
		$args = '';
		$args['message'] 	= $txtContent;
		$args['rphone'] 	= $receiver_phone;
		$args['sphone1'] 	= $arr_s_user[0];
		$args['sphone2'] = $arr_s_user[1];
		$args['sphone3'] = $arr_s_user[2];
		$args['rdate'] = $txtdate;
		$args['rtime'] = $txttime;
		$args['returnurl'] = '';
		$args['testflag'] = 'N';
		$args['destination'] = '';
		$args['repeatFlag'] = '';
		$args['repeatNum'] = '1';
		$args['repeatTime'] = '15';
		$args['send_num'] = $send_num;	
		
		$rst = $C_Api -> Api_Sms_Send($args);	
				
		$args['result'] = $rst;				
		$args['ssa_idx'] = '9999';			
		SMS_send_history($args);	//발송기록 데이터베이스에 기록	
		
		if($rst == "success" || $rst == "reserved") {								
			$arr = array('msg'=>'true');						
			echo json_encode($arr);
			exit();	
		}else{
			$arr = array('msg'=>'false', 'error'=> $re_msg);			
			echo json_encode($arr);			
			exit();	
		}		
	break;

	case "RECRUIT_PASS_Y":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;

		$args = '';
		$args['arr_idx'] = $arr_idx;
		$rst = $C_Recruit ->Recruit_Pass_Y($args);
		
		echo "true";
		exit();
	
	break;

	case "RECRUIT_PASS_N":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;

		$args = '';
		$args['arr_idx'] = $arr_idx;
		$rst = $C_Recruit ->Recruit_Pass_N($args);
		
		echo "true";
		exit();
	
	break;

	case "RECRUIT_DEL_ALL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;

		$args = '';
		$args['arr_idx'] = $arr_idx;
		$rst = $C_Recruit ->Recruit_Del_All($args);
		
		echo "true";
		exit();
	
	break;

	
	case "RECRUIT_SMS" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['rc_idx'] 	= $_POST['rc_idx'];
		$rst = $C_Recruit -> Recruit_info($args);	
		
		if($rst) {
			extract($rst);			
			
			$msg = $rc_name ."님 윌스기념병원 입사지원이 접수되었습니다";

			$args = '';
			$args['message'] 	= $msg;
			$args['rphone'] 	= $rc_mobile;
			$args['sphone1'] 	= "031";
			$args['sphone2'] 	= "240";
			$args['sphone3'] 	= "6245";
			$args['rdate'] = '';
			$args['rtime'] = '';
			$args['returnurl'] = '';
			$args['testflag'] = 'N';
			$args['destination'] = '';
			$args['repeatFlag'] = '';
			$args['repeatNum'] = '1';
			$args['repeatTime'] = '15';
			$args['send_num'] = 1;	
			
			$rst1 = $C_Api -> Api_Sms_Send($args);	
					
			$args['result'] = $rst1;				
			$args['ssa_idx'] = '9999';			
			SMS_send_history($args);	//발송기록 데이터베이스에 기록	 
			
			if($rst1 == "success") {
				$args = "";
				$args['rc_idx'] 	= $_POST['rc_idx'];
				$rst= $C_Recruit->Recruit_SMS_Result($args); 
			}
		}
		
		echo "true";
		exit();
	break;
	
	case "RECRUIT_DEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
		$args = "";
		$args['rc_idx'] 	= $_POST['rc_idx'];		
		$rst = $C_Recruit ->Recruit_Del($args);
		
		echo "true";
		exit();
	
	break;
	
	case 'RECRUIT_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";		
		$args['rc_idx']			= $rc_idx;      
		$args['rc_name']		= $rc_name;
		$args['rc_sex']			= $rc_sex;
		$args['rc_birth']		= $rc_birth;
		$args['rc_age']			= $rc_age;
		$args['rc_mobile']	= $rc_mobile;
		$args['rc_email']		= $rc_email;
		$args['rc_post']		= $rc_post1. "-" . $rc_post2;
		$args['rc_addr1']		= $rc_addr1;
		$args['rc_addr2']		= $rc_addr2;
		$args['rc_pass']		= $rc_pass;
		
		$rst = $C_Recruit -> Recruit_Modify($args);
		
		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;
	
	
	

	
	
	
}

?>