<?
	include_once("../../_init.php");
	include_once($GP -> CLS."/class.sms.php");
	$C_Sms 	= new Sms;
	
	
	//보낼 데이터
	$data = array(
		'type' => 'send',
		'msg' => "그 사건은 바람이 확실합니다.",  
		'sm_to' => "010-9728-1159",
		'destination' => '',
		'sm_from1' => '010',
		'sm_from2' => '2615',
		'sm_from3' => '7283',
		'send_date' => '',
		'send_time' => '',
		'returnurl' => '',
		'testflag' => 'N',			//Y:실방송 N:테스트발송
		'repeatFlag' => '',
		'repeatNum' => '1',
		'repeatTime' => '15',			
		'send_num' => '1',
		'return_type' => 'json',			
		'login_id' => 'hwcsms',   
		'login_pwd' => 'accc53ca4405d1e2d2917d9444b9759a' 	
	);		
	
		
	print_r($data);
	exit();
	
	// Send a request to example.com 
	$result = $C_Func->post_request('http://webzinem.cafe24.com/api/sms_api.html', $data); 
	
	print_r($result);
	exit();
	
	if ($result['status'] == 'ok'){ 
		$re_msg = $result['content'];			
		$obj_result = json_decode($re_msg); 	
		
		$arr = array('msg'=> $obj_result->msg, 'error' => $obj_result->error);
		echo json_encode($arr);
		exit();			
	} else { 
		$arr = array('msg'=>'false', 'error' => $result['error']);
		echo json_encode($arr);
		exit();			
	} 
?>