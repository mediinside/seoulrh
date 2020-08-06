<?
CLASS Tag extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}

	// desc	 : 순위변경
	// auth  : 
	// param
	function TT_AUTO_CHAGE($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		

		$arr_tmp = explode(',',$tmp_id);
		
		for($i=0; $i<count($arr_tmp); $i++) {
			$idx = $arr_tmp[$i];			
			$qry = " update tblTag set tt_desc = '$max_desc' where tt_idx = '$idx'	";			
			$rst =  $this -> DB -> execSqlUpdate($qry);
			$max_desc--; 
		}
	}

	// desc	 : 순위변경
	// auth  : 
	// param
	/*
	function TT_AUTO_CHAGE($args) {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;		
		
		$arr_tmp = explode('_',$cl_id);
		$arr_tmp1 = explode('_',$ch_id);
		
		$n_idx = $arr_tmp[0];
		$n_desc = $arr_tmp[1];
		
		$a_idx = $arr_tmp1[0];
		$a_desc = $arr_tmp1[1];
		
		$qry = " update tblTag set tt_desc = '$a_desc' where tt_idx = '$n_idx'	";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		
		$qry1 = " update tblTag set tt_desc = '$n_desc' where tt_idx = '$a_idx'	";
		$rst1 =  $this -> DB -> execSqlUpdate($qry1);
		
		return $rst;
	}
	*/
	
	// desc	 : 메인 태그 리스트
	// auth  : 
	// param
	function Main_Tag_List($args) {
	if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblTag where tt_show = 'Y' and tt_cate='$tt_cate' order by tt_desc desc
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
		
	}
	
	
	// desc	 : 태그 삭제
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Tag_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblTag where tt_idx = '$tt_idx'	
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 태그 수정
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Tag_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblTag
			set
				tt_cate = '$tt_cate',
				tt_tag_name = '$tt_tag_name',
				tt_url = '$tt_url',
				tt_show = '$tt_show'			
			where
				tt_idx = '$tt_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	
	
	// desc	 : 태그 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Tag_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			select * from tblTag where tt_idx = '$tt_idx'
		";
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 태그 등록
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Tag_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "select tt_desc  from tblTag where tt_cate='$tt_cate' order by tt_desc desc limit 0, 1";	
		$rst = $this -> DB -> execSqlOneRow($qry);		
		if($rst) {
			$tt_desc = $rst['tt_desc'] + 1;
		}else{
			$tt_desc = 1;
		}
		
		$qry = "
			INSERT INTO
				tblTag
				(
					tt_idx,
					tt_cate,
					tt_tag_name,
					tt_url,
					tt_show,
					tt_desc,
					tt_regdate
				)
				VALUES
				(
					''		
					, '$tt_cate'
					, '$tt_tag_name'
					, '$tt_url'
					, '$tt_show'
					, '$tt_desc'
					,  NOW()
				)
			";
		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	// desc	 : 태그 리스트
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Tag_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($tt_cate)) {
			$addQry .= " AND ";
			$addQry .= " tt_cate = '$tt_cate' ";
		}	
		
		if(!empty($tt_show)) {
			$addQry .= " AND ";
			$addQry .= " tt_show = '$tt_show' ";
		}	
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}
		
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "tt_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblTag";
		$args['q_where'] = $addQry;
		$args['q_order'] = "tt_desc desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
}
?>