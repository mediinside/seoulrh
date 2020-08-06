<?php
class Visit extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_visit');
		$this->load->library('pagination');
		$this->load->helper('search');
	}
	
	function logs() {
		include "init.php";
		
		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sfl  = $seg->get_seg('sfl');
		$stx  = $seg->get_seg('stx');
		$fr_date = $seg->get_seg('from');
		$to_date = $seg->get_seg('to');
		
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		$fr_date = ($fr_date) ? $fr_date : TIME_YMD;
		$to_date = ($to_date) ? $to_date : TIME_YMD;
		
		$config['suffix'] = $seg->get_qstr();
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/visit/logs/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];
		$result = $this->M_a_visit->list_result($fr_date, $to_date, $config['per_page'], $offset);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = $row;
			$list[$i]['lst'] = $i%2;
			$list[$i]['vs_referer'] = str_replace('@/', '/', $row['vs_referer']);
			$list[$i]['vs_title'] = urldecode($row['vs_referer']);
		}
		
		$vars = array(
			'_TITLE_'		=> $fr_date.' ~ '.$to_date.' 방문자 로그',
			'_BODY_'		=> ADM_F.'/visit/visit_logs',
			'_CSS_'			=> array('jquery-ui'),
			'_JS_'			=> array('jquery-ui.min', 'jtimepicker'),
			
			'list'			=> $list,
			'fr_date'		=> $fr_date,
			'to_date'		=> $to_date,
			'paging'		=> $this->pagination->create_links()
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function daily() {
		include "init.php";
	
		$s_mm  = $this->input->get('s_mm');
		$s_mm = ($s_mm) ? $s_mm : date("Y년 m월", time());
	
		$date = preg_replace('/년|월| /', '', $s_mm);
		$year = substr($date, 0, 4);
		$month = substr($date, 4, 2);
	
		$fr_date = $year.'-'.$month.'-01';
		$to_date = $year.'-'.$month.'-31';
	
		$max = 0;
		$max_count = 0;
		$result = $this->M_a_visit->get_count($fr_date, $to_date);
		
		$list = array_fill(1, 31, array_fill_keys(array('count', 'login', 'join'), 0));
		foreach ($result as $i => $row) {
			$day = (int)(substr($row['vsc_regdate'], 8, 2));
			$list[$day]['count'] = $list[$day]['count'] + $row['vsc_count'];
			if($list[$day]['count'] > $max_count) {
				$max_count = $list[$day]['count'];
			}
		}
		
		$max_count = round($max_count + (int)($max_count * 0.1), -(strlen($max_count) - 2));
		
		$vars = array(
			'_TITLE_'		=> $s_mm.' 방문자 통계',
			'_BODY_'		=> ADM_F.'/visit/visit_daily',
			'_CSS_'			=> array('jquery-ui'),
			'_JS_'			=> array('jquery-ui.min', 'jtimepicker', 'https://www.google.com/jsapi'),
					
			's_mm'			=> $s_mm,
			'list'			=> $list,
			'max_count'		=> ($max_count < 10) ? 10 : $max_count
		);
	
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function monthly() {
		include "init.php";
		
		$s_mm  = $this->input->get('s_mm');
		$s_mm = ($s_mm) ? $s_mm : date("Y년", time());
		
		$date = preg_replace('/년| /', '', $s_mm);
		$year = substr($date, 0, 4);
		
		$fr_date = $year.'-01-01';
		$to_date = $year.'-12-31';
		
		$max = 0;
		$max_count = 0;
		$result = $this->M_a_visit->get_count($fr_date, $to_date);
		
		$max_count = 0;
		$list = array_fill(1, 12, array_fill_keys(array('count', 'login', 'join'), 0));
		foreach ($result as $i => $row) {
			$month = (int)(substr($row['vsc_regdate'], 5, 2));
			$list[$month]['count'] = $list[$month]['count'] + $row['vsc_count'];
			if($list[$month]['count'] > $max_count) {
				$max_count = $list[$month]['count'];
			}
		}
		
		$max_count = round($max_count + (int)($max_count * 0.1), -(strlen($max_count) - 2));
		
		$vars = array(
			'_TITLE_'		=> $s_mm.' 방문자 통계',
			'_BODY_'		=> ADM_F.'/visit/visit_monthly',
			'_CSS_'			=> array('jquery-ui'),
			'_JS_'			=> array('jquery-ui.min', 'jtimepicker', 'https://www.google.com/jsapi'),
			
			's_mm'			=> $s_mm,
			'list'			=> $list,
			'max_count'		=> ($max_count < 10) ? 10 : $max_count
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function hourly() {
		include "init.php";
		
		$seg  = new search_seg;
		$fr_date = $seg->get_seg('from');
		$to_date = $seg->get_seg('to');
		
		$fr_date = ($fr_date) ? $fr_date : date('Y-m-d', strtotime('-1 week', strtotime(TIME_YMD)));
		$to_date = ($to_date) ? $to_date : TIME_YMD;
		
		$max = 0;
		$sum_count = 0;
		$result = $this->M_a_visit->get_count($fr_date, $to_date, 'vsc_hour');
		
		$max_count = 0;
		$list = array_fill(0, 24, array_fill_keys(array('count', 'login', 'join'), 0));
			foreach ($result as $i => $row) {
			$hour = $row['vsc_hour'];
			$list[$hour]['count'] = $list[$hour]['count'] + $row['vsc_count'];
			if($list[$hour]['count'] > $max_count) {
				$max_count = $list[$hour]['count'];
			}
		}
		$max_count = round($max_count + (int)($max_count * 0.1), -(strlen($max_count) - 2));
		
		$vars = array(
			'_TITLE_'		=> $fr_date.' ~ '.$to_date.' 방문자 통계',
			'_BODY_'		=> ADM_F.'/visit/visit_hourly',
			'_CSS_'			=> array('jquery-ui'),
			'_JS_'			=> array('jquery-ui.min', 'jtimepicker', 'https://www.google.com/jsapi'),
			
			'fr_date'		=> $fr_date,
			'to_date'		=> $to_date,
			'list'			=> $list,
			'max_count'		=> ($max_count < 10) ? 10 : $max_count
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function browser() {
		include "init.php";
		
		$seg  = new search_seg;
		$fr_date = $seg->get_seg('from');
		$to_date = $seg->get_seg('to');
		
		$fr_date = ($fr_date) ? $fr_date : date('Y-m-d', strtotime('-1 week', strtotime(TIME_YMD)));
		$to_date = ($to_date) ? $to_date : TIME_YMD;
		
		$max = 0;
		$sum_count = 0;
		$result = $this->M_a_visit->get_count($fr_date, $to_date, 'vsc_browser');
		
		$max_count = 0;
		$list = array_fill_keys(array('IE4.x','IE5','IE5.5','IE6','IE7','IE8','IE9','IE10','Safari','Firefox','Chrome','Netsc.','Opera','Gecko','Robot','Mozilla','기타'), array_fill_keys(array('count', 'login', 'join'), 0));
		foreach ($result as $i => $row) {
			$browser = $row['vsc_browser'];
			$list[$browser]['count'] = $list[$browser]['count'] + $row['vsc_count'];
			if($list[$browser]['count'] > $max_count) {
				$max_count = $list[$browser]['count'];
			}
		}
		$max_count = round($max_count + (int)($max_count * 0.1), -(strlen($max_count) - 2));
		
		$vars = array(
			'_TITLE_'		=> $fr_date.' ~ '.$to_date.' 방문자 브라우저 통계',
			'_BODY_'		=> ADM_F.'/visit/visit_browser',
			'_CSS_'			=> array('jquery-ui'),
			'_JS_'			=> array('jquery-ui.min', 'jtimepicker', 'https://www.google.com/jsapi'),
			
			'fr_date' => $fr_date,
			'to_date' => $to_date,
			'list' => $list,
			'max_count' => ($max_count < 10) ? 10 : $max_count
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function os() {
		include "init.php";
		
		$seg  = new search_seg;
		$fr_date = $seg->get_seg('from');
		$to_date = $seg->get_seg('to');
	
		$fr_date = ($fr_date) ? $fr_date : date('Y-m-d', strtotime('-1 week', strtotime(TIME_YMD)));
		$to_date = ($to_date) ? $to_date : TIME_YMD;
	
		$max = 0;
		$sum_count = 0;
		$result = $this->M_a_visit->get_count($fr_date, $to_date, 'vsc_os');
	
		$max_count = 0;
		$list = array_fill_keys(array('Win98','WinME','WinXP','WinVista','Win7','WinCE','MAC','Linux','iOS','Android','B.Berry','Symbian','Bada','Robot','기타'), array_fill_keys(array('count', 'login', 'join'), 0));
		foreach ($result as $i => $row) {
			$os = $row['vsc_os'];
			$list[$os]['count'] = $list[$os]['count'] + $row['vsc_count'];
			if($list[$os]['count'] > $max_count) {
				$max_count = $list[$os]['count'];
			}
		}
		$max_count = round($max_count + (int)($max_count * 0.1), -(strlen($max_count) - 2));
		
		$vars = array(
			'_TITLE_'		=> $fr_date.' ~ '.$to_date.' 방문자 OS 통계',
			'_BODY_'		=> ADM_F.'/visit/visit_os',
			'_CSS_'			=> array('jquery-ui'),
			'_JS_'			=> array('jquery-ui.min', 'jtimepicker', 'https://www.google.com/jsapi'),
			
			'fr_date' => $fr_date,
			'to_date' => $to_date,
			'list' => $list,
			'max_count' => ($max_count < 10) ? 10 : $max_count
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
}
?>
