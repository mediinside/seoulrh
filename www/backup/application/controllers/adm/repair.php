<?php
class Repair extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_repair');
		$this->load->dbutil();
	}

	function index() {
		include "init.php";
		
		$this->M_a_repair->delete_popular();
		$this->M_a_repair->delete_memo();

		$rep_result = $opt_result = FALSE;
		$tables = $this->db->list_tables();
		foreach ($tables as $table) {
			// 테이블 수리	
			if (!$this->dbutil->repair_table($table))
			    $rep_result .= $table.' 실패 <br/>';

			// 테이블 최적화 
			if (!$this->dbutil->optimize_table($table))
			    $opt_result .= $table.' 실패 <br/>';
		}

		// 하루가 지난 임시파일 삭제
		$tmpPath = DATA_PATH.'/temp';
		$dir = opendir($tmpPath);
		while($file = readdir($dir)) {
			if($file == '.' || $file == '..') continue;
			else {
				$fp = fopen($tmpPath."/".$file, "r");
				$fstat = fstat($fp);
				if($fstat['mtime'] < time()-86400) {
					fclose($fp);
					@unlink($tmpPath."/".$file); // 파일삭제
				}
			}
		}
		closedir($dir);
		
		$vars = array(
			'_TITLE_'		=> '테이블 복구 및 최적화',
			'_BODY_'		=> ADM_F.'/repair',
			
			'rep_result'	=> ($rep_result) ? $rep_result : '테이블 수리 완료',
			'opt_result'	=> ($opt_result) ? $opt_result : '테이블 최적화 완료',
			'tmp_result'	=> '임시파일 삭제 완료'
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
}
?>
