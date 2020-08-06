<?
CLASS seoulrh extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	
	// desc	 : 메인 슬라이드 리스트
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function Main_seoulrh_Show($args='') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if($s_lang != '') {
			$addQry .= " AND s_lang = '$s_lang' ";
		}else{
			$addQry .= " AND s_lang = 'kor' ";
		}
		
		if($s_type != '') {
			$addQry .= " AND s_type = '$s_type' ";
		}else{
			$addQry .= " AND s_type = 'main' ";
		}
		$qry = "
			select * from tblseoulrh where s_show ='Y' $addQry order by s_regdate asc $limit
		";
		if ($_SERVER["REMOTE_ADDR"] == '210.90.202.198') {
			// echo $qry;
		}
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
    }   
   
		
	// desc	 : 슬라이드 수정
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function seoulrh_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tblseoulrh
			set
                s_year = '$s_year',				
                s_content1 = '$s_content1',
                s_content2 = '$s_content2',
                s_content3 = '$s_content3',
                s_content4 = '$s_content4',
                s_content5 = '$s_content5',
                s_content6 = '$s_content6',
                s_content7 = '$s_content7',
				s_show = '$s_show',
				s_type = '$s_type'
			where
				s_idx = '$s_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 삭제
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function seoulrh_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tblseoulrh where s_idx = '$s_idx'	
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}	
	
	
	// desc	 : 슬라이드 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function seoulrh_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "            
            select * from tblseoulrh where s_idx = '$s_idx'
        ";
                
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
    
    // desc	 : 슬라이드 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function seoulrh_url_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "            
            select * from tblseoulrh where s_show = 'Y' and s_year = '$s_year'
        ";
                
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	// desc	 : 슬라이드 등록
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function seoulrh_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		$qry = "
			INSERT INTO
				tblseoulrh
				(
                    s_idx,
                    s_year,
                    s_content1,
                    s_content2,		
                    s_content3,		
                    s_content4,		
                    s_content5,		
                    s_content6,		
                    s_content7,		
					s_show,					
					s_regdate,
					s_type
				)
				VALUES
				(
					''		
					, '$s_year'
                    , '$s_content1'	
                    , '$s_content2'			
                    , '$s_content3'			
                    , '$s_content4'			
                    , '$s_content5'			
                    , '$s_content6'			
                    , '$s_content7'					
					, '$s_show'
					,  NOW()
					, '$s_type'
				)
			";
			

		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}	
	
	// desc	 : 태그 리스트
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function seoulrh_List_year ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($s_show)) {
			$addQry .= " AND ";
			$addQry .= " s_show = '$s_show' ";
        }	
        
        if(!empty($s_type)) {
			$addQry .= " AND ";
			$addQry .= " s_type = '$s_type' ";
		}	
						
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "s_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblseoulrh";
		$args['q_where'] = $addQry;
		$args['q_order'] = "s_year desc , s_regdate desc";
		$args['q_group'] = "s_year";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&seracs_key=" . $searcs_key . "&searcs_content=" . $searcs_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
    }

    function seoulrh_List_day ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($s_year)) {
			$addQry .= " AND ";
			$addQry .= " s_year = '$s_year' ";
        }	
        
        if(!empty($s_show)) {
			$addQry .= " AND ";
			$addQry .= " s_show = '$s_show' ";
        }
        
        if(!empty($s_type)) {
			$addQry .= " AND ";
			$addQry .= " s_type = '$s_type' ";
		}	
	
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "s_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblseoulrh";
		$args['q_where'] = $addQry;
		$args['q_order'] = "s_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&seracs_key=" . $searcs_key . "&searcs_content=" . $searcs_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
        return $C_ListClass -> listInfo($args);
    }
    
    function seoulrh_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($s_show)) {
			$addQry .= " AND ";
			$addQry .= " s_show = '$s_show' ";
        }	
        
        if(!empty($s_type)) {
			$addQry .= " AND ";
			$addQry .= " s_type = '$s_type' ";
        }	
		
		if ($searcs_key && $searcs_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $searcs_key LIKE ('%$searcs_content%')";
			}
		}
				
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "s_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tblseoulrh";
		$args['q_where'] = $addQry;
		$args['q_order'] = "s_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&seracs_key=" . $searcs_key . "&searcs_content=" . $searcs_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
	}
	
}
?>