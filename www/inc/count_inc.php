<?
	include_once $_SERVER['DOCUMENT_ROOT'] ."/_init.php";
	include_once($GP->CLS."class.analytics.php");
	$C_Analytics 	= new Analytics;
	
	//접속로그 체크 및 기록-------------------------------------------------------------------------------------------------------------->
	if(!$_COOKIE['new_connect'])
	{	
		setcookie('new_connect',$_SERVER['REMOTE_ADDR'],time()+86400);
		
		$referer = $_SERVER["HTTP_REFERER"];
		$connect_url = $referer;
		
		if (!$connect_url)
			$connect_url="-";		
		
		$cnt_year = date("Y");
		$cnt_month = date("m");
		$cnt_day = date("d");
		$connect_ip = $_SERVER['REMOTE_ADDR'];
		$connect_agent = $_SERVER['HTTP_USER_AGENT'];		
		
		$browser = $C_Func->ckBrowser();  //브라우저 정보
		$os = $C_Func->ckOs(); //OS 정보
		$bot = $C_Func->ckBot(); 
		$engine = $C_Func->ckEngine(); 
		
		$args = "";
		$args['cnt_year'] = $cnt_year;
		$args['cnt_month'] = $cnt_month;
		$args['cnt_day'] = $cnt_day;
		$args['connect_ip'] = $connect_ip;
		$rst = $C_Analytics->Analytics_Check($args);
		$cnt_connect = $rst['cnt'];
		
		if($cnt_connect <= 0)	{
			$args['s_StatusIP'] = $_SERVER['REMOTE_ADDR'];
			$args['s_StatusURL'] = $_SERVER['PHP_SELF'];
			$args['s_StatusReferer'] = $connect_url;	
			$args['s_SearchBot'] = $bot;	
			$args['s_SearchEngine'] = $engine;	
			$args['s_Browser'] = $browser;
			$args['s_OS'] = $os;
			$args['s_Agent'] = $_SERVER['HTTP_USER_AGENT'];
			$rst = $C_Analytics->Analytics_Insert($args);			
		}
	}
?>