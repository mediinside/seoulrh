<?php
class Main extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->helper('number');
	}

	function index() {
		include "init.php";
		
		$pubasic = $pulayer = array();
		
		// 계정의 사용량을 구함 
		$account_space = `du -sb`; 
		$account_space = substr($account_space,0,strlen($account_space)-3);
		// DATA 폴더의 용량을 구함
		$data_path = DATA_PATH; 
		$data_space = `du -sb $data_path`; 
		$data_space = substr($data_space,0,strlen($data_space)-8); 

		// GD 버젼
		$gd_support = extension_loaded('gd');
		if ($gd_support) {
			$gd_info = gd_info();
			$gd_version = $gd_info['GD Version'];
		} else {
			$gd_version = 'GD가 설치되지 않음';
		}

		// MySQL 버전
		$query = $this->db->query('select version() as ver');
		$row = $query->row_array();
		$db_version = $row['ver'];

		$vars = array(
			'_BODY_'		=> ADM_F.'/main',
			
			'pubasic'		=> $pubasic,
			'pulayer'		=> $pulayer,
			'os_version'	=> php_uname('r'),
			'ip_addr'		=> gethostbyname(trim(`hostname`)),
			'account_space'	=> byte_format($account_space),
			'data_space'	=> byte_format($data_space),
			'code_space'	=> byte_format($account_space - $data_space),
			'apache_version'=> apache_get_version(),
			'php_version'	=> phpversion(),
			'zend_version'	=> zend_version(),
			'gd_version'	=> $gd_version,
			'max_filesize'	=> get_cfg_var('upload_max_filesize'),
			'db_version'	=> $db_version,
			'db_date'		=> '', // $db_date
			'db_status'		=> '' // $db_status
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
}
?>
