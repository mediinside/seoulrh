<?php
class Join extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->config->load('cf_register');
		$this->load->library('form_validation');
		$this->load->model(array('M_mb_infor', 'M_register'));
		$this->load->config('cf_register');
	}

	function index() {
		if (IS_MEMBER)
			alert('이미 로그인 중입니다.');

		$config = array(
			array('field'=>'agree', 'label'=>'회원가입약관', 'rules'=>'required'),
			array('field'=>'agree2', 'label'=>'개인정보취급방침', 'rules'=>'required')
		);
		if ($this->config->item('cf_use_jumin')) {
			$config[] = array('field'=>'mb_name', 'label'=>'이름', 'rules'=>'trim|required|min_length[2]|max_length[10]');
			$config[] = array('field'=>'mb_jumin', 'label'=>'주민등록번호', 'rules'=>'trim|required|exact_length[13]|is_natural_no_zero');
		}
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE) {
			$this->load->helper('file');

			$vars = array_merge( array(
				'_TITLE_'		=> '회원가입약관',
				'_BODY_'		=> 'member/join_check',
				'_CSS_'			=> array('member','jquery-ui'),
				
				'privacy'		=> read_file(SKIN_PATH.'/member/privacy.txt'),
				'stipulation'	=> read_file(SKIN_PATH.'/member/stipulation.txt'),
				'use_jumin'		=> $this->config->item('cf_use_jumin')
			), $this->config->item('cf_page_join') );
			
			$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
		}
		else
			$this->_form();
	}
	
	function _form() {
		$token = get_token();

		// 주민등록번호를 사용한다면 중복검사를 합니다.
		$jumin = $this->input->post('mb_jumin');
		if ($this->config->item('cf_use_jumin')) {
			$row = $this->M_mb_infor->is_jumin_name();
			if ($row) {
				if ($row['mb_name'] == $this->input->post('mb_name'))
					alert('이미 가입되어 있습니다.');
				else
					alert("다른 이름으로 같은 주민등록번호가 이미 가입되어 있습니다.\\n\\n관리자에게 문의해 주십시오.");
			}

			// 주민등록번호의 7번째 한자리 숫자
			$y = substr($jumin, 6, 1);

			// 성별은 F, M 으로 나눈다.
			// 주민등록번호의 7번째 자리가 홀수이면 남자(Male), 짝수이면 여자(Female)
			$sex = $y % 2 == 0 ? 'F' : 'M';

			// 생일은 8자리로 만든다 (검색의 용이)
			// 주민등록번호 앞자리를 생일로 활용
			// 주민등록번호 7번째 자리 비교
			$birth = substr($jumin, 0, 6);

			if ($y == 9 || $y == 0) // 1800년생
				$birth = '18' . $birth;
			else if ($y == 1 || $y == 2) // 1900년생
				$birth = '19' . $birth;
			else if ($y == 3 || $y == 4) // 2000년생
				$birth = '20' . $birth;
			else // 오류
				$birth = 'xx' . $birth;

			$readonly_name = "readonly='readonly' style='background-color:#dddddd;'";
		}
		else
			$readonly_name = $birth = $sex = FALSE;

		$vars = array_merge( array(
			'_TITLE_'		=> '회원 가입',
			'_BODY_'		=> 'member/join',
			'_CSS_'			=> array('member','jquery-ui'),
			
			'mb_id'			=> '',
			'mb_password_q'	=> '',
			'mb_password_a'	=> '',
			'mb_nick'		=> '',
			'mb_email'		=> '',
			'mb_mailling'	=> '',
			'mb_tel1'		=> '',
			'mb_tel2'		=> '',
			'mb_tel3'		=> '',
			'mb_hp1'		=> '',
			'mb_hp2'		=> '',
			'mb_hp3'		=> '',
			'mb_zip1'		=> '',
			'mb_zip2'		=> '',
			'mb_addr1'		=> '',
			'mb_addr2'		=> '',
			'mb_homepage'	=> '',
			'mb_open'		=> '',
			'mb_profile'	=> '',
			'nick_modify'	=> true,
			'open_modify'	=> true,
			'cf_icon_size'	=> 0,
			'cf_named_size'	=> 0,
			
			'mb_name'		=> $this->input->post('mb_name'),
			'mb_sex'		=> ($sex) ? $sex : FALSE,
			'mb_birth'		=> ($birth) ? preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $birth) : FALSE,
			'jumin'			=> (strlen($jumin) < 14) ? md5(sha1($jumin)) : $jumin,
			'readonly_name'	=> $readonly_name,

			'cf_use_nick'	=> $this->config->item('cf_use_nick'),
			'cf_nick_modify'=> $this->config->item('cf_nick_modify'),
			'cf_open_modify'=> $this->config->item('cf_open_modify'),

			'img_path' => IMG_DIR.'/member',
			'token'    => $token,
			'todays'   => date("Ymd", time())
		), $this->config->item('cf_page_join') );
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
	
	function update() {
		check_token('member/join');
		check_wrkey();

		$this->load->helper('chkstr');
		$config = array(
			array('field'=>'mb_id', 'label'=>'아이디', 'rules'=>'trim|required|min_length[4]|max_length[20]|alpha_dash|xss_clean|callback_mb_id_check'),
			array('field'=>'mb_password', 'label'=>'비밀번호', 'rules'=>'trim|required|max_length[20]|md5'),
			array('field'=>'mb_password_re', 'label'=>'비밀번호 확인', 'rules'=>'trim|required|max_length[20]|matches[mb_password]|md5'),
			array('field'=>'mb_password_q', 'label'=>'비밀번호 분실시 질문', 'rules'=>'trim|required|max_length[50]'),
			array('field'=>'mb_password_a', 'label'=>'비밀번호 분실시 답변', 'rules'=>'trim|required|max_length[50]'),
			array('field'=>'mb_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[10]|callback_mb_name_check'),
			array('field'=>'mb_email', 'label'=>'이메일', 'rules'=>'trim|required|max_length[50]|valid_email|callback_mb_email_check'),
			array('field'=>'mb_birth', 'label'=>'생일', 'rules'=>'trim|exact_length[10]'),
			array('field'=>'mb_sex', 'label'=>'성별', 'rules'=>'trim|exact_length[1]'),
			array('field'=>'wr_key', 'label'=>'자동등록방지', 'rules'=>'trim|required')
		);
		if ($this->config->item('cf_use_nick'))
			$config[] = array('field'=>'mb_nick', 'label'=>'별명', 'rules'=>'trim|required|max_length[20]|callback_mb_nick_check');

		if ($this->config->item('cf_use_jumin'))
			$config[] = array('field'=>'mb_jumin', 'label'=>'주민등록번호', 'rules'=>'trim|required|exact_length[32]');

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$this->_form();
		}
		else {
			$this->load->library(array('encrypt', 'email'));

			if ($this->config->item('cf_use_nick'))
				$mb_nick = $this->input->post('mb_nick');
			else
				$mb_nick = substr(md5(uniqid($this->input->post('mb_id'), TRUE)), 0, 14);

			$admin = $this->M_basic->get_member(ADMIN, 'mb_nick, mb_email');

			// 회원 INSERT
			$this->M_mb_infor->insert($mb_nick);

			// 회원가입 포인트 부여
			$this->load->model('M_point');
			$this->M_point->insert($this->input->post('mb_id'), $this->config->item('cf_register_point'), "회원가입 축하", '@member', $this->input->post('mb_id'), '회원가입');

			// 회원님께 메일 발송
			if ($this->config->item('cf_email_mb_member') || $this->config->item('cf_use_email_certify')) {
				$mb_md5 = md5($this->input->post('mb_id').$this->input->post('mb_email').TIME_YMDHIS);
				$certify_href = $this->config->item('base_url').'/member/certify/email/'.$this->input->post('mb_id').'/'.$mb_md5;

				$data = array(
					'mb_name' => $this->input->post('mb_name'),
					'certify_href' => $certify_href,
					'email_chk' => $this->config->item('cf_use_email_certify')
				);
				$content = $this->load->view('mail/join_member', $data, TRUE);

				$this->email->clear();
				$this->email->from($admin['mb_email'], $admin['mb_nick']);
				$this->email->to($this->input->post('mb_email'));
				$this->email->subject("회원가입을 축하드립니다.");
				$this->email->message($content);
				$this->email->send();
			}

			// 최고관리자님께 메일 발송
			if ($this->config->item('cf_email_mb_admin')) {
				$data = array(
					'mb_id' => $this->input->post('mb_id'),
					'mb_name' => $this->input->post('mb_name'),
					'mb_nick' => $mb_nick
				);
				$content = $this->load->view('mail/join_admin', $data, TRUE);

				$this->email->clear();
				$this->email->from($this->input->post('mb_email'), $this->input->post('mb_name'));
				$this->email->to($admin['mb_email']);
				$this->email->subject($this->input->post('mb_name')." 님께서 회원으로 가입하셨습니다.");
				$this->email->message($content);
				$this->email->send();
			}

			// 메일인증 사용하지 않는 경우에만 로그인
			if (!$this->config->item('cf_use_email_certify'))
				$this->session->set_userdata('ss_mb_id', $this->input->post('mb_id'));

			$this->session->set_flashdata('ss_mb_reg', $this->input->post('mb_id'));

			goto_url('member/join/result');
		}
	}

	function result() {
		if (!$this->session->flashdata('ss_mb_reg'))
			goto_url('/');

		$mb = $this->M_basic->get_member($this->session->flashdata('ss_mb_reg'), 'mb_id, mb_name, mb_email');
		// 회원정보가 없다면 초기 페이지로 이동
		if (!$mb)
			goto_url('/');

		$vars = array_merge( array(
			'_TITLE_'		=> '회원가입 결과',
			'_BODY_'		=> 'member/join_result',
			'_CSS_'			=> array('member','jquery-ui'),
			
			'img_path'		=> IMG_DIR.'/member',
			'mb_id'			=> $mb['mb_id'],
			'mb_name'		=> $mb['mb_name'],
			'mb_email'		=> $mb['mb_email'],
			'email_chk'		=> $this->config->item('cf_use_email_certify')
		), $this->config->item('cf_page_join') );
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}

	function mb_id_check($str) {
		if (preg_match("/[\,]?{$str}/i", $this->config->item('cf_prohibit_id'))) {
			$this->form_validation->set_message('mb_id_check', $str.' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다.');
			return FALSE;
		}

		$row = $this->M_register->is('mb_id', $str);
		if ($row != 0) {
			$this->form_validation->set_message('mb_id_check', $str.' 은(는) 이미 다른분이 사용중인 회원아이디이므로 사용이 불가합니다.');
			return FALSE;
		}
		return TRUE;
	}

	function mb_name_check($str) {
		if (!check_string($str, _RT_HANGUL_)) {
			$this->form_validation->set_message('mb_name_check', '이름은 공백없이 한글만 입력 가능합니다.');
			return FALSE;
		}
		return TRUE;
	}

	function mb_nick_check($str) {
		if (!check_string($str, _RT_HANGUL_ + _RT_ALPHABETIC_ + _RT_NUMERIC_)) {
			$this->form_validation->set_message('mb_nick_check', '별명은 공백없이 한글, 영문, 숫자만 입력 가능합니다.');
			return FALSE;
		}

		if (preg_match("/[\,]?{$str}/i", $this->config->item('cf_prohibit_id'))) {
			$this->form_validation->set_message('mb_nick_check', $str.' 은(는) 예약어로 사용하실 수 없는 별명입니다.');
			return FALSE;
		}

		$row = $this->M_register->is('mb_nick', $str);
		if ($row != 0) {
			$this->form_validation->set_message('mb_nick_check', $str.' 은(는) 이미 다른분이 사용중인 별명이므로 사용이 불가합니다.');
			return FALSE;
		}
		return TRUE;
	}

	function mb_email_check($str) {
		$row = $this->M_register->is('mb_email', $str);
		if ($row != 0) {
			$this->form_validation->set_message('mb_email_check', $str.' 은(는) 이미 다른분이 사용중인 E-mail이므로 사용이 불가합니다.');
			return FALSE;
		}
		return TRUE;
	}
}
?>
