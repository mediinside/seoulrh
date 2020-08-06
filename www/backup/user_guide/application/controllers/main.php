<?php
class Main extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('M_content', 'M_latest', 'M_popup', 'M_banner'));
	}

	function index() {
		$content_file = 'index';		// 인덱츠 컨텐츠가 메인
		
		$content = $this->M_content->get_content($content_file);
		
		if(!$content) {
			show_404();
		}
		
		$layout = $this->M_layout->row($content['ct_layout']);
		
		// 관리자 컨텐츠 설정 데이터
		$dbVars = $this->M_dbvars->get_data('content', $content['ct_id']);
		$this->load->vars( getContent($dbVars) );
		
		// 변수 데이터
		$ct_parameter = array();
		if($content['ct_parameter']) {
			foreach(json_decode($content['ct_parameter']) AS $key => $val) {
				$ct_parameter[$key] = $val;
			}
		}
		
		// 시스템 데이터
		$vars = array(
			'_TITLE_'		=> $this->config->item('cf_title'),
			'_BODY_'		=> 'main/content',
			
			'_CONTAINER_'	=> HTML_PATH.'/'.$content['ct_filename'],

			'notice' 	   	=> $this->M_latest->write('notice', 3, 55),
			'info' 	   		=> $this->M_banner->latest(2, 3, '115x95'),
			
			'popup'			=> $this->M_popup->output()
		);
		
		$this->load->view(LAYOUT_PATH.'/'.$layout['ly_file'], array_merge($vars, $ct_parameter));
	}
}
?>
