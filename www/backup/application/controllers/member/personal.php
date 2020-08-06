<?php
class Personal extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$vars = array(
			'_TITLE_'		=> '개인정보보호정책',
			'_BODY_'		=> 'member/personal'
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
}
?>
