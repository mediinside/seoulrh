<?php
class Leave extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library(array('form_validation', 'encrypt'));
		$this->load->model('M_mb_infor');
        $this->load->helper('cookie');
		$this->load->config('cf_register');
	}

	function index($msg=false) {
		if (!IS_MEMBER)
			goto_url('/');
		
		$vars = array_merge( array(
			'_TITLE_'		=> '회원탈퇴',
			'_BODY_'		=> 'member/leave',
			'_CSS_'			=> array('member'),
			
			'img_path'		=> IMG_DIR.'/member',
			'msg'			=> $msg
		), $this->config->item('cf_page_leave') );
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}

	function confirm() {
		$this->form_validation->set_rules(array(
			array('field'=>'mb_id', 'label'=>'아이디', 'rules'=>'trim|required|min_length[3]|max_length[20]|alpha_dash|xss_clean'),
			array('field'=>'mb_password', 'label'=>'비밀번호', 'rules'=>'trim|required|md5')
		));

		if ($this->form_validation->run() !== FALSE) {
			$this->load->library('encrypt');
			$member = unserialize(MEMBER);
			$mb = $this->M_basic->get_member($member['mb_id'], 'mb_id, mb_password, mb_email, mb_leave_date, mb_email_certify');
			
			if ($member['mb_id'] != $this->input->post('mb_id') || $this->input->post('mb_password') !== $this->encrypt->decode($mb['mb_password']))
				die('아이디 또는 비밀번호가 올바르지 않습니다.');
			else
				die('000');
		}
	}
	
	function update() {
		$this->M_mb_infor->leave();

		alert('회원탈퇴가 완료되었습니다.\\n\\n그동안 이용해 주셔서 감사합니다.','member/login/out');
	}
}
?>
