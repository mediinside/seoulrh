<?
CLASS Main extends Dbconn
{
	private $DB;
	private $GP;
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	// desc	 : 배너 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Main_Winng_List($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;				
		
		$qry = " 
				select 
					* 
				from 
					tblwebzineboard TWZB 
				where 
					twz_idx = '$twz_idx' 
					and twzb_del_flag = 'N' 
					and twzb_banner_show = 'Y'
				order by twzb_desc desc
			";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;
	}	
	
	// desc	 : 메인 슬라이드 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Main_Slide_List($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;	
		$qry = "
			select * from tblwebzineslide where twz_idx='$twz_idx' and twzs_show='Y' and twzs_del_flag='N' order by twzs_desc desc			
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 호 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function HO_List($args = '') {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;				
		
		$qry = " select * from tblWebzine where twz_show = 'Y' order by twz_desc desc";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 호 정보
	// auth  : JH 2013-09-16 월요일
	// param
	function HO_Info($twz_idx) {		
		$qry = " select * from tblWebzine where twz_idx = '$twz_idx'";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	// desc	 : 상단 배너 2개
	// auth  : JH 2013-09-16 월요일
	// param
	function Top_Banner_Info() {		
		$qry = " select * from tblBanner where bn_show ='Y' and bn_type = 'T' and bn_del_flag ='N' order by bn_desc desc limit 0,2";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 카테고리
	// auth  : JH 2013-09-16 월요일
	// param
	function Cate_List($args) {		
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = " select * from tblCategory where ct_idx in ($twz_cate) and ct_show ='Y' and ct_del_flag ='N' order by ct_desc asc";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 아티클 리스트
	// auth  : JH 2013-09-16 월요일
	// param
	function Main_article_List($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;				
		
		if($twz_search != '') {
			$addQry = "	and (TWZB.twzb_title like '%$twz_search%' or TWZB.twzb_content like '%$twz_search%') ";
		}
		
		if($ct_idx != '') {
			$addQry = "	and TWZB.ct_idx='$ct_idx' ";
		}
		
		$qry = " 
				select 
					* 
				from 
					tblwebzineboard TWZB LEFT OUTER JOIN tblCategory CT ON TWZB.ct_idx  = CT.ct_idx 
				where 
					TWZB.twz_idx = '$twz_idx' 
					and TWZB.twzb_del_flag = 'N' 
					$addQry
				order by TWZB.twzb_desc desc
			";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 좌측 배너
	// auth  : JH 2013-09-16 월요일
	// param
	function Left_Banner_Info() {		
		$qry = " select * from tblBanner where bn_show ='Y' and bn_type='L' and bn_del_flag ='N' order by bn_desc desc ";
		$rst = $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	
	// desc	 : 메인에 글 가져오기
	// auth  : JH 2013-04-26
	// param 
	function Board_Main_Data($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			SELECT 
				A.*, B.jb_content 
			FROM 
				tblJhBoardList A INNER JOIN tblJhBoardDetail B ON A.jb_idx=B.jb_idx
			WHERE 
				A.jb_code='$jb_code' 
				and A.jb_del_flag = 'N'
			order by A.jb_idx desc
			$limit	
		";
		$rst =  $this -> DB -> execSqlList($qry);
		return $rst;
	}
	
	// desc	 : 아티클 상세
	// auth  : JH 2013-09-16 월요일
	// param
	function Artice_info_Detail($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		
		$qry = "
			select
					twzb_desc
				from
					tblwebzineboard 
				where
					twz_idx = '$twz_idx'
					and twzb_idx = '$twzb_idx'
		";
		$twzb_arr = $this -> DB -> execSqlOneRow($qry);
		$twzb_desc = $twzb_arr['twzb_desc'];
		
		
		$arr_tmp = array();
		//이전글
		$qry1 = "
				select
					*
				from
					tblwebzineboard 
				where
					twz_idx = '$twz_idx'
					and twzb_desc < '$twzb_desc'
				order by twzb_desc desc limit 0, 1
		";	
		$arr_tmp[0] = $this -> DB -> execSqlOneRow($qry1);


		//본데이터
		$qry2 = "
				select 
					* 
				from 
					tblwebzineboard TWZB LEFT OUTER JOIN tblCategory CT ON TWZB.ct_idx  = CT.ct_idx
				where 					
					TWZB.twzb_idx = '$twzb_idx' 
					and TWZB.twz_idx = '$twz_idx' 
					and TWZB.twzb_del_flag = 'N'
				order by TWZB.twzb_desc desc
		";
		$arr_tmp[1] = $this -> DB -> execSqlOneRow($qry2);


		//다음글
		$qry3 = "
			select
					*
				from
					tblwebzineboard 
				where
					twz_idx = '$twz_idx'					
					and twzb_desc > '$twzb_desc'
				order by twzb_desc asc limit 0, 1

		";
		$arr_tmp[2] = $this -> DB -> execSqlOneRow($qry3);
		return $arr_tmp;
	}
	
	
	// desc	 : 서브 커버 노출 이미지
	// auth  : JH 2013-09-16 월요일
	// param
	function Sub_img_info($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;				
		
		$qry = " select * from tblwebzinecover WC LEFT OUTER JOIN tblCategory CT ON WC.ct_idx=CT.ct_idx where WC.twz_idx = '$twz_idx' and WC.twzc_show ='Y' order by WC.twzc_desc desc limit 0,1 ";
		$rst = $this -> DB -> execSqlOneRow($qry);
		return $rst;
	}
	
	
	
	
}
?>