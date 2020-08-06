<?php
class Rsh_satisfaction extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->model('M_research');
	}
	
	function get_hidden($create_html=TRUE) {
		$this->load->helper('form');
	
		$this->hidden_flds = array(
			'circum'			=> setValue($this->session->userdata('ss_rsh_circum'), $this->input->post('circum')),
			'process'			=> setValue($this->session->userdata('ss_rsh_process'), $this->input->post('process')),
			'kind'				=> setValue($this->session->userdata('ss_rsh_kind'), $this->input->post('kind')),
			'service'			=> setValue($this->session->userdata('ss_rsh_service'), $this->input->post('service')),
			'route'				=> setValue($this->session->userdata('ss_rsh_route'), $this->input->post('route')),
			'recommend'			=> setValue($this->session->userdata('ss_rsh_recommend'), $this->input->post('recommend')),
			'homepage'			=> setValue($this->session->userdata('ss_rsh_homepage'), $this->input->post('homepage')),
			'newspaper'			=> setValue($this->session->userdata('ss_rsh_newspaper'), $this->input->post('newspaper'))
		);
	
		return $create_html ? form_hidden($this->hidden_flds) : $this->hidden_flds;
	}
	
	function index() {
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_index',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min')
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step1() {
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step1',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min')
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step2() {
		$this->chk_form('circum');
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step2',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step3() {
		$this->chk_form('process');
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step3',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step4() {
		$this->chk_form('kind');
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step4',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step5() {
		$this->chk_form('service');
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step5',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step6() {
		$this->chk_form('route');
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step6',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step7() {
		$this->chk_form('recommend');
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step7',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step8() {
		$this->chk_form('homepage');
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_step8',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function complete() {
		$this->chk_form('newspaper');
		
		$form_data = $this->get_hidden(FALSE);
		
		$this->M_research->record('sati', $form_data);
		
		$this->session->sess_destroy(array_keys($form_data));
		
		$vars = array(
			'_TITLE_'		=> '만족도조사',
			'_BODY_'		=> 'mobile/research/rsh_sati_complete',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min')
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_pop', $vars);
	}
	
	function chk_form($name, $show_err=TRUE) {
		if($this->input->post($name)) {
			$this->session->set_userdata('ss_rsh_'. $name, $this->input->post($name));
		}
		else if(!$this->session->userdata('ss_rsh_'. $name) && $show_err) {
			$vars = array(
				'_TITLE_'		=> '설문조사',
				'_BODY_'		=> 'mobile/research/rsh_error',
				'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
				
				'url'		=> '/rsh_satisfaction'
			);
			
			$this->load->view(LAYOUT_PATH.'/layout_mobile_pop', $vars);
			exit;
		}
	}
}
