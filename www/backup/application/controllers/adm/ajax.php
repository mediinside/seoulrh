<?php
class Ajax extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function select_cssjs() {
		include "init.php";
		
		$this->load->helper('form');
		
		$path = $this->input->get_post('path');
		$name = $this->input->get_post('name');
		$val = $this->input->get_post('value');
		
		$files = get_files(DOC_ROOT . $path);

		$arr = array();
		if($files) {
			foreach($files AS $file) {
				$arr[preg_replace('/(\.css|\.js)$/','',$file)] = $file;
			}
		}

		if($val && !isset($arr[$val])) {		// 설정된 파일이 없음
			die('100');
		}
		else if(!$files) {						// 파일 없음
			die('200');
		}

		$_html = preg_replace('/<option/', '<option value="">선택</option><option', form_dropdown($name, $arr, $val), 1);
		
		die($_html);
	}
}
?>
