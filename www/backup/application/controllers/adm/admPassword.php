<?php
class admPassword extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->library('encrypt');
		$this->load->model(ADM_F.'/M_a_member');
	}

	function index() {
		include "init.php";
		
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'old_password', 'label'=>'현재 비밀번호', 'rules'=>'trim|required|min_length[3]|md5'),
			array('field'=>'new_password', 'label'=>'새 비밀번호', 'rules'=>'trim|required|min_length[3]|md5'),
			array('field'=>'new_password_re', 'label'=>'새 비밀번호 확인', 'rules'=>'trim|required|min_length[3]|matches[new_password]|md5')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$vars = array(
				'_TITLE_'		=> '관리자 비밀번호 변경',
				'_BODY_'		=> ADM_F.'/admPassword',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jvalid_ext'),
				
				'token'			=> get_token(),
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();

			if (!($this->encrypt->decode($member['mb_password']) == $this->input->post('old_password') && $this->input->post('old_password')))
				alert('현재 비밀번호가 맞지 않습니다.', ADM_F.'/admPassword');
			
			$res = $this->M_a_member->update_Pass($member['mb_id']);

			if(!$res)
				alert('ERROR: 오류로 인하여 비밀번호가 변경되지 않았습니다.', ADM_F.'/admPassword');
			else
				alert('비밀번호가 변경되었습니다.', ADM_F.'/admPassword');
		}
	}
}
?>
