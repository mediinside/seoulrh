<?php
include_once("../../../_init.php");
include_once($GP -> CLS."/class.sms.php");
include_once($GP -> CLS."/class.api.php");
$C_Sms 	= new Sms;
$C_Api = new Api;


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
	
	case "SMS_CLIENT_DEL" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		$args = "";
		$args['ssa_idx'] = $ssa_idx;
		$rst = $C_Sms -> Sms_Client_Del($args);
		
		echo "true";
		exit();
	break;
	
	case "SMS_CLIENT_REG" :	
		
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
		$args = "";
		$args['ssa_id'] = $ssa_id;
		$args['ssa_pwd'] = $ssa_pwd;
		$args['ssa_name'] = $ssa_name;
		$args['ssa_email'] = $ssa_email;
		$args['ssa_mobile'] = $ssa_mobile;
		$args['ssa_num'] = $ssa_num;
		$rst = $C_Sms -> Sms_Client_Reg($args);
			
		$C_Func->put_msg_and_modalclose("등록 되었습니다");		
		exit();
	break;
	
	

	case "SMS_CLIENT_MODI" :	
		
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
		$args = "";
		$args['ssa_idx'] = $ssa_idx;
		$args['ssa_id'] = $ssa_id;
		$args['ssa_pwd'] = $ssa_pwd;
		$args['ssa_name'] = $ssa_name;
		$args['ssa_email'] = $ssa_email;
		$args['ssa_mobile'] = $ssa_mobile;
		$args['ssa_num'] = $ssa_num;
		$rst = $C_Sms -> Sms_Client_Modi($args);
			
		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();
	break;
	
	case "SMS_STEUP_REG":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
			$args = "";
			$args['tss_name'] = $tss_name;
			$args['tss_moible'] = $tss_mobile1 . "-". $tss_moible2 . "-" . $tss_mobile3;
			$rst = $C_Sms -> Sms_Setup_Reg($args);
			
			$C_Func->put_msg_and_go("설정 되었습니다","../sms_setup.php?tab=1");		
			exit();
	break;
	
	case "SMS_STEUP_MODI":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;			

			$args = "";
			$args['tss_idx'] = $tss_idx;
			$args['tss_name'] = $tss_name;
			$args['tss_moible'] = $tss_mobile1 . "-". $tss_mobile2 . "-" .$tss_mobile3;			
			$rst = $C_Sms -> Sms_Setup_Modi($args);
			
			$C_Func->put_msg_and_go("설정 되었습니다","../sms_setup.php?tab=1");		
			exit();
	break;
	
	
	case "MEM_MOVE":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
			$args = "";
			$arr_val = explode(',', $chkval);
			
			for($i=0; $i < count($arr_val)-1; $i++)
			{
				$args['mb_code'] = $arr_val[$i];
				$args['gr_code'] = $group_code;
				$rst = $C_Sms -> Sms_Mem_Move($args);
			}
			
			echo "true";
			exit();
	break;
	
	case "MEM_DEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
			$args = "";
			$args['mb_code'] = $mb_code;
			$rst = $C_Sms -> Sms_Mem_Del($args);
			
			echo "true";
			exit();
	break;
	
	case "MEM_MODI":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
			$args = "";
			$args['mb_code'] = $mb_code;
			$args['mb_name'] = $mb_name;
			$args['mb_mobile'] = $mb_mobile;
			$rst = $C_Sms -> Sms_Mem_Modi($args);
			
			echo "true";
			exit();
	break;
	
	case "GROUP_DEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
			$args = "";
			$args['gr_code'] = $group_code;
			$rst = $C_Sms -> Sms_Group_Del($args);

			
			echo "true";
			exit();
		
	break;
	
	case "GROUP_MOVE":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;	
		
			$args = "";
			$arr_val = explode(',', $chkval);
			
			for($i=0; $i < count($arr_val)-1; $i++)
			{
				$args['bf_gr_code'] =  $arr_val[$i];
				$args['gr_code'] = $group_code;
				$rst = $C_Sms -> Sms_Group_Move($args);
			}
			
			echo "true";
			exit();
		
	break;
	
	case "GROUP_MODI":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = '';		
		$args['gr_code'] = $group_code;
		$be_rst = $C_Sms->Sms_Group_info($args);
		
		if($be_rst['gr_name'] == rawurldecode($group_name)){
			echo "true";
			exit();
		}else{
			$args['gr_name'] = rawurldecode($group_name);
			$rst1 = $C_Sms->Sms_Chk_Group($args);
			
			if($rst1['cnt'] > 0) {
				echo "false1";
				exit();
			}
			
			$rst = $C_Sms -> Sms_Group_Modi($args);			
			echo "true";
			exit();
			
		}		
	break;
	
	case "GROUP_REG":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
			
		$args = '';
		$args['gr_name'] = rawurldecode($group_name);
		$rst1 = $C_Sms->Sms_Chk_Group($args);
		
		if($rst1['cnt'] > 0) {
			echo "false1";
			exit();
		}
				
		$rst = $C_Sms -> Sms_Group_Reg($args);
		
		if($rst) {
			echo "true";
			exit();
		}else{
			echo "false";
			exit();
		}		
			
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
	
	
	case "SMS_SEND_DEL" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = "";
		$args['ssl_idx'] = $ssl_idx;
		$args['table'] ="tblSmsSendList_". $year;
		$rst = $C_Sms -> Sms_Send_Del($args);
		
		echo "true";
		exit();
	break;
	
}
?>