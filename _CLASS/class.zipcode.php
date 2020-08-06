<?
CLASS Zipcode extends Dbconn 
{	
	private $DB;
	private $GP;	
	function __construct($DB = array()) {
		global $C_DB, $GP;
		$this -> DB = (!empty($DB))? $DB : $C_DB;
		$this -> GP = $GP;
	}
	
	// desc	 : 
	// auth  : JH 2012-10-10 수요일
	// param :
	function searchDong($args = '') { 
	
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			SELECT
				*
			FROM
				zipcode
			where 
				zc_dong like '%" . $zc_dong . "%'
		";		

		$rst = $this -> DB -> execSqlList($qry);
		return $rst;		
	}	
	
	// desc	 : 
	// auth  : JH 2012-10-10 수요일
	// param :
	function Gugun_list($args = '') {
		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;
		
		$qry = "
			SELECT 
				zc_gugun
			FROM  
				zipcode 
			WHERE 
				zc_sido =  '$zc_sido'
			GROUP BY zc_gugun
		";		

		$rst = $this -> DB -> execSqlAssoc($qry);
		return $rst;		
	}
}
?>