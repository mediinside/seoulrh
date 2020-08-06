<?
CLASS AdminMain extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	// desc	 : 게시판 리스트 전부 
	// auth  : JH 2013-09-16 월요일
	// param
	function Board_List_All($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		$qry = "select * from tblJhBoardAdmin ";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;	
	}

	
	// desc	 : 권한 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Auth_Del($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblAdminAuth where tl_idx = '$tl_idx'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}
	
	// desc	 : 권한 수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Auth_Modi($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update 
				tblAdminAuth 
			set
				tl_level = '$tl_level',
				tl_folder = '$tl_folder',
				tl_folder_sub = '$tl_folder_sub',
				tl_bbs = '$tl_bbs'
			where 
				tl_idx = '$tl_idx'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;	
	}


	// desc	 : 권한 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Auth_List_All($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblAdminAuth 
		";
		$rst = $this -> DB -> execSqlAssoc($qry);
		return $rst;		
	}

	
	// desc	 : 권한 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Auth_info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblAdminAuth where tl_idx = '$tl_idx'
		";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;		
	}
	
	// desc	 : 권한 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Auth_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblAdminAuth
			(
			tl_idx,
			tl_level,
			tl_folder,
			tl_folder_sub,
			tl_bbs,
			tl_regdate
			)
			VALUES
			(
			''
			, '$tl_level'
			, '$tl_folder'
			, '$tl_folder_sub'
			, '$tl_bbs'
			, NOW()
			)
		";

		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 관리자 권한 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Auth_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " tl_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "tl_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblAdminAuth";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tl_idx desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	
	// desc	 : 팀원 정보 전부
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Mem_All() {
		
		$qry = "
			select * from tblTeamMember ttm LEFT OUTER JOIN tblTeam tt ON ttm.tt_idx=tt.tt_idx where ttm.ttm_del_flag = 'N'
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 팀 정보 전부
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Mem_Data($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select 
				* 
			from 
				tblTeamMember ttm LEFT OUTER JOIN tblTeam tt ON ttm.tt_idx=tt.tt_idx 
			where 
				ttm.tt_idx='$tt_idx' 
				and ttm.ttm_del_flag = 'N'
			";		
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 팀 정보 전부
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_All() {
		
		$qry = "
			select * from tblTeam where tt_del_flag = 'N' order by tt_idx asc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	// desc	 : 첨부 이미지 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Team_Mem_ImgUpdate($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			Update
				tblTeamMember
			set
				ttm_photo = ''
			where 
				ttm_idx = '$ttm_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 팀원 정보삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Mem_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblTeamMember
			set
				ttm_del_flag = 'Y'
			where
				ttm_idx = '$ttm_idx'
		";		
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 팀원 정보수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Mem_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblTeamMember
			set
				tt_idx = '$tt_idx',
				ttm_name = '$ttm_name',
				ttm_photo = '$ttm_photo',
				ttm_content = '$ttm_content',
				ttm_profile = '$ttm_profile'
			where
				ttm_idx = '$ttm_idx'
		";	
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 팀원 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Mem_info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblTeamMember where ttm_idx = '$ttm_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 팀원 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Mem_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblTeamMember
			(
			ttm_idx,
			tt_idx,
			ttm_name,
			ttm_content,
			ttm_photo,
			ttm_profile,
			ttm_del_flag,
			ttm_regdate
			)
			VALUES
			(
			''
			, '$tt_idx'
			, '$ttm_name'
			, '$ttm_content'
			, '$ttm_photo'
			, '$ttm_profile'
			, 'N'
			, NOW()
			)
		";

		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	// desc	 : 팀 전체 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Team_List() {
		
		$qry = " select * from tblTeam where tt_del_flag ='N' ";
		$rst =  $this -> DB -> execSqlList($qry);
		
		$tmp_arr = array();		
		for($i=0; $i<count($rst); $i++) {			
			$tmp_arr[$rst[$i]['tt_idx']] = $rst[$i]['tt_name'];			
		}
		
		return $tmp_arr;
	}
	
	// desc	 : 팀 정보삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblTeam
			set
				tt_del_flag = 'Y'
			where
				tt_idx = '$tt_idx'
		";		
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 팀 정보수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblTeam
			set
				tt_name = '$tt_name',
				tt_content = '$tt_content'
			where
				tt_idx = '$tt_idx'
		";		
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 팀 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblTeam where tt_idx = '$tt_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 팀 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Team_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblTeam
			(
			tt_idx,
			tt_name,
			tt_content,
			tt_del_flag,
			tt_regdate
			)
			VALUES
			(
			''
			, '$tt_name'
			, '$tt_content'		
			, 'N'
			, NOW()
			)
		";

		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 팀 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function getTeamList ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and tt_del_flag ='N' ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " tt_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "tt_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblTeam";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tt_idx desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	
	// desc	 : 팀원 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function getTeamMemList ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and ttm.ttm_del_flag ='N' ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " ttm.ttm_regdate BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "ttm_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblTeamMember ttm LEFT OUTER JOIN tblTeam tt ON ttm.tt_idx=tt.tt_idx ";
		$args['q_where'] = $addQry;
		$args['q_order'] = "ttm.ttm_regdate desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 게시판 관리 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function getBoardList ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if($jb_code_in != '') {
			$addQry .= " and  jb_code in (" . $jb_code_in . ") ";
		}
		
		if($jba_lang != '') {
			$addQry .= " and  jba_lang = '".$jba_lang."'";
		}else{
			$addQry .= " and  jba_lang = 'kor'";
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "jba_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblJhBoardAdmin";
		$args['q_where'] = $addQry;
		$args['q_order'] = " jb_code asc";
		$args['q_group'] = "";

		$args['tail'] = "";
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 관리자 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Info_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblAdmin
			set
				mb_del_flag = 'Y'
			where
				mb_code = '$mb_code'
		";
		$rst = $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 관리자 등록
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Info_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
			tblAdmin
			(
			mb_code,
			mb_id,
			mb_name,
			mb_password,
			mb_email,
			mb_phone,
			mb_level,
			tl_idx,
			mb_register_date,
			mb_del_flag
			)
			VALUES
			(
			''
			, '$mb_id'
			, '$mb_name'
			, '$mb_password'
			, '$mb_email'
			, '$mb_phone'
			, '$mb_level'
			, '$tl_idx'
			, NOW()
			, 'N'
			)
		";

		$rst = $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	// desc	 : 아이디 중복체크
	// auth  : JH 2013-09-16 월요일
	// param
	function DobuleIDCheck($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select count(*) as cnt from tblAdmin where mb_id='$mb_id'			
		";	
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 관리자 정보수정
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_Info_Modify($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if($mb_level != '') {
			$addQry = " mb_level = '$mb_level', ";
		}
		
		$qry = "
			update
				tblAdmin
			set
				mb_id = '$mb_id',
				mb_name = '$mb_name',
				mb_password = '$mb_password',
				mb_email = '$mb_email',
				mb_phone = '$mb_phone',
				tl_idx = '$tl_idx',
				$addQry
				mb_modify_date = NOW()
			where
				mb_code = '$mb_code'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	// desc	 : 관리자 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function Admin_info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblAdmin where mb_code = '$mb_code'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 관리자 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function getAdminList ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 and mb_del_flag ='N' ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " mb_register_date BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "mb_code";
		$args['q_col'] = "*";
		$args['q_table'] = "tblAdmin";
		$args['q_where'] = $addQry;
		$args['q_order'] = "mb_register_date desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
}
?>