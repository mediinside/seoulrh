<?php
class Config extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->model(array(ADM_F.'/M_a_config', 'M_config'));
		$this->load->helper('directory');
	}

	function index() {
		include "init.php";
		
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'cf_domain',		'label'=>'도메인',				'rules'=>'trim|max_length[50]|required|xss_clean'),
			array('field'=>'cf_title',		'label'=>'홈페이지 이름',		'rules'=>'trim|max_length[50]|required|xss_clean'),
			array('field'=>'cf_webmaster',	'label'=>'자동발송 메일주소',	'rules'=>'trim|max_length[50]|required|xss_clean')
		);
		
		$conf = $this->M_config->getConfig();
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$vars = array(
				'_TITLE_'		=> '홈페이지 설정',
				'_BODY_'		=> ADM_F.'/config',
				'_JS_'			=> array('jvalidate', 'jvalid_ext'),
				
				'conf'			=> $conf
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			$data = array(
				'cf_domain'			=> $this->input->post('cf_domain'),
				'cf_title'			=> $this->input->post('cf_title'),
				'cf_webmaster'		=> $this->input->post('cf_webmaster'),
				'cf_mobile'			=> $this->input->post('cf_mobile')
			);
			
			$this->M_a_config->setConfig($data);
			
			goto_url(ADM_F.'/config');
		}
	}
}
?>
