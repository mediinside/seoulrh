<?php
class Content extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('M_content', 'M_latest', 'M_popup', 'M_banner'));
	}

	function index() {
		//$content_url = $this->uri->uri_string;
		$content_url = implode('/', $this->uri->segments);
		$content = $this->M_content->get_content($content_url);
		
		if(!$content) {
			show_404();
		}
		
		$layout = $this->M_layout->row($content['ct_layout']);
		
		// 관리자 컨텐츠 설정 데이터
		$dbVars = $this->M_dbvars->get_data('content', $content['ct_id']);
		$this->load->vars( getContent($dbVars) );
		
		// 시스템 데이터
		$vars = array(
			'_TITLE_'		=> $this->config->item('cf_title'),
			'_BODY_'		=> 'main/content',
			
			'_CONTAINER_'	=> HTML_PATH.'/'.$content['ct_filename'],
			
			'popup'			=> $content_url == 'index' ? $this->M_popup->output() : ''
		);
		
		$this->load->view(LAYOUT_PATH.'/'.$layout['ly_file'], array_merge($vars, param_decode($content['ct_parameter'])));
	}
}
?>
