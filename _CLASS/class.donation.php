<?
CLASS donation extends Dbconn
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
	function Main_donation_Show($args='') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		if($do_lang != '') {
			$addQry .= " AND do_lang = '$do_lang' ";
		}else{
			$addQry .= " AND do_lang = 'kor' ";
		}
		
		if($do_type != '') {
			$addQry .= " AND do_type = '$do_type' ";
		}else{
			$addQry .= " AND do_type = 'main' ";
		}
		$qry = "
			select * from tbldonation where do_show ='Y' $addQry order by do_regdate asc $limit
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
	function donation_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tbldonation
			set
				do_title = '$do_title',
				do_link = '$do_link',
				do_content = '$do_content',
				do_img = '$do_img',
				do_img2 = '$do_img2',
				do_show = '$do_show',
				do_type = '$do_type'
			where
				do_idx = '$do_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 삭제
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tbldonation where do_idx = '$do_idx'	
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation_ImgUpdate($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$addQry = "";
		if($type == "W") {
			$addQry = " do_img = '' ";
		}else {
			$addQry = " do_img2 = '' ";
		}
		
		$qry = "
			update
				tbldonation
			set				
				$addQry
			where
				do_idx = '$do_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "            
            select * from tbldonation where do_idx = '$do_idx'
        ";
                
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
    }
    
    // desc	 : 슬라이드 정보
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation_Info2($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "            
            select * from tbldonation where do_show = 'Y' and do_type = '$do_type' order by do_idx desc
        ";
                
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	// desc	 : 슬라이드 등록
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		$qry = "
			INSERT INTO
				tbldonation
				(
					do_idx,
					do_title,
					do_link,
					do_content,
					do_img,
					do_img2,					
					do_show,					
					do_regdate,
					do_type
				)
				VALUES
				(
					''		
					, '$do_title'
					, '$do_link'
					, '$do_content'
					, '$do_img'
					, '$do_img2'					
					, '$do_show'
					,  NOW()
					, '$do_type'
				)
			";
			

		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
	}
	
	
	// desc	 : 태그 리스트
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";
		
		if(!empty($do_show)) {
			$addQry .= " AND ";
			$addQry .= " do_show = '$do_show' ";
		}	
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}
				
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "do_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tbldonation";
		$args['q_where'] = $addQry;
		$args['q_order'] = "do_regdate desc";
		$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
    }

    function donation2_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
		$addQry = " 1=1 ";		
		
        if(!empty($do_year)) {
			$addQry .= " AND ";
			$addQry .= " do_year = '$do_year' ";
        }	
		
		if(!empty($do_show)) {
			$addQry .= " AND ";
			$addQry .= " do_show = '$do_show' ";
        }	
        
        if(!empty($do_gubun)) {
			$addQry .= " AND ";
			$addQry .= " do_gubun = '$do_gubun' ";
        }	

        if(!empty($do_select)) {
			$addQry .= " AND ";
			$addQry .= " do_select = '$do_select' ";
        }	
        
        if(!empty($do_type)) {
			$addQry .= " AND ";
			$addQry .= " do_type = '$do_type' ";
        }	   
		
		if ($search_key && $search_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $search_key LIKE ('%$search_content%')";
			}
		}
				
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "do_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tbldonation2";
		$args['q_where'] = $addQry;
		$args['q_order'] = "do_year desc, do_idx, do_regdate desc";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&serach_key=" . $search_key . "&search_content=" . $search_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
    }

    // desc	 : 슬라이드 등록
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation2_Reg($args = '') {
        if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;        
        
		$qry = "
			INSERT INTO
				tbldonation2
				(
                    do_idx,
                    do_cidx,
                    do_year,                    
                    do_gubun,
                    do_select,
                    do_Pay,
                    do_color,	
                    do_img,		
					do_show,					
					do_regdate,
					do_type
				)
				VALUES
				(
                    ''		
                    , '$do_cidx'
                    , '$do_year'                    
                    , '$do_gubun'
                    , '$do_select'
                    , '$do_pay'
                    , '$do_color'			
                    , '$do_img'	
					, '$do_show'
					,  NOW()
					, '$do_type'
				)
            ";            

		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
    }	

    function donation2_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "            
            select * from tbldonation2 where do_idx = '$do_idx'
        ";
                
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
    }

    function donation2_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tbldonation2
			set
                do_year = '$do_year',
				do_gubun = '$do_gubun',
                do_select = '$do_select',
                do_pay = '$do_pay',
				do_show = '$do_show',
				do_type = '$do_type'
			where
				do_idx = '$do_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
    }

    function donation2_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tbldonation2 where do_idx = '$do_idx'	
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
    

    function donation3_List ($args = '') {
		global $C_Func;
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		global $C_ListClass;

		$tail = "";
        $addQry = " 1=1 ";
        
        if(!empty($do_year)) {
			$addQry .= " AND ";
			$addQry .= " do_year = '$do_year' ";
        }	
		
		if(!empty($do_show)) {
			$addQry .= " AND ";
			$addQry .= " do_show = '$do_show' ";
        }	
        
        if(!empty($do_gubun)) {
			$addQry .= " AND ";
			$addQry .= " do_gubun = '$do_gubun' ";
        }	
        
        if(!empty($do_type)) {
			$addQry .= " AND ";
			$addQry .= " do_type = '$do_type' ";
        }	               
		
		if ($searcdo_key && $searcdo_content) {
			if (!empty($addQry)) {
				$addQry .= " AND ";
				$addQry .= " $searcdo_key LIKE ('%$searcdo_content%')";
			}
		}
				
		$args['show_row'] = $show_row;
		$args['show_page'] = 10;
		$args['q_idx'] = "do_idx";
		$args['q_col'] = "*";
		$args['q_table'] = "tbldonation3";
		$args['q_where'] = $addQry;
		$args['q_order'] = "do_year desc, do_idx desc, do_regdate desc";
		//$args['q_group'] = "";
		$args['tail'] = "s_date=" . $s_date . "&e_date=" . $e_date ."&seracdo_key=" . $searcdo_key . "&searcdo_content=" . $searcdo_cotent . "&tt_cate=" . $tt_cate;
		$args['q_see'] = "";
		return $C_ListClass -> listInfo($args);
    }
    
    // desc	 : 슬라이드 등록
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation3_Reg($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		$qry = "
			INSERT INTO
				tbldonation3
				(
                    do_idx,
                    do_year,
                    do_day,
                    do_gubun,
					do_content,		
					do_show,					
					do_regdate,
					do_type
				)
				VALUES
				(
					''		
					, '$do_year'
                    , '$do_day'
                    , '$do_gubun'
					, '$do_content'			
					, '$do_show'
					,  NOW()
					, '$do_type'
				)
			";
			

		$rst =  $this -> DB -> execSqlInsert($qry);
		return $rst;
    }	
    

	function donation3_Info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "            
            select * from tbldonation3 where do_idx = '$do_idx'
        ";
                
		$rst =  $this -> DB -> execSqlOneRow($qry);
		return $rst;
    }

	function donation3_Modi($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			update
				tbldonation3
			set
                do_year = '$do_year',
                do_day = '$do_day',
                do_gubun = '$do_gubun',
				do_content = '$do_content',
				do_show = '$do_show',
				do_type = '$do_type'
			where
				do_idx = '$do_idx'			
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
    }
    
    // desc	 : 슬라이드 삭제
	// auth  : JH 2012-12-05 2012-11-06
	// param
	function donation3_Del($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			delete from tbldonation3 where do_idx = '$do_idx'	
		";
		$rst =  $this -> DB -> execSqlUpdate($qry);
		return $rst;
	}
    
    
	
}
?>