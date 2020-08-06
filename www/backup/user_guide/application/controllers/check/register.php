<?php
class Register extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('M_register');
		$this->config->load('cf_register');
	}

	function id() {
		$reg_mb_id = $this->input->post('reg_mb_id');
		$TRUE = $FALSE = FALSE;

		if (preg_match("/[^0-9a-z_]+/i", $reg_mb_id))
			$FALSE = '영문자, 숫자, _ 만 입력하세요.';
		else if (strlen($reg_mb_id) < 3)
			$FALSE = '최소 3자이상 입력하세요.';
		else {
			$row = $this->M_register->is('mb_id', $reg_mb_id);
			if ($row != 0)
				$FALSE = '이미 사용중인 아이디 입니다.';
			else {
				if (preg_match("/[\,]?".$reg_mb_id."/i", $this->config->item('cf_prohibit_id')))
					$FALSE = '예약어로 사용할 수 없는 아이디 입니다.';
				else
					$TRUE = '사용하셔도 좋은 아이디 입니다.';
			}
		}

		if ($TRUE)
			echo "<font color='blue'>".$TRUE."</font>";
		else if ($FALSE)
			echo "<font color='red'>".$FALSE."</font>";
	}

	function nick() {
		$mb_id = $this->input->post('mb_id');
		$reg_mb_nick = $this->input->post('reg_mb_nick');
		$TRUE = $FALSE = FALSE;
		$member = (SU_ADMIN && $mb_id) ? $this->M_basic->get_member($mb_id,'mb_nick') : unserialize(MEMBER);
		$member_nick = isset($member['mb_nick']) ? $member['mb_nick'] : '';

		if(strtolower(preg_replace('/-/','',ENCODING)) == 'euckr')
			$reg_mb_nick = mb_convert_encoding($reg_mb_nick, 'CP949', 'UTF-8');	//EUC-KR
		else
			$reg_mb_nick = $reg_mb_nick;	// UTF-8

		// 별명은 한글, 영문, 숫자만 가능
		$this->load->helper('chkstr');
		if (!check_string($reg_mb_nick, _RT_HANGUL_ + _RT_ALPHABETIC_ + _RT_NUMERIC_))
			$FALSE = '별명은 공백없이 한글, 영문, 숫자만 입력 가능합니다.';
		else if (strlen($reg_mb_nick) < 4)
			$FALSE = '한글 2글자, 영문 4글자 이상 입력 가능합니다.';
		else {
			$row = $this->M_register->is('mb_nick', $reg_mb_nick);
			if ($row != 0 && $reg_mb_nick != $member_nick)
				$FALSE = '이미 존재하는 별명입니다.';
			else {
				if (preg_match("/[\,]?".$reg_mb_nick."/i", $this->config->item('cf_prohibit_id')))
					$FALSE = '예약어로 사용할 수 없는 별명 입니다.';
				else
					$TRUE = '사용하셔도 좋은 별명 입니다.';
			}
		}
		
		if ($TRUE)
			echo "<font color='blue'>".$TRUE."</font>";
		else if ($FALSE)
			echo "<font color='red'>".$FALSE."</font>";
	}

	function email() {
		$mb_id = $this->input->post('mb_id');
		$reg_mb_email = $this->input->post('reg_mb_email');
		$TRUE = $FALSE = $member_email = FALSE;
		$member = (SU_ADMIN && $mb_id) ? $this->M_basic->get_member($mb_id,'mb_email') : unserialize(MEMBER);
		$member_email = isset($member['mb_email']) ? $member['mb_email'] : '';
		
		if (trim($reg_mb_email) == '')
			$FALSE = '이메일 주소를 입력하십시오.';
		else if (!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $reg_mb_email))
			$FALSE = '이메일 주소가 형식에 맞지 않습니다.';
		else {
			$row = $this->M_register->is('mb_email', $reg_mb_email);
			if ($row != 0 && $reg_mb_email != $member_email)
				$FALSE = '이미 존재하는 이메일 주소입니다.';
			else
				$TRUE = '사용하셔도 좋은 이메일 주소입니다.';
		}

		if ($TRUE)
			echo "<font color='blue'>".$TRUE."</font>";
		else if ($FALSE)
			echo "<font color='red'>".$FALSE."</font>";
	}
}
?>
