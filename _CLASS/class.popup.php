<?
CLASS Popup extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	// desc	 : 팝업 첨부 이미지 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Popup_ImgUpdate($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			Update
				tblPopUp
			set
				pop_file_name = ''
			where 
				pop_idx = '$pop_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 팝업 삭제
	// auth  : JH 2013-09-16 월요일
	// param
	function Popup_Info_Del($args) {
		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblPopUp where pop_idx = '$pop_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 팝업 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function PopUp_Info($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblPopUp where pop_idx = '$pop_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 팝업 노출
	// auth  : JH 2013-09-16 월요일
	// param
	function PopUp_Show() {
		
		$qry = "
			SELECT * FROM tblPopUp WHERE pop_use='Y' AND ('".date('Y-m-d H:i:s')."' BETWEEN pop_start_date AND pop_end_date) ORDER BY pop_idx DESC
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 팝업 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Popup_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		
		$addQry = " 1=1 ";

		if (($s_date && $e_date) && ($s_date < $e_date)) {
			if ($addQry)
			$addQry .= " AND ";

			$addQry .= " pop_reg_date BETWEEN '$s_date 00:00:00' AND '$e_date 00:00:00'";
		}

		
		if($pop_lang != '') {
			$addQry .= " AND pop_lang = '$pop_lang' ";
		}
				
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}

		$args['show_row'] = $show_row;
		$args['show_page'] = 5;
		$args['q_idx'] = "pop_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblPopUp";
		$args['q_where'] = $addQry;
		$args['q_order'] = "pop_reg_date desc";
		$args['q_group'] = "";

		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
	// desc	 : 팝업 수정
	// auth  : JH 2013-09-13
	// param 
	function Popup_Info_Modify($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			Update
				tblPopUp
			set			
				pop_type = '$pop_type',
				pop_use = '$pop_use',
				pop_cookie = '$pop_cookie',
				pop_start_date = '$pop_start_date',
				pop_end_date = '$pop_end_date',
				pop_title = '$pop_title',
				pop_contents = '$pop_contents',
				pop_file_name = '$pop_file_name',
				pop_link_url = '$pop_link_url',
				pop_width = '$pop_width',
				pop_height = '$pop_height',
				pop_scroll = '$pop_scroll',
				pop_x_position = '$pop_x_position',
				pop_y_position = '$pop_y_position',
				pop_lang = '$pop_lang',
				editor_img_code = '$editor_img_code',
				pop_type2 = '$pop_type2',
				pop_mod_date  =  NOW()
			where
				pop_idx='$pop_idx'
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	
	// desc	 : 팝업 등록
	// auth  : JH 2013-09-13
	// param 
	function Popup_Info_Reg($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			INSERT INTO
				tblPopUp
				(
					pop_idx,
					pop_type,
					pop_use,
					pop_cookie,
					pop_start_date,
					pop_end_date,
					pop_title,
					pop_contents,
					pop_file_name,
					pop_link_url,
					pop_width,
					pop_height,
					pop_scroll,
					pop_x_position,
					pop_y_position,
					pop_lang,
					editor_img_code,
					pop_type2,
					pop_reg_date
				)
				VALUES
				(
					''
					, '$pop_type'
					, '$pop_use'
					, '$pop_cookie'
					, '$pop_start_date'
					, '$pop_end_date'
					, '$pop_title'
					, '$pop_contents'
					, '$pop_file_name'
					, '$pop_link_url'
					, '$pop_width'
					, '$pop_height'
					, '$pop_scroll'
					, '$pop_x_position'
					, '$pop_y_position'
					, '$pop_lang'
					, '$editor_img_code'
					, '$pop_type2'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
}
?>