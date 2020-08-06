<?php
CLASS Reserve extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}

	
	
	// desc	 : 이전 예약 취소
	// auth  : 
	// param
	function Reserve_Cancel_Old($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				WMH_MemberReserved
			set
				idx_of_Member = '0',
				MemID = '',
				MemName = '',
				BirthDate = '',
				Phone = '',
				Mobile = '',
				Email = '',				
				ZipCode = '',				
				Addr1 = '',				
				Addr2 = '',				
				Reservation_Contents = '',				
				Reservation_Status = '0',				
				aDate = ''				
			where
				idx ='$idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 이전 예약 수정 및 완료
	// auth  : 
	// param
	function Reserve_Modi_Old($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				WMH_MemberReserved
			set
				Reservation_Status = '$Reservation_Status'
			where
				idx ='$idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 이전 센터 리스트
	// auth  : 
	// param
	function Doctor_List_Old($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if($idx_of_Group != '') {
			$addQry = " and idx_of_Group = '$idx_of_Group' ";
		}
		
		$qry = "
			select * from WMH_ConsultationDoctor where 1=1 $addQry order by Sortnum desc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 이전 센터 리스트
	// auth  : 
	// param
	function Center_List_Old($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if($Area != '') {
			$addQry = " and Area = '$Area' ";
		}
		
		$qry = "
			select * from WMH_ConsultationGroup where 1=1 $addQry order by Sortnum desc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	function Reserve_Detail_Old($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				A.* , B.MemBirth, B.MemMF,
				(select `Group` from WMH_ConsultationGroup where idx = A.idx_of_Group) as gr_name,		
				(select doctor_Name from WMH_ConsultationDoctor where idx = A.idx_of_doctor) as doctorName , 
				(select select_Consultation from WMH_ConsultationDoctor where idx = A.idx_of_doctor) as selectConsultation 
			from 
				WMH_MemberReserved A LEFT OUTER JOIN WMH_MEMBER B ON A.idx_of_Member = B.idx
			where
				A.idx = '$idx'			
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 이전 예약 리스트
	// auth  : 
	// param
	function Reserve_List_Old ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 and Reservation_Status != 0  ";
		
		if($idx_of_Group != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " idx_of_Group = '$idx_of_Group' ";
		}
		
		if($idx_of_Doctor != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " idx_of_Doctor = '$idx_of_Doctor' ";
		}
		
		if($Reservation_Status != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " Reservation_Status = '$Reservation_Status' ";
		}
	
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " Possible_Date BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = " idx ";
		$args['q_col'] = "
			idx , 
			MemID , 
			MemName , 
			(select `Group` from WMH_ConsultationGroup where idx = WMR.idx_of_Group) as gr_name,
			(select doctor_Name from WMH_ConsultationDoctor where idx = WMR.idx_of_doctor) as doctorName , 
			(select select_Consultation from WMH_ConsultationDoctor where idx = WMR.idx_of_doctor) as selectConsultation , 
			Possible_Date , 
			Possible_Time , 
			Reservation_Status , 
			aDate
		";
		$args['q_table'] = " WMH_MemberReserved as WMR ";
		$args['q_where'] = $addQry;
		$args['q_order'] = " aDate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}

	
	// desc	 : 온라인 예약 문자 발송
	// auth  : JH 2013-09-16 월요일
	// param
	function Reserve_Sms_List($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		$qry = "
			select 
				*
			from 
				reserve_data
			where
				rd_date = '$s_date'	
				and mb_mobile != ''
		";		
		$rst =  $this -> DB -> execSqlList($qry);				
		return $rst;
		
	}
	
	// desc	 : 온라인 예약 취소
	// auth  : JH 2013-09-16 월요일
	// param
	function Reserve_Cancel($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_data where rd_idx = '$rd_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 온라인 문자 발송
	// auth  : JH 2013-09-16 월요일
	// param
	function Reserve_SMS_Result($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				reserve_data
			set
				rd_sms = 'Y'
			where
				rd_idx = '$rd_idx'
		";

		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 회원 예약 수정
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Reserve_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				reserve_data
			set
				dr_idx = '$dr_idx',
				dr_name = '$dr_name',
				dr_clinic = '$dr_clinic',
				dr_center = '$dr_center',
				cp_idx = '$cp_idx',
				rd_date = '$rd_date',
				rd_s_time = '$rd_s_time',
				rd_e_time = '$rd_e_time',
				rd_status = '$rd_status'
			where
				rd_idx='$rd_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 :  일정에 맞는 시간 가져오기 관리자용
	// param
	function Doctor_Time_List_Adm($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				*
			from 
				reserve_capa 
			where 
				dr_idx='$dr_idx' 
				and cp_date='$cp_date' 
				and cp_s_time NOT IN (select rd_s_time from reserve_data where rd_date='$cp_date' and rd_s_time != '$rd_s_time')	
			order by cp_s_time asc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	

	// desc	 : 회원 예약 취소
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Reserve_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_data where rd_idx = '$rd_idx' and mb_code='$mb_code'
		";		
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	//예약 관리자
	function Reserve_Detail_Adm($args='') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		

		$qry = "
			select * from reserve_data where rd_idx='$rd_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}

	//예약 상세
	function Reserve_Detail($args='') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		

		$qry = "
			select * from reserve_data where rd_idx='$rd_idx' and mb_code='$mb_code'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}

	//예약 등록
	function Reserve_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$qry = "
			INSERT INTO
				 reserve_data
				(
					rd_idx,
					cp_idx,
					mb_code,
					mb_id,
					mb_name,
					mb_name2,
					mb_mobile,
					mb_email,
					mb_birth,
					mb_sex,
					mb_con,
					mb_type,
					mb_post,
					mb_address1,
					mb_address2,
					dr_idx,
					dr_name,
					dr_clinic,
					dr_center,
					rd_date,
					rd_s_time,
					rd_e_time,
					rd_type,
					rd_status,
					rd_sms,
					rd_regdate
				)
				VALUES
				(
					''
					, '$cp_idx'
					, '$mb_code'	
					, '" . mysql_real_escape_string($mb_id) . "'
					, '" . mysql_real_escape_string($mb_name) . "'					
					, '" . mysql_real_escape_string($mb_name2) . "'					
					, '$mb_mobile'	
					, '$mb_email'
					, '$mb_birth'
					, '$mb_sex'
					, '$mb_con'
					, '$mb_type'
					, '$mb_post'
					, '" . mysql_real_escape_string($mb_address1) . "'					
					, '" . mysql_real_escape_string($mb_address2) . "'					
					, '$dr_idx'	
					, '$dr_name'	
					, '$dr_clinic'
					, '$dr_center'
					, '$rd_date'
					, '$rd_s_time'
					, '$rd_e_time'
					, '$rd_type'
					, 'N'
					, 'N'
					, NOW()
				)
			";		
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 :    
	// param
	function Doctor_Time_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select cp_s_time, cp_e_time from reserve_capa where dr_idx='$dr_idx' and cp_idx='$cp_idx'
		";

		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}

	// desc	 :  일정에 맞는 시간 가져오기
	// param
	function Doctor_Time_List($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				* 
			from 
				reserve_capa 
			where 
				dr_idx='$dr_idx'
				and cp_date='$cp_date' 
			order by cp_s_time asc
		";
		/*$qry = "
			select 
				* 
			from 
				reserve_capa 
			where 
				dr_idx='$dr_idx'
				and cp_date='$cp_date' 
				and cp_s_time NOT IN (select rd_s_time from reserve_data where rd_date='$cp_date')
			order by cp_s_time asc
		";*/
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}

	//당일 이후 예약이 한건이라도 잇는지 체크 
	function Reserve_Today_Cnt($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$qry = " select count(*) as cnt from reserve_data where mb_code='$mb_code' and rd_date >= '$rd_date' ";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 휴무일 관리
	// auth  : JH 2013-09-13
	// param
	function HOliday_RS_LIST($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				th_date
			from 
				tblholiday
			where 
				LEFT(th_date,7) = '$th_date'
		";
		$rst =  $this -> DB -> execSqlAssoc($qry);
		return $rst;
	}
	
	function HOLIDAY_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = " delete from tblholiday where th_idx = '$th_idx' ";		
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;			
	}
	
	
	function HOLIDAY_MODI($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblholiday
			set
				th_con = '$th_con',
				th_date = '$th_date'
			where
				th_idx = '$th_idx'
		";
		
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;			
	}
	
	function HOliday_Detail($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				* 
			from 
				tblholiday
			where 
				th_idx = '$th_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	function HOLIDAY_REG($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
			$qry = "
			INSERT INTO
				 tblholiday
				(
					th_idx,
					th_con,
					th_date,
					th_regdate
				)
				VALUES
				(
					''
					, '$th_con'
					, '$th_date'						
					, NOW()
				)
			";		
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 휴무일
	// auth  : 
	// param
	function Holiday_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

	
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " th_date BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "th_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " tblholiday";
		$args['q_where'] = $addQry;
		$args['q_order'] = " th_date asc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	
	
	
	// desc	 : 캡파 삭제
	// auth  : JH 2013-11-04 월요일
	// param	
	function Cappa_Info_Del_Arr($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_capa where cp_idx in ($cp_arr_idx)
		";		
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 캡파 삭제
	// auth  : JH 2013-11-04 월요일
	// param	
	function Cappa_Info_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_capa where cp_idx = '$cp_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}

	// desc	 : 휴무일 설정으로 캡파 삭제
	// auth  : 
	// param	
	function Cappa_Holiday_Info_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_capa where dr_idx = '$dr_idx' and cp_date = '$cp_date'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}

	// desc	 : 휴무일 설정으로 캡파 상태 변경
	// auth  : 
	// param	
	function Cappa_Holiday_Info_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				reserve_capa
			set
				cp_holiday = '$status'
			where
				dr_idx = '$dr_idx' and cp_date = '$cp_date'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 캡파 개별 수정
	// auth  : JH 2013-11-04 월요일
	// param	
	function Cappa_Info_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				reserve_capa
			set
				cp_s_time = '$cp_s_time',
				cp_e_time = '$cp_e_time'
			where
				cp_idx = '$cp_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	
	
	
	// desc	 : 의료진 일정 리스트
	// auth  : JH 2013-11-04 월요일
	// param	
	function Day_Sch_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and dr_idx ='$dr_idx' and cp_date ='$cp_date' ";


		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "cp_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "reserve_capa ";
		$args['q_where'] = $addQry;
		$args['q_order'] = "cp_date asc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&dr_idx=" . $dr_idx ."&cp_date=" . $cp_date;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	
	
	
	
	// desc	 : 일정이 있는지 여부
	// auth  : JH 2013-09-13
	// param
	function Doctor_Schedule_Chk($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select count(*) as cnt from reserve_capa where dr_idx='$dr_idx' and cp_date ='$cho_date'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}

	function Doctor_Schedule_Holiday_Chk($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select count(*) as cnt from reserve_capa where dr_idx='$dr_idx' and cp_date ='$cho_date' and cp_holiday = 'Y';
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}	
	
	
	// desc	 : 스케쥴 캡파 해제
	// auth  : JH 2013-09-13
	// param
	function Sch_Cancel($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_capa where so_idx='$so_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}	
	
	
	
	// desc	 : 스케쥴 캡파 삭제
	// auth  : JH 2013-09-13
	// param
	function Sch_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_capa where so_idx='$so_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		
		$qry = "
			delete from reserve_option where so_idx='$so_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}	

	function Sch_Capa_Chk($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$qry = "
			select count(*) as cnt from reserve_capa where dr_idx='$dr_idx' and cp_date ='$cp_date' and cp_s_time = '$cp_s_time'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 스케쥴 캡파 넣기
	// auth  : JH 2013-09-13
	// param
	function Sch_Capa_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				reserve_capa
				(
					cp_idx,
					dr_idx,
					so_idx,
					cp_date,
					cp_s_time,
					cp_e_time,
					cp_num,
					cp_day,
					cp_regdate
				)
				VALUES
				(
					''				
					, '$dr_idx'	
					, '$so_idx'	
					, '$cp_date'				
					, '$cp_s_time'	
					, '$cp_e_time'	
					, '$cp_num'	
					, '$cp_day'	
					,  NOW()
				)
			";	
		//echo $qry ."<br><br>";
			
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	
	
	// desc	 : 스케쥴 설정 정보
	// auth  : JH 2013-09-13
	// param 
	function Sch_Option_Info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from reserve_option where so_idx='$so_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	
	// desc	 : 스케쥴 설정 등록
	// auth  : JH 2013-09-13
	// param 
	function Sch_Option_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				reserve_option
				(
				 	so_idx,				
					dr_idx,										
					so_rsinterval_sun,
					so_work_stime_sun,
					so_work_etime_sun,					
					so_ext_stime_sun,
					so_ext_etime_sun,		
					so_rsinterval_mon,
					so_work_stime_mon,
					so_work_etime_mon,
					so_ext_stime_mon,
					so_ext_etime_mon,	
					so_rsinterval_tue,
					so_work_stime_tue,
					so_work_etime_tue,
					so_ext_stime_tue,
					so_ext_etime_tue,	
					so_rsinterval_wed,
					so_work_stime_wed,
					so_work_etime_wed,
					so_ext_stime_wed,
					so_ext_etime_wed,	
					so_rsinterval_thu,
					so_work_stime_thu,
					so_work_etime_thu,
					so_ext_stime_thu,
					so_ext_etime_thu,	
					so_rsinterval_fri,
					so_work_stime_fri,
					so_work_etime_fri,
					so_ext_stime_fri,
					so_ext_etime_fri,	
					so_rsinterval_sat,
					so_work_stime_sat,
					so_work_etime_sat,
					so_ext_stime_sat,
					so_ext_etime_sat,	
					so_apply_sdate,
					so_apply_edate,
					so_week_holiday,
					so_delflag,
					so_regdate
				)
				VALUES
				(
					''					
					, '$dr_idx'										
					, '$so_rsinterval_sun'
					, '$so_work_stime_sun'
					, '$so_work_etime_sun'
					, '$so_ext_stime_sun'
					, '$so_ext_etime_sun'					
					, '$so_rsinterval_mon'
					, '$so_work_stime_mon'
					, '$so_work_etime_mon'
					, '$so_ext_stime_mon'
					, '$so_ext_etime_mon'					
					, '$so_rsinterval_tue'
					, '$so_work_stime_tue'
					, '$so_work_etime_tue'
					, '$so_ext_stime_tue'
					, '$so_ext_etime_tue'					
					, '$so_rsinterval_wed'
					, '$so_work_stime_wed'
					, '$so_work_etime_wed'
					, '$so_ext_stime_wed'
					, '$so_ext_etime_wed'					
					, '$so_rsinterval_thu'
					, '$so_work_stime_thu'
					, '$so_work_etime_thu'
					, '$so_ext_stime_thu'
					, '$so_ext_etime_thu'					
					, '$so_rsinterval_fri'
					, '$so_work_stime_fri'
					, '$so_work_etime_fri'
					, '$so_ext_stime_fri'
					, '$so_ext_etime_fri'					
					, '$so_rsinterval_sat'
					, '$so_work_stime_sat'
					, '$so_work_etime_sat'
					, '$so_ext_stime_sat'
					, '$so_ext_etime_sat'					
					, '$so_apply_sdate'
					, '$so_apply_edate'
					, '$so_week_holiday'
					, 'N'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}

	// desc	 : 스케쥴 설정 등록
	// auth  : JH 2013-09-13
	// param 
	function Sch_Option_Mod($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			UPDATE
				reserve_option
			SET
				 	so_rsinterval_sun = '$so_rsinterval_sun',
					so_work_stime_sun = '$so_work_stime_sun',
					so_work_etime_sun = '$so_work_etime_sun',
					so_ext_stime_sun = '$so_ext_stime_sun',
					so_ext_etime_sun = '$so_ext_etime_sun',
					so_rsinterval_mon = '$so_rsinterval_mon',
					so_work_stime_mon = '$so_work_stime_mon',
					so_work_etime_mon = '$so_work_etime_mon',
					so_ext_stime_mon = '$so_ext_stime_mon',
					so_ext_etime_mon = '$so_ext_etime_mon',
					so_rsinterval_tue = '$so_rsinterval_tue',
					so_work_stime_tue = '$so_work_stime_tue',
					so_work_etime_tue = '$so_work_etime_tue',
					so_ext_stime_tue = '$so_ext_stime_tue',
					so_ext_etime_tue = '$so_ext_etime_tue',
					so_rsinterval_wed = '$so_rsinterval_wed',
					so_work_stime_wed = '$so_work_stime_wed',
					so_work_etime_wed = '$so_work_etime_wed',
					so_ext_stime_wed = '$so_ext_stime_wed',
					so_ext_etime_wed = '$so_ext_etime_wed',
					so_rsinterval_thu = '$so_rsinterval_thu',
					so_work_stime_thu = '$so_work_stime_thu',
					so_work_etime_thu = '$so_work_etime_thu',
					so_ext_stime_thu = '$so_ext_stime_thu',
					so_ext_etime_thu = '$so_ext_etime_thu',
					so_rsinterval_fri = '$so_rsinterval_fri',
					so_work_stime_fri = '$so_work_stime_fri',
					so_work_etime_fri = '$so_work_etime_fri',
					so_ext_stime_fri = '$so_ext_stime_fri',
					so_ext_etime_fri = '$so_ext_etime_fri',
					so_rsinterval_sat = '$so_rsinterval_sat',
					so_work_stime_sat = '$so_work_stime_sat',
					so_work_etime_sat = '$so_work_etime_sat',
					so_ext_stime_sat = '$so_ext_stime_sat',
					so_ext_etime_sat = '$so_ext_etime_sat',
					so_apply_sdate = '$so_apply_sdate',
					so_apply_edate = '$so_apply_edate',
					so_week_holiday = '$so_week_holiday'
			WHERE
					so_idx = '$so_idx'
			";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 스케쥴 설정 삭제
	// auth  : 
	// param
	function Sch_Option_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from reserve_option where so_idx = '$so_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 등록된 캡파가 있는지 체크
	// auth  : JH 2013-09-13
	// param 
	function Sch_Capa_Data_Chk($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select count(*) as cnt from reserve_capa where cp_date ='$cp_date' and dr_idx='$dr_idx'
		";
		$rst = $this->DB->execSqlOneRow($qry);
		return $rst;
	}
	
	
	
	
	// desc	 : 일정 적용여부
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function CHECK_CAPPA($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select count(*) as cnt from reserve_capa where so_idx='$so_idx' and cp_date between '$s_date' and '$e_date'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 적용 캡파중 예약내역 존재유무
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function CHECK_CAPPA_RESERVE($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select count(*) as cnt from reserve_capa A, reserve_data B where A.cp_idx=B.cp_idx and A.so_idx='$so_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 의료진 일정 설정
	// auth  : JH 2013-11-04 월요일
	// param	
	function Doctor_Schdule_Setup_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and so_delflag ='N' ";
		
		if($dr_clinic != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " D.dr_clinic = '$dr_clinic' ";
		}
		
		if($dr_center != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " D.dr_center = '$dr_center' ";
		}

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " so_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}


		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "so_idx";
		$args['q_col'] = "RO.*, D.dr_name, D.dr_center, D.dr_clinic";
		$args['q_table'] = "reserve_option RO LEFT OUTER JOIN tblDoctor D ON RO.dr_idx=D.dr_idx";
		$args['q_where'] = $addQry;
		$args['q_order'] = "so_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&dr_idx=" . $dr_idx;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	
	
	
	// desc	 : 예약 리스트
	// auth  : 
	// param
	function Reserve_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";		
		$addQry = " 1=1 ";
		
		if($dr_clinic != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_clinic = '$dr_clinic' ";
		}
		
		if($dr_center != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " dr_center = '$dr_center' ";
		}

		if($mb_id != '') {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " mb_id = '$mb_id' ";
		}
	
		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " rd_date BETWEEN '$s_date 00:00:00' AND '$e_date 23:59:59'";
		}
		
		
		
		
		if (($s_date2 && $e_date2) && ($s_date2 < $e_date2)) {
			if ($addQry)
			$addQry .= " AND ";
			$addQry .= " rd_regdate BETWEEN '$s_date2 00:00:00' AND '$e_date2 23:59:59'";
		}
		
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "rd_idx";
		$args['q_col'] = "*";
		$args['q_table'] = " reserve_data";
		$args['q_where'] = $addQry;
		$args['q_order'] = " rd_idx desc";
		$args['q_group'] = "";

		$args['tail'] = "dr_center=" . $dr_center . "&dr_clinic=" . $dr_clinic . "&s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
}
?>