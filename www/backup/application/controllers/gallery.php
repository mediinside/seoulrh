<?php
class Gallery extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('M_search');
		$this->load->helper(array('board', 'search', 'textual'));
		
		define('BO_IMG_PATH',	IMG_DIR.'/board');
	}
	
	function lists() {
		$vars = array(
			'_TITLE_'		=> $this->config->item('cf_title'),
			'_BODY_'		=> 'gallery/lists',
		
			'pageNum'		=> '0',
			'subNum'		=> '0',
			'mainTitle'		=> '갤러리',
			'subTitle'		=> get_text(stripslashes($stx))
		);
		
		$this->load->view(setValue(LAYOUT_PATH.'/layout_sub', $this->layout), $vars);
	}
}
?>
