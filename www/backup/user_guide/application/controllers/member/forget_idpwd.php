<?php
class Forget_idpwd extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->config->load('cf_register');
		$this->load->library('email');
		$this->load->model('M_mb_forget');
		$this->load->config('cf_register');
	}
	
	function index($not_mb_id=true) {
		if (IS_MEMBER)
			alert('이미 로그인 중입니다.');

		$mb_id = $this->input->get('id');
		
		$vars = array_merge( array(
			'_TITLE_'		=> '회원아이디 / 비밀번호 찾기',
			'_BODY_'		=> 'member/forget_idpwd',
			'_CSS_'			=> array('member'),
			
			'img_path'		=> IMG_DIR.'/member',
			'not_mb_id'		=> $not_mb_id,
			'mb_id'			=> $mb_id,
			'use_jumin'		=> $this->config->item('cf_use_jumin')
		), $this->config->item('cf_page_idpwd') );
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
	
	function id() {
		$this->index(true);
	}
	
	function pw() {
		$this->index(false);
	}
	
	function step2() {
		if (!$this->input->post('w'))
			goto_url('/');
		
		$not_mb_id = $this->input->post('not_mb_id');
		
		$mb = $this->M_mb_forget->check();
		
		if(!isset($mb['mb_id']))		alert('입력하신 정보의 회원을 찾을 수 없습니다.');
		else if($mb['mb_id'] == ADMIN)	alert('관리자 아이디는 이용하실 수 없습니다.');

		$this->load->helper('textual');
		$mb['mb_password_q'] = get_text($mb['mb_password_q']);

		$vars = array_merge( array(
			'_TITLE_'		=> '비밀번호 찾기 2단계',
			'_BODY_'		=> 'member/forget_pwd',
			'_CSS_'			=> array('member'),
			
			'img_path'		=> IMG_DIR.'/member',
			'time'			=> time(),
			'mb_id'			=> $mb['mb_id'],
			'mb_password_q'	=> $mb['mb_password_q']
		), $this->config->item('cf_page_confirm') );
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}

	function ajax_find_password() {
		check_wrkey();

		$this->session->set_flashdata('mb_idpwd', $this->input->post('mb_id'));

		$mb = $this->M_basic->get_member($this->input->post('mb_id'), 'mb_id, mb_email, mb_password_a');
		
		if(!isset($mb['mb_id']))		die( json_encode(array('result' => '100', 'msg' => '입력하신 정보의 회원을 찾을 수 없습니다.')) );
		else if($mb['mb_id'] == ADMIN)	die( json_encode(array('result' => '200', 'msg' => '관리자 아이디는 이용하실 수 없습니다.')) );
		else if($this->input->post('mb_password_a') !== $mb['mb_password_a'])
										die( json_encode(array('result' => '300', 'msg' => '비밀번호 찾기 답변이 일치하지 않습니다.')) );
		
		// 난수 발생
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float)$sec + ((float)$usec * 100000);
		srand($seed);
		$change_pwd = substr(md5($seed), 0, rand(4, 6));

		$this->M_mb_forget->new_pwd($change_pwd);

		// 메일 발송
		$err_msg = '';
		$super_admin = $this->M_basic->get_member(ADMIN, 'mb_email');
		$mail_addr = $mb['mb_email'];
		$subject = '['.$this->config->item('cf_title').'] 변경된 임시 비밀번호입니다.';
		$content = '<p>회원 요청으로 변경된 임시 비밀번호입니다.</br>임시 비밀번호로 로그인하신 후 사용하실 비밀번호로 변경해주세요.</p><p>회원 아이디: '.$mb['mb_id'].'<br/>임시 비밀번호: '.$change_pwd.'</p>';
		
		$this->email->clear();
		$this->email->from($super_admin['mb_email'], '메일검사');
		$this->email->to($mail_addr);
		$this->email->subject($subject);
		$this->email->message($content);
		if (!$this->email->send())
			die( json_encode(array('result' => '400', 'msg' => '메일 발송이 실패하였습니다. 잠시후 다시 시도해주세요.')) );
		
		die( json_encode(array('result' => '000', 'msg' => '변경된 비밀번호가 등록된 이메일('. $mail_addr .')로 발송되었습니다.')) );
	}
	
	function ajax_check_id() {
		$mb = $this->M_mb_forget->check();
		if(!isset($mb['mb_id']))		die( json_encode(array('result' => '100', 'msg' => '입력하신 정보의 회원을 찾을 수 없습니다.')) );
		else if($mb['mb_id'] == ADMIN)	die( json_encode(array('result' => '200', 'msg' => '관리자 아이디는 이용하실 수 없습니다.')) );
		else							die( json_encode(array('result' => '000')) );
	}
}
?>