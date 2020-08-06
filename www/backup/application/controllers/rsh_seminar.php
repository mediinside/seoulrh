<?php
class Rsh_seminar extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model('M_research');
	}
	
	function get_hidden($create_html=TRUE) {
		$this->load->helper('form');
		
		$this->hidden_flds = array(
			'sex'			=> setValue($this->session->userdata('ss_rsh_sex'), $this->input->post('sex')),
			'type'			=> setValue($this->session->userdata('ss_rsh_type'), $this->input->post('type')),
			'grade'			=> setValue($this->session->userdata('ss_rsh_grade'), $this->input->post('grade')),
			'plan'			=> setValue($this->session->userdata('ss_rsh_plan'), $this->input->post('plan')),
			'time'			=> setValue($this->session->userdata('ss_rsh_time'), $this->input->post('time')),
			'circum'		=> setValue($this->session->userdata('ss_rsh_circum'), $this->input->post('circum')),
			'data'			=> setValue($this->session->userdata('ss_rsh_data'), $this->input->post('data')),
			'recommend'		=> setValue($this->session->userdata('ss_rsh_recommend'), $this->input->post('recommend')),
			'motive'		=> setValue($this->session->userdata('ss_rsh_motive'), $this->input->post('motive')),
			'supplement'	=> setValue($this->session->userdata('ss_rsh_supplement'), $this->input->post('supplement'))
		);
		
		$cnt = $this->M_research->get_semi_count();
		
		for($i = 1; $i <= $cnt; $i++) {
			$this->hidden_flds['sati_'. $i] = setValue($this->session->userdata('ss_rsh_'. 'sati_'. $i), $this->input->post('sati_'. $i)); 
		}
		
		return $create_html ? form_hidden($this->hidden_flds) : $this->hidden_flds;
	}
	
	function index() {
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_index',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min')
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step1() {
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step1',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min')
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step2() {
		$this->chk_form('sex');
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step2',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step3() {
		$this->chk_form('type');
		$this->chk_form('grade');
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step3',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step4() {
		$this->chk_form('motive', FALSE);
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step4',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step5() {
		$this->chk_form('plan');
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step5',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step6() {
		$this->chk_form('time');
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step6',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step7() {
		$this->chk_form('circum');
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step7',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step8() {
		$this->chk_form('data');
		
		$total = $this->M_research->get_semi_count();
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step8',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'act_url'		=> $total == 0 ? '/rsh_seminar/step9' : '/rsh_seminar/satisfaction',
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function satisfaction($cnt=1) {
		if($total = $this->M_research->get_semi_count()) {
			if($cnt > 1) {
				$this->chk_form('sati_'. ($cnt - 1));
			}
		}
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_satisfaction',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'cnt'			=> $cnt,
			'act_url'		=> $cnt == $total ? '/rsh_seminar/step9' : '/rsh_seminar/satisfaction/'. ($cnt + 1),
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step9() {
		if($total = $this->M_research->get_semi_count()) {	
			$this->chk_form('sati_'. $total);
		}
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step9',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function step10() {
		$this->chk_form('supplement', FALSE);
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_step10',
			'_JS_'			=> array('jmobile/jquery.mobile-1.4.0.min'),
			
			'hidden_form'	=> $this->get_hidden()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_mobile_sub', $vars);
	}
	
	function complete() {
		$this->chk_form('recommend');
		
		$form_data = $this->get_hidden(FALSE);
		
		$this->M_research->record('semi', $form_data);
		
		$this->session->sess_destroy(array_keys($form_data));
		
		$vars = array(
			'_TITLE_'		=> '설문조사',
			'_BODY_'		=> 'mobile/research/rsh_semi_complete',
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
				
				'url'		=> '/rsh_seminar'
			);
			
			$this->load->view(LAYOUT_PATH.'/layout_mobile_pop', $vars);
			exit;
		}
	}
}
