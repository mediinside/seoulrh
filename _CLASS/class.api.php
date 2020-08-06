<?
CLASS Api extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	// desc	 : 신주소 리스트
	// auth  : JH 2013-11-28 목요일
	// param
	function New_Addr_list($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		/*
		$args = '';
		$args['rd_type'] = $type;
		$args['sido'] = $GP -> SIDO_ARRAY[$sido];
		$args['gugun'] = $gugun;
		$args['dong'] = $dong;
		$args['dong_jibun'] = $dong_jibun;
		$args['load'] = $load;
		$args['load_jibun'] = $load_jibun;
		*/
		
		$table = "tblzipcode_" . $sido;		
		
		if($type == "D") {
			
			if($dong_jibun == '지번') {
				$dong_jibun = '';
			}
			
			$arr_jibun = explode('-', $dong_jibun);
			
			$qry = "
				select 
					zc_post, zc_sido, zi_gugun, zi_doro, zi_build_num1, zi_law, zi_zibun, zi_zibun_bu 
				from 
					" . $table . " 
				where 
					zi_gugun = '$gugun' 
					and zi_law like '%$dong%' 
			";
			
			if($arr_jibun[0] != '') {
				$qry .= "	and (zi_zibun = '" . $arr_jibun[0]. "' or zi_zibun = '" . $arr_jibun[1]. "')
					and (zi_zibun_bu = '" . $arr_jibun[0]. "' or zi_zibun_bu = '" . $arr_jibun[1]. "')				
				";	
			}
		}else if($type == "R") {
			
			if($load_jibun == "건물번호") {
				$load_jibun = '';
			}
			$qry = "
				select 
					zc_post, zc_sido, zi_gugun, zi_doro, zi_build_num1, zi_law, zi_zibun, zi_zibun_bu 
				from 
					" . $table . " 
				where 
					zi_gugun = '$gugun' 
					and zi_doro like '%$load%'
			";
			
			if($load_jibun != '') {
				 $qry .=	" and (zi_build_num1 like '$load_jibun' or zi_build_num2 like '$load_jibun' or zi_build_num3 like '$load_jibun') ";
			}
		}else{
			$qry = "
				select 
					zc_post, zc_sido, zi_gugun, zi_doro, zi_build_num1, zi_law, zi_zibun, zi_zibun_bu 
				from 
					" . $table . " 
				where 
					zi_gugun = '$gugun' 
					and zi_gugun_build like '%$build_name%'
			";	
		}
		
		//echo $qry;
		
		$rst = $this->DB->execSqlAssoc($qry);
		return $rst;
		
	}
	
	// desc	 : SMS발송 리스트
	// auth  : JH 2013-11-28 목요일
	// param
	function SMS_Send_List($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if (($s_date && $e_date) && ($s_date < $e_date)) {			
			$addQry .= " AND ";
			$addQry .= " ssl_senddate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		$qry = "select * from " . $table ." where ssa_idx='$ssa_idx' $addQry order by ssl_senddate desc limit $offset, $limit";
		$rst	= $this->DB->execSqlAssoc($qry);
		
		$qry1 = "SELECT count(*) as cnt FROM " . $table ." where ssa_idx='$ssa_idx' $addQry ";		
		$tcnt = $this->DB->execSqlOneCol($qry1);
		
		$rst['totalcnt'] = $tcnt;		
		return $rst;
	}
	
	// desc	 : SMS발송 기록
	// auth  : JH 2013-11-28 목요일
	// param
	function SMS_Send_Insert($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			insert into " . $table ." (ssa_idx, ssl_content, ssl_sendnum,ssl_result, ssl_senddate) 
			values ('$ssa_idx','$message','$send_num','$result', NOW())
		";
		$rst =  $this -> DB -> execSqlInsert($qry);
		
		//보낸수 만큼 보유건수 감소
		//$qry1 = "update tblSmsAccount Set ssa_num = ssa_num - 1 where ssa_idx = '$ssa_idx'";
		//$rst1 =  $this -> DB -> execSqlUpdate($qry1);
		
		return $rst;
	}
	function SMS_Send_Insert2($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			insert into " . $table ." (ssa_idx, ssl_content, ssl_sendnum,ssl_result, ssl_senddate) 
			values ('$ssa_idx','$ssl_content','1','$ssl_result', NOW())
		";
		// echo $qry;
		// exit;
		$rst =  $this -> DB -> execSqlInsert($qry);
		
		//보낸수 만큼 보유건수 감소
		//$qry1 = "update tblSmsAccount Set ssa_num = ssa_num - 1 where ssa_idx = '$ssa_idx'";
		//$rst1 =  $this -> DB -> execSqlUpdate($qry1);
		
		return $rst;
	}
	
	// desc	 : 테이블 생성
	// auth  : JH 2013-11-28 목요일
	// param
	function Creat_Sms_Table($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			CREATE TABLE 
				". $table ."
				( 
					 ssl_idx int(11) NOT NULL auto_increment primary key, 
					 ssa_idx int(11), 										 
					 ssl_content text, 
					 ssl_sendnum int(11), 
					 ssl_result varchar(100), 
					 ssl_senddate datetime
				)
		";
		$rst =  $this -> DB -> execSqlQry($qry);
		return $rst;
	}
	
	// desc	 : 아이디가 있는지 확인
	// auth  : JH 2013-11-28 목요일
	// param
	function Api_Biz_Info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select ssa_pwd, ssa_num, ssa_idx, ssa_mobile from tblSmsAccount where ssa_id='$login_id'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : SMS 남은 건수 
	// auth  : JH 2013-11-28 목요일
	// param
	function Api_Sms_Remain_Num($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$sms_url = "http://sslsms.cafe24.com/sms_remain.php"; // SMS 잔여건수 요청 URL
  	$sms['user_id'] = base64_encode($this->GP-> SMS_ID); //SMS 아이디.
    $sms['secure'] = base64_encode($this->GP-> SMS_AUTH_KEY) ;//인증키	
		
		$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		
		$host_info = explode("/", $sms_url);
		$host = $host_info[2];
		$path = $host_info[3]."/".$host_info[4];
		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
		
		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
		
		// 본문 생성
		foreach($sms AS $index => $value){
				$data .="--$boundary\r\n";
				$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
				$data .= "\r\n".$value."\r\n";
				$data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($data) . "\r\n\r\n";
		
		$fp = fsockopen($host, 80);
		
		if ($fp) {
				fputs($fp, $header.$data);
				$rsp = '';
				while(!feof($fp)) {
						$rsp .= fgets($fp,8192);
				}
				fclose($fp);
				$msg = explode("\r\n\r\n",trim($rsp));
				$Result = $msg[1]; //잔여건수
				$return_msg = $Result;
		}
		else {
				$return_msg =  "Connection Failed";
		}
		return $return_msg;
	}
	
	
	// desc	 : SMS 발송(카페24에 소켓)
	// auth  : JH 2013-11-28 목요일
	// param
	function Api_Sms_Send($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		/******************** 인증정보 ********************/
    $sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
		// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
    $sms['user_id'] = base64_encode($this->GP-> SMS_ID); //SMS 아이디.
    $sms['secure'] = base64_encode($this->GP-> SMS_AUTH_KEY) ;//인증키		
    $sms['msg'] = base64_encode(stripslashes($message));
    $sms['rphone'] = base64_encode($rphone);
    $sms['sphone1'] = base64_encode($sphone1);
    $sms['sphone2'] = base64_encode($sphone2);
    $sms['sphone3'] = base64_encode($sphone3);
    $sms['rdate'] = base64_encode($rdate);
    $sms['rtime'] = base64_encode($rtime);
    $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
    $sms['returnurl'] = base64_encode($returnurl);
    $sms['testflag'] = base64_encode($testflag);
    $sms['destination'] = urlencode(base64_encode($destination));
    $sms['repeatFlag'] = base64_encode($repeatFlag);
    $sms['repeatNum'] = base64_encode($repeatNum);
    $sms['repeatTime'] = base64_encode($repeatTime);

    $host_info = explode("/", $sms_url);
    $host = $host_info[2];
    $path = $host_info[3]."/".$host_info[4];

    srand((double)microtime()*1000000);
    $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
    //print_r($sms);

    // 헤더 생성
    $header = "POST /".$path ." HTTP/1.0\r\n";
    $header .= "Host: ".$host."\r\n";
    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

    // 본문 생성
    foreach($sms AS $index => $value){
        $data .="--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
        $data .= "\r\n".$value."\r\n";
        $data .="--$boundary\r\n";
    }
    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

    $fp = fsockopen($host, 80);

    if ($fp) { 
        fputs($fp, $header.$data);
        $rsp = '';
        while(!feof($fp)) { 
            $rsp .= fgets($fp,8192); 
        }
        fclose($fp);
        $msg = explode("\r\n\r\n",trim($rsp));
				
        $rMsg = explode(",", $msg[1]);
        $Result= $rMsg[0]; //발송결과
        $Count= $rMsg[1]; //잔여건수

        //발송결과 알림
				
				$return_msg = $Result;
				/*
        if($Result=="success") {
            $alert = "성공";
            $alert .= " 잔여건수는 ".$Count."건 입니다.";
        }
        else if($Result=="reserved") {
            $alert = "성공적으로 예약되었습니다.";
            $alert .= " 잔여건수는 ".$Count."건 입니다.";
        }
        else if($Result=="3205") {
            $alert = "잘못된 번호형식입니다.";
        }
				else if($Result=="0044") {
            $alert = "스팸문자는발송되지 않습니다.";
        }		
        else {
            $alert = "[Error]".$Result;
        }
				*/
    }
    else {
				/*
        $alert = "Connection Failed";
				*/
				$return_msg = "ConnFail";
    }
		return $return_msg;
		//exit();
	}
	
}
?>