<?php
include_once("../../../_init.php");
include_once $GP -> CLS . 'class.reserve.php';
include_once $GP -> CLS . 'class.doctor.php';
include_once($GP -> CLS . "class.sms.php");
include_once($GP -> CLS . "class.api.php");
$C_Sms 	= new Sms;
$C_Api = new Api;
$C_Reserve = new Reserve();
$C_Doctor = new Doctor();


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
	
	//이전 예약취소
	case "RESERVE_DEL_OLD":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['idx'] 	= $_POST['idx'];		
		$rst = $C_Reserve ->Reserve_Cancel_Old($args);
		
		echo "true";
		exit();
	brak;
	
	
	//이전 예약수정
	case "RS_MODI_OLD":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['idx'] 	= $_POST['idx'];		
		$args['Reservation_Status'] 	= $_POST['Reservation_Status'];		
		$rst = $C_Reserve ->Reserve_Modi_Old($args);
		
		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();	
	break;
	
	
	
	
	
	
	
	case "RESERVE_DEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['rd_idx'] 	= $_POST['rd_idx'];		
		$rst1 = $C_Reserve ->Reserve_Detail_Adm($args);
		
		if($rst1) {
			extract($rst1);			
			
			$msg = "윌스기념병원 예약 취소 " .  $rd_date . " " . $rd_s_time . " " . $dr_name;

			$args = '';
			$args['message'] 	= $msg;
			$args['rphone'] 	= $mb_mobile;
			$args['sphone1'] 	= "031";
			$args['sphone2'] 	= "240";
			$args['sphone3'] 	= "6000";
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
				$args['rd_idx'] 	= $_POST['rd_idx'];
				$rst= $C_Reserve->Reserve_SMS_Result($args); 
			}
		}
		
		$args = "";
		$args['rd_idx'] 	= $_POST['rd_idx'];		
		$rst = $C_Reserve ->Reserve_Cancel($args);
		
		echo "true";
		exit();
	break;
	
	
	case "RESERVE_SMS" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['rd_idx'] 	= $_POST['rd_idx'];
		$rst = $C_Reserve ->Reserve_Detail_Adm($args);
		
		if($rst) {
			extract($rst);			
			
			$msg = "윌스기념병원 예약 완료 " .  $rd_date . " " . $rd_s_time . " " . $dr_name;

			$args = '';
			$args['message'] 	= $msg;
			$args['rphone'] 	= $mb_mobile;
			$args['sphone1'] 	= "031";
			$args['sphone2'] 	= "240";
			$args['sphone3'] 	= "6000";
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
				$args['rd_idx'] 	= $_POST['rd_idx'];
				$rst= $C_Reserve->Reserve_SMS_Result($args); 
			}
		}
		echo "true";
		exit();
	break;
	


	
	case "RS_MODI" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		

				
		//캡파정보
		
		$args = '';	
		$args['dr_idx'] = $dr_idx;
		$args['cp_idx'] = $cp_idx;	
		$rst = $C_Reserve->Doctor_Time_Info($args);
		$dt_arr = $C_Doctor ->Doctor_Info($args);


		$args = '';      
		$args['rd_idx']			= $rd_idx;
		$args['dr_idx']			= $dr_idx;
		$args['dr_name']		= $dt_arr['dr_name'];
		$args['dr_clinic']	= $dt_arr['dr_clinic'];
		$args['dr_center']	= $dt_arr['dr_center'];
		$args['cp_idx']			= $cp_idx;
		$args['rd_date']		= $rd_date;
		$args['rd_s_time']	= $rst['cp_s_time'];
		$args['rd_e_time']	= $rst['cp_e_time'];
		$args['rd_status']	= $rd_status;		

		$rst = $C_Reserve -> Reserve_Modi($args);

		$C_Func->put_msg_and_modalclose("수정 되었습니다");		
		exit();

	break;
	
	
	case 'HOLIDAY_DEL':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
		$args = '';
		$args['th_idx'] = $th_idx;	
		$rst = $C_Reserve -> HOLIDAY_Del($args);

		echo "true";
		exit();
	break;
	
	
	case 'HOLIDAY_MODI':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		

		
		$args = '';		

		$args['th_date'] 		= $th_date;
		$args['th_con'] 		= $th_con;
		$args['th_idx'] 		= $th_idx;
		
		$rst = $C_Reserve -> HOLIDAY_MODI($args);


		$C_Func->put_msg_and_modalclose("수정 되었습니다");
		exit();
	break;
	
	case 'HOLIDAY_REG':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		

		
		$args = '';		

		$args['th_date'] 		= $th_date;
		$args['th_con'] 		= $th_con;
		
		$rst = $C_Reserve -> HOLIDAY_REG($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	break;
	
	
	//캡파 일별 삭제
	case "CAPA_DAY_DEL_ARR":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = '';
		$args['cp_arr_idx']  = $cp_arr_idx;	
		$rst = $C_Reserve -> Cappa_Info_Del_Arr($args);
		
		echo "true";	
		exit();
	break;
	
	//캡파 일별 삭제
	case "CAPA_DAY_DEL":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = '';
		$args['cp_idx']  = $cp_idx;	
		$rst = $C_Reserve -> Cappa_Info_Del($args);
		
		echo "true";	
		exit();
	break;
	
	
	
	//캡파 일별수정
	case "CAPA_DAY_MODI":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = '';
		$args['cp_s_time'] = $cp_s_time;
		$args['cp_e_time']  = $cp_e_time;		
		$args['cp_idx']  = $cp_idx;	
		$rst = $C_Reserve -> Cappa_Info_Modi($args);
		
		echo "true";	
		exit();
	
	break;
	

	//휴무일 설정으로 인한 일정삭제
	case "RS_HOLIDAY_TIME_ADD":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = '';
		$args['cp_date']  = $cp_date_holiday;	
		$args['dr_idx']  = $dr_idx_holiday;	
		$rst = $C_Reserve -> Cappa_Holiday_Info_Del($args);
		
		$C_Func->put_msg_and_modalclose("휴무일로 설정 되었습니다");
		exit();
	break;

	//휴무일 설정으로 인한 일정 상태값 변경
	case "RS_HOLIDAY_TIME_MODI":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		/*echo "<BR>".$cp_date_holiday;
		echo "<BR>".$dr_idx_holiday;
		echo "<BR>".$status;*/


		$args = '';
		$args['cp_date']  = $cp_date_holiday;	
		$args['dr_idx']  = $dr_idx_holiday;	
		$args['status']  = $status;	
		$rst = $C_Reserve -> Cappa_Holiday_Info_Modi($args);
		echo "true";
		// $C_Func->put_msg_and_modalclose("휴무일로 설정 되었습니다");
		exit();
	break;
	
	
	
	//의료진 일정이 등록되어 있는지 체크
	case 'SCH_DATA_CK':
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = '';
		$args['dr_idx'] = $dr_idx;
		$args['cp_date'] = $s_date;
		$rst = $C_Reserve->Sch_Capa_Data_Chk($args);		
		if($rst['cnt'] > 0) {
			echo "sdate_fail";
			exit();
		}		
		
		$args = '';
		$args['dr_idx'] = $dr_idx;
		$args['cp_date'] = $e_date;
		$rst = $C_Reserve->Sch_Capa_Data_Chk($args);
		if($rst['cnt'] > 0) {
			echo "edate_fail";
			exit();
		}
		
		echo "true";
		exit();
	break;
	
	
	
	//의료진 일정 만들기
	case "SCH_REG":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;				
		
		$args = '';
		$args['dr_idx'] = $dr_idx;

		$args['so_apply_sdate'] = $so_apply_sdate;
		$args['so_apply_edate'] = $so_apply_edate;
		
		$tmp_str = "";
		foreach($so_week_holiday as $key => $val) {			
			$tmp_str .= $val .",";
		}
		$tmp_str = rtrim($tmp_str, ",");		
		$args['so_week_holiday'] = $tmp_str;		

		$args['so_rsinterval_sun'] = $so_rsinterval_sun;
		$args['so_work_stime_sun'] = $so_work_stime_sun;
		$args['so_work_etime_sun'] = $so_work_etime_sun;
		$args['so_ext_stime_sun'] = $so_ext_stime_sun;
		$args['so_ext_etime_sun'] = $so_ext_etime_sun;
	
		$args['so_rsinterval_mon'] = $so_rsinterval_mon;
		$args['so_work_stime_mon'] = $so_work_stime_mon;
		$args['so_work_etime_mon'] = $so_work_etime_mon;
		$args['so_ext_stime_mon'] = $so_ext_stime_mon;
		$args['so_ext_etime_mon'] = $so_ext_etime_mon;
		
		$args['so_rsinterval_tue'] = $so_rsinterval_tue;
		$args['so_work_stime_tue'] = $so_work_stime_tue;
		$args['so_work_etime_tue'] = $so_work_etime_tue;
		$args['so_ext_stime_tue'] = $so_ext_stime_tue;
		$args['so_ext_etime_tue'] = $so_ext_etime_tue;
		
		$args['so_rsinterval_wed'] = $so_rsinterval_wed;
		$args['so_work_stime_wed'] = $so_work_stime_wed;
		$args['so_work_etime_wed'] = $so_work_etime_wed;
		$args['so_ext_stime_wed'] = $so_ext_stime_wed;
		$args['so_ext_etime_wed'] = $so_ext_etime_wed;
		
		$args['so_rsinterval_thu'] = $so_rsinterval_thu;
		$args['so_work_stime_thu'] = $so_work_stime_thu;
		$args['so_work_etime_thu'] = $so_work_etime_thu;
		$args['so_ext_stime_thu'] = $so_ext_stime_thu;
		$args['so_ext_etime_thu'] = $so_ext_etime_thu;
		
		$args['so_rsinterval_fri'] = $so_rsinterval_fri;
		$args['so_work_stime_fri'] = $so_work_stime_fri;
		$args['so_work_etime_fri'] = $so_work_etime_fri;
		$args['so_ext_stime_fri'] = $so_ext_stime_fri;
		$args['so_ext_etime_fri'] = $so_ext_etime_fri;
		
		$args['so_rsinterval_sat'] = $so_rsinterval_sat;
		$args['so_work_stime_sat'] = $so_work_stime_sat;
		$args['so_work_etime_sat'] = $so_work_etime_sat;
		$args['so_ext_stime_sat'] = $so_ext_stime_sat;
		$args['so_ext_etime_sat'] = $so_ext_etime_sat;		
		
		$rst = $C_Reserve -> Sch_Option_Reg($args);

		if($rst) {
			$C_Func->put_msg_and_modalclose("등록 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("등록에 실패하였습니다");
		}
		exit();
	
	break;

	//의료진 삭제후 일정 만들기
	case "SCH_MOD":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;				
		
		// 해당 의료진 일정 모두삭제
		$args = '';
		$args['so_idx'] = $so_idx;
		
		$rst = $C_Reserve -> Sch_Cancel($args);
		// $rst2 = $C_Reserve -> Sch_Option_Del($args);
	
		// 해당 의료진 일정 등록
		$args = '';
		$args['dr_idx'] = $dr_idx;
		$args['so_idx'] = $so_idx;

		$args['so_apply_sdate'] = $so_apply_sdate;
		$args['so_apply_edate'] = $so_apply_edate;
		
		$tmp_str = "";
		foreach($so_week_holiday as $key => $val) {			
			$tmp_str .= $val .",";
			if ($val == 'sun') {
				$so_rsinterval_sun = "";
			}else if ($val == 'mon') {
				$so_rsinterval_mon = "";
			}else if ($val == 'tue') {
				$so_rsinterval_tue = "";
			}else if ($val == 'wed') {
				$so_rsinterval_wed = "";
			}else if ($val == 'thu') {
				$so_rsinterval_thu = "";
			}else if ($val == 'fri') {
				$so_rsinterval_fri = "";
			}else if ($val == 'sat') {
				$so_rsinterval_sat = "";
			}
		}
		$tmp_str = rtrim($tmp_str, ",");		
		$args['so_week_holiday'] = $tmp_str;		

		$args['so_rsinterval_sun'] = $so_rsinterval_sun;
		$args['so_work_stime_sun'] = $so_work_stime_sun;
		$args['so_work_etime_sun'] = $so_work_etime_sun;
		$args['so_ext_stime_sun'] = $so_ext_stime_sun;
		$args['so_ext_etime_sun'] = $so_ext_etime_sun;
	
		$args['so_rsinterval_mon'] = $so_rsinterval_mon;
		$args['so_work_stime_mon'] = $so_work_stime_mon;
		$args['so_work_etime_mon'] = $so_work_etime_mon;
		$args['so_ext_stime_mon'] = $so_ext_stime_mon;
		$args['so_ext_etime_mon'] = $so_ext_etime_mon;
		
		$args['so_rsinterval_tue'] = $so_rsinterval_tue;
		$args['so_work_stime_tue'] = $so_work_stime_tue;
		$args['so_work_etime_tue'] = $so_work_etime_tue;
		$args['so_ext_stime_tue'] = $so_ext_stime_tue;
		$args['so_ext_etime_tue'] = $so_ext_etime_tue;
		
		$args['so_rsinterval_wed'] = $so_rsinterval_wed;
		$args['so_work_stime_wed'] = $so_work_stime_wed;
		$args['so_work_etime_wed'] = $so_work_etime_wed;
		$args['so_ext_stime_wed'] = $so_ext_stime_wed;
		$args['so_ext_etime_wed'] = $so_ext_etime_wed;
		
		$args['so_rsinterval_thu'] = $so_rsinterval_thu;
		$args['so_work_stime_thu'] = $so_work_stime_thu;
		$args['so_work_etime_thu'] = $so_work_etime_thu;
		$args['so_ext_stime_thu'] = $so_ext_stime_thu;
		$args['so_ext_etime_thu'] = $so_ext_etime_thu;
		
		$args['so_rsinterval_fri'] = $so_rsinterval_fri;
		$args['so_work_stime_fri'] = $so_work_stime_fri;
		$args['so_work_etime_fri'] = $so_work_etime_fri;
		$args['so_ext_stime_fri'] = $so_ext_stime_fri;
		$args['so_ext_etime_fri'] = $so_ext_etime_fri;
		
		$args['so_rsinterval_sat'] = $so_rsinterval_sat;
		$args['so_work_stime_sat'] = $so_work_stime_sat;
		$args['so_work_etime_sat'] = $so_work_etime_sat;
		$args['so_ext_stime_sat'] = $so_ext_stime_sat;
		$args['so_ext_etime_sat'] = $so_ext_etime_sat;		
		
		$rst = $C_Reserve -> Sch_Option_Mod($args);
		
		// 등록된 스케쥴 적용
		$args = "";
		$args['so_idx'] = $so_idx;
		$rst = $C_Reserve -> Sch_Option_Info($args);
		
		if($rst) {
			extract($rst);
		}
				
		$day_cnt = $C_Func->request_day($so_apply_sdate, $so_apply_edate);	
	
		
		for($i=0; $i<=$day_cnt; $i++) {
			
			$date = $C_Func->request_term_day($so_apply_sdate , $i);	
			$day = $C_Func->DayWeekChange($date);			
			$capa_cnt = 1;	//무조건 1명			
			
			//일요일
			if($day == "일") {
				if($so_rsinterval_sun != '') {	
					$s_time_sun = $so_work_stime_sun;
					$e_time_sun = $so_work_etime_sun;
					$lun_s_time_sun = str_replace(":","",$so_ext_stime_sun);	//제외시간 시작
					$lun_e_time_sun = str_replace(":","",$so_ext_etime_sun);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_sun, $e_time_sun, $so_rsinterval_sun); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_sun = $s_time_sun;
						$s_time_sun = $C_Func->plus_minute($s_time_sun, $so_rsinterval_sun);				
						
						$chk_time_sun = str_replace(":","", $s_time_sun);
						
						if($chk_time_sun > $lun_s_time_sun && $chk_time_sun <= $lun_e_time_sun) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_sun;
							$args['cp_e_time'] 	= $s_time_sun ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//월요일
			if($day == "월") {
				if($so_rsinterval_mon != '') {	
					$s_time_mon = $so_work_stime_mon;
					$e_time_mon = $so_work_etime_mon;
					$lun_s_time_mon = str_replace(":","",$so_ext_stime_mon);	//제외시간 시작
					$lun_e_time_mon = str_replace(":","",$so_ext_etime_mon);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_mon, $e_time_mon, $so_rsinterval_mon); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_mon = $s_time_mon;
						$s_time_mon = $C_Func->plus_minute($s_time_mon, $so_rsinterval_mon);				
						
						$chk_time_mon = str_replace(":","", $s_time_mon);
						
						if($chk_time_mon > $lun_s_time_mon && $chk_time_mon <= $lun_e_time_mon) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_mon;
							$args['cp_e_time'] 	= $s_time_mon ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//화요일
			if($day == "화") {
				if($so_rsinterval_tue != '') {	
					$s_time_tue = $so_work_stime_tue;
					$e_time_tue = $so_work_etime_tue;
					$lun_s_time_tue = str_replace(":","",$so_ext_stime_tue);	//제외시간 시작
					$lun_e_time_tue = str_replace(":","",$so_ext_etime_tue);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_tue, $e_time_tue, $so_rsinterval_tue); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_tue = $s_time_tue;
						$s_time_tue = $C_Func->plus_minute($s_time_tue, $so_rsinterval_tue);				
						
						$chk_time_tue = str_replace(":","", $s_time_tue);
						
						if($chk_time_tue > $lun_s_time_tue && $chk_time_tue <= $lun_e_time_tue) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_tue;
							$args['cp_e_time'] 	= $s_time_tue ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//수요일
			if($day == "수") {
				if($so_rsinterval_wed != '') {	
					$s_time_wed = $so_work_stime_wed;
					$e_time_wed = $so_work_etime_wed;
					$lun_s_time_wed = str_replace(":","",$so_ext_stime_wed);	//제외시간 시작
					$lun_e_time_wed = str_replace(":","",$so_ext_etime_wed);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_wed, $e_time_wed, $so_rsinterval_wed); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_wed = $s_time_wed;
						$s_time_wed = $C_Func->plus_minute($s_time_wed, $so_rsinterval_wed);				
						
						$chk_time_wed = str_replace(":","", $s_time_wed);
						
						if($chk_time_wed > $lun_s_time_wed && $chk_time_wed <= $lun_e_time_wed) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_wed;
							$args['cp_e_time'] 	= $s_time_wed ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//목요일
			if($day == "목") {
				if($so_rsinterval_thu != '') {	
					$s_time_thu = $so_work_stime_thu;
					$e_time_thu = $so_work_etime_thu;
					$lun_s_time_thu = str_replace(":","",$so_ext_stime_thu);	//제외시간 시작
					$lun_e_time_thu = str_replace(":","",$so_ext_etime_thu);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_thu, $e_time_thu, $so_rsinterval_thu); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_thu = $s_time_thu;
						$s_time_thu = $C_Func->plus_minute($s_time_thu, $so_rsinterval_thu);				
						
						$chk_time_thu = str_replace(":","", $s_time_thu);
						
						if($chk_time_thu > $lun_s_time_thu && $chk_time_thu <= $lun_e_time_thu) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_thu;
							$args['cp_e_time'] 	= $s_time_thu ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//금요일
			if($day == "금") {
				if($so_rsinterval_fri != '') {	
					$s_time_fri = $so_work_stime_fri;
					$e_time_fri = $so_work_etime_fri;
					$lun_s_time_fri = str_replace(":","",$so_ext_stime_fri);	//제외시간 시작
					$lun_e_time_fri = str_replace(":","",$so_ext_etime_fri);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_fri, $e_time_fri, $so_rsinterval_fri); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_fri = $s_time_fri;
						$s_time_fri = $C_Func->plus_minute($s_time_fri, $so_rsinterval_fri);				
						
						$chk_time_fri = str_replace(":","", $s_time_fri);
						
						if($chk_time_fri > $lun_s_time_fri && $chk_time_fri <= $lun_e_time_fri) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_fri;
							$args['cp_e_time'] 	= $s_time_fri ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//토요일
			if($day == "토") {
				if($so_rsinterval_sat != '') {	
					$s_time_sat = $so_work_stime_sat;
					$e_time_sat = $so_work_etime_sat;
					$lun_s_time_sat = str_replace(":","",$so_ext_stime_sat);	//제외시간 시작
					$lun_e_time_sat = str_replace(":","",$so_ext_etime_sat);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_sat, $e_time_sat, $so_rsinterval_sat); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_sat = $s_time_sat;
						$s_time_sat = $C_Func->plus_minute($s_time_sat, $so_rsinterval_sat);				
						
						$chk_time_sat = str_replace(":","", $s_time_sat);
						
						if($chk_time_sat > $lun_s_time_sat && $chk_time_sat <= $lun_e_time_sat) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_sat;
							$args['cp_e_time'] 	= $s_time_sat ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}						
				}
			}
			
		}

		if($rst) {
			$C_Func->put_msg_and_modalclose("수정 되었습니다");
		}else{
			$C_Func->put_msg_and_modalclose("수정에 실패하였습니다");
		}
		exit();
	
	break;


	case "RS_TIME_ADD":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		

		$day = $C_Func->DayWeekChange($cp_date);	
		
		$args = '';
		$args['dr_idx'] 		= $dr_idx;
		$args['so_idx'] 		= $so_idx;
		$args['cp_date'] 		= $cp_date;
		$args['cp_day'] 		= $day;
		$args['cp_s_time'] 	= $s_time;
		$args['cp_e_time'] 	= $e_time ;
		$args['cp_num'] 		= 1;
		$rst1 = $C_Reserve->Sch_Capa_Chk($args);

		$url = "../d_time_list.php?dr_idx=" . $dr_idx . "&cp_date=" . $cp_date;

		if($rst1['cnt'] > 0) {
			$C_Func->put_msg_and_go("이미 등록되어 있는 시간입니다.", $url);
			exit();
		}

		$rst = $C_Reserve->Sch_Capa_Reg($args);	

		if($rst) {
			$C_Func->put_msg_and_go("등록 되었습니다", $url);
			exit();
		}else{
			$C_Func->put_msg_and_go("등록에 실패하였습니다", $url);
			exit();
		}
	break;
	
	
	//의료진 일정 만들기
	case "SCH_SET":
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;		
		
		$args = "";
		$args['so_idx'] = $so_idx;
		$rst = $C_Reserve -> Sch_Option_Info($args);
		
		if($rst) {
			extract($rst);
		}
		/*
		$dr_idx					//의료진번호
		$so_apply_sdate //시작일
		$so_apply_edate //종료일
		$so_rsinterval_sat //간격 10, 30, 60
		$so_work_stime_sat	//적용시간 시작
		$so_work_etime_sat 	//적용시간 종료
		$so_ext_stime_sat 	//제외시간 시작
		$so_ext_etime_sat 	//제외시간 종료
		*/
		
		$day_cnt = $C_Func->request_day($so_apply_sdate, $so_apply_edate);	
	
		
		for($i=0; $i<=$day_cnt; $i++) {
			
			$date = $C_Func->request_term_day($so_apply_sdate , $i);	
			$day = $C_Func->DayWeekChange($date);			
			$capa_cnt = 1;	//무조건 1명			
			
			//일요일
			if($day == "일") {
				if($so_rsinterval_sun != '') {	
					$s_time_sun = $so_work_stime_sun;
					$e_time_sun = $so_work_etime_sun;
					$lun_s_time_sun = str_replace(":","",$so_ext_stime_sun);	//제외시간 시작
					$lun_e_time_sun = str_replace(":","",$so_ext_etime_sun);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_sun, $e_time_sun, $so_rsinterval_sun); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_sun = $s_time_sun;
						$s_time_sun = $C_Func->plus_minute($s_time_sun, $so_rsinterval_sun);				
						
						$chk_time_sun = str_replace(":","", $s_time_sun);
						
						if($chk_time_sun > $lun_s_time_sun && $chk_time_sun <= $lun_e_time_sun) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_sun;
							$args['cp_e_time'] 	= $s_time_sun ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//월요일
			if($day == "월") {
				if($so_rsinterval_mon != '') {	
					$s_time_mon = $so_work_stime_mon;
					$e_time_mon = $so_work_etime_mon;
					$lun_s_time_mon = str_replace(":","",$so_ext_stime_mon);	//제외시간 시작
					$lun_e_time_mon = str_replace(":","",$so_ext_etime_mon);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_mon, $e_time_mon, $so_rsinterval_mon); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_mon = $s_time_mon;
						$s_time_mon = $C_Func->plus_minute($s_time_mon, $so_rsinterval_mon);				
						
						$chk_time_mon = str_replace(":","", $s_time_mon);
						
						if($chk_time_mon > $lun_s_time_mon && $chk_time_mon <= $lun_e_time_mon) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_mon;
							$args['cp_e_time'] 	= $s_time_mon ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//화요일
			if($day == "화") {
				if($so_rsinterval_tue != '') {	
					$s_time_tue = $so_work_stime_tue;
					$e_time_tue = $so_work_etime_tue;
					$lun_s_time_tue = str_replace(":","",$so_ext_stime_tue);	//제외시간 시작
					$lun_e_time_tue = str_replace(":","",$so_ext_etime_tue);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_tue, $e_time_tue, $so_rsinterval_tue); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_tue = $s_time_tue;
						$s_time_tue = $C_Func->plus_minute($s_time_tue, $so_rsinterval_tue);				
						
						$chk_time_tue = str_replace(":","", $s_time_tue);
						
						if($chk_time_tue > $lun_s_time_tue && $chk_time_tue <= $lun_e_time_tue) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_tue;
							$args['cp_e_time'] 	= $s_time_tue ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//수요일
			if($day == "수") {
				if($so_rsinterval_wed != '') {	
					$s_time_wed = $so_work_stime_wed;
					$e_time_wed = $so_work_etime_wed;
					$lun_s_time_wed = str_replace(":","",$so_ext_stime_wed);	//제외시간 시작
					$lun_e_time_wed = str_replace(":","",$so_ext_etime_wed);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_wed, $e_time_wed, $so_rsinterval_wed); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_wed = $s_time_wed;
						$s_time_wed = $C_Func->plus_minute($s_time_wed, $so_rsinterval_wed);				
						
						$chk_time_wed = str_replace(":","", $s_time_wed);
						
						if($chk_time_wed > $lun_s_time_wed && $chk_time_wed <= $lun_e_time_wed) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_wed;
							$args['cp_e_time'] 	= $s_time_wed ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//목요일
			if($day == "목") {
				if($so_rsinterval_thu != '') {	
					$s_time_thu = $so_work_stime_thu;
					$e_time_thu = $so_work_etime_thu;
					$lun_s_time_thu = str_replace(":","",$so_ext_stime_thu);	//제외시간 시작
					$lun_e_time_thu = str_replace(":","",$so_ext_etime_thu);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_thu, $e_time_thu, $so_rsinterval_thu); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_thu = $s_time_thu;
						$s_time_thu = $C_Func->plus_minute($s_time_thu, $so_rsinterval_thu);				
						
						$chk_time_thu = str_replace(":","", $s_time_thu);
						
						if($chk_time_thu > $lun_s_time_thu && $chk_time_thu <= $lun_e_time_thu) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_thu;
							$args['cp_e_time'] 	= $s_time_thu ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//금요일
			if($day == "금") {
				if($so_rsinterval_fri != '') {	
					$s_time_fri = $so_work_stime_fri;
					$e_time_fri = $so_work_etime_fri;
					$lun_s_time_fri = str_replace(":","",$so_ext_stime_fri);	//제외시간 시작
					$lun_e_time_fri = str_replace(":","",$so_ext_etime_fri);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_fri, $e_time_fri, $so_rsinterval_fri); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_fri = $s_time_fri;
						$s_time_fri = $C_Func->plus_minute($s_time_fri, $so_rsinterval_fri);				
						
						$chk_time_fri = str_replace(":","", $s_time_fri);
						
						if($chk_time_fri > $lun_s_time_fri && $chk_time_fri <= $lun_e_time_fri) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_fri;
							$args['cp_e_time'] 	= $s_time_fri ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}			
				}
			}
			
			//토요일
			if($day == "토") {
				if($so_rsinterval_sat != '') {	
					$s_time_sat = $so_work_stime_sat;
					$e_time_sat = $so_work_etime_sat;
					$lun_s_time_sat = str_replace(":","",$so_ext_stime_sat);	//제외시간 시작
					$lun_e_time_sat = str_replace(":","",$so_ext_etime_sat);	//제외시간 종료				
					$time_cnt = $C_Func->loop_cnt($s_time_sat, $e_time_sat, $so_rsinterval_sat); //시작시간 , 종료시간, 간격으로 루프수를 계산
					
					for($j=0; $j<$time_cnt; $j++) {
						$ori_time_sat = $s_time_sat;
						$s_time_sat = $C_Func->plus_minute($s_time_sat, $so_rsinterval_sat);				
						
						$chk_time_sat = str_replace(":","", $s_time_sat);
						
						if($chk_time_sat > $lun_s_time_sat && $chk_time_sat <= $lun_e_time_sat) {
							
						}else{
							$args = '';
							$args['dr_idx'] 		= $dr_idx;
							$args['so_idx'] 		= $so_idx;
							$args['cp_date'] 		= $date;
							$args['cp_day'] 		= $day;
							$args['cp_s_time'] 	= $ori_time_sat;
							$args['cp_e_time'] 	= $s_time_sat ;
							$args['cp_num'] 		= $capa_cnt;						
							$rst = $C_Reserve->Sch_Capa_Reg($args);
						}		
					}						
				}
			}
			
		}
		
		echo "true";
		exit();
	
	break;
	
	
	//월별 일정삭제 
	case "SCH_DEL" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = '';
		$args['so_idx'] = $so_idx;
		$rst = $C_Reserve -> Sch_Del($args);
		
		echo "true";	
		exit();
		
	break;
	
	//월별 캡파 적용해제
	case "SCH_CANCEL" :
		if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
		
		$args = '';
		$args['so_idx'] = $so_idx;
		$rst = $C_Reserve -> Sch_Cancel($args);
		
		echo "true";	
		exit();
		
	break;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>