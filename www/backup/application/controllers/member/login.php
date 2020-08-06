<?php
class Login extends CI_Controller {
	var $conf;
	
	function __construct() {
		parent::__construct();
		
		$this->load->model('M_mb_infor');
        $this->load->helper('cookie');

		$this->conf = $this->M_mb_infor->getConfig('mcf_skin, mcf_login_param');
	}

	function index() {
		$this->qry();
	}

	function qry($msg=FALSE) {
		
		$url = $this->input->get_post('url');
		
		if(!$url) $url = (is_numeric($msg)) ? '/' : urldecode(str_replace('.', '%', $msg));
		
		if(IS_MEMBER)
			goto_url($url);
		
		$reId = get_cookie('ck_mb_id');
		
		$user_alert = $guest_alert = '';
		switch($msg) {
			case 1 :
				$user_alert = "alert('아이디 또는 비밀번호가 올바르지 않습니다.\\n\\n비밀번호는 대소문자를 구분합니다.'); $('#mb_id').focus();";
				break;
			case 2 :
				$guest_alert = "alert('주문번호 또는 비밀번호가 올바르지 않습니다.\\n\\n비밀번호는 대소문자를 구분합니다.'); $('#od_no').focus();";
				break;
			default :
				$user_alert = "$('#mb_id').focus();";
				break;
		}
		
		if($url == '/shop/bought') {
			$guest_login = $this->load->view('member/guest_login', array('url'=>$url,'alt_script'=>$guest_alert), TRUE);
		}
		
		$vars = array_merge( array(
			'_TITLE_'		=> '로그인',
			'_BODY_'		=> 'member/'. $this->conf['mcf_skin'] .'/login',
			'_CSS_'			=> array('member'),
			'_JS_'			=> array('capslock', 'jvalidate', 'jvalid_ext'),
			
			'IMG_PATH'		=> IMG_DIR.'/member/'. $this->conf['mcf_skin'],
			
			'url'			=> $url,
			'alt_script'	=> $user_alert,
			'reId'			=> $reId,
			'chk_reId'		=> $reId ? "checked='checked'" : '',
			
			'guest_login'	=> isset($guest_login) ? $guest_login : ''
		), $this->conf['login_param']);
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}

	function in() {
		$url = $this->input->get_post('url');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules(array(
			array('field'=>'mb_id',			'label'=>'아이디',	'rules'=>'trim|required|min_length[4]|max_length[20]|alpha_dash|xss_clean'),
			array('field'=>'mb_password',	'label'=>'비밀번호',	'rules'=>'trim|required|md5')
		));
		
		if($this->form_validation->run() !== FALSE) {
			$this->load->library('encrypt');
			$mb = $this->M_basic->get_member($this->input->post('mb_id'), 'mb_id, mb_password, mb_email, mb_leave_date, mb_email_certify');
			
			if(!$mb || $this->input->post('mb_password') !== $this->encrypt->decode($mb['mb_password']))
				goto_url('member/login/qry/1?url='.$url);

			if($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date('Ymd', time())) {
				$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
				alert("탈퇴한 아이디이므로 접근하실 수 없습니다.\\n\\n탈퇴일 : ".$date);
			}

			if($this->config->item('cf_use_email_certify') && !preg_match("/[1-9]/", $mb['mb_email_certify']))
				alert("메일인증을 받으셔야 로그인 하실 수 있습니다.\\n\\n회원님의 메일주소는 ".$mb['mb_email']." 입니다.");
			
			$this->session->set_userdata('ss_mb_id', $mb['mb_id']);
			
			if($this->input->post('reId')) {
				$cookie = array(
				   'name'   => 'ck_mb_id',
				   'value'  => $mb['mb_id'],
				   'expire' => 86400*30,
				   'domain' => $this->config->item('cookie_domain')
			   );
			   set_cookie($cookie);
			}
			else if (get_cookie('ck_mb_id'))
				delete_cookie('ck_mb_id');
			
			goto_url($url);
		}
		
		goto_url('/');
	}
	
	function out() {
		if (IS_MEMBER) {
			$this->session->sess_destroy();
		}
		goto_url('/');
	}
	
	function guest_in() {
		$url = $this->input->get_post('url');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules(array(
			array('field'=>'od_no',			'label'=>'주문번호',	'rules'=>'trim|required|min_length[20]|max_length[20]|alpha_dash|xss_clean'),
			array('field'=>'od_password',	'label'=>'비밀번호',	'rules'=>'trim|required|md5')
		));
		
		if($this->form_validation->run() !== FALSE) {
			$this->load->library('encrypt');
			$this->load->model('M_shop');
			
			$od = $this->M_shop->get_order($this->input->post('od_no'), 'od_no,od_password');

			if(!$od || $this->input->post('od_password') !== $this->encrypt->decode($od['od_password']))
				goto_url('member/login/qry/2?url='.$url);
			
			$this->session->set_userdata('ss_od_no', $od['od_no']);
			
			goto_url($url .'/lists/'.$od['od_no']);
		}
		
		goto_url('/');
	}
}
?>
