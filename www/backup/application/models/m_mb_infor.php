<?php
class M_mb_infor extends CI_Model {
	var $table = "ki_member";
	var $table_conf = "ki_member_conf";
	
	function __construct() {
		parent::__construct();
	}
	
	function is_jumin_name() {
		$this->db->select('mb_name');
		$query = $this->db->get_where($this->table, array('mb_jumin' => md5(sha1($this->input->post('mb_jumin')))));
		return $query->row_array();
	}
	
	function getConfig($fields='*') {
		$row = $this->db->select($fields)->get($this->table_conf)->row_array();
		if(!$row) {
			$row = $this->db->get_columns($this->table_conf);
		}
		else {
			if(isset($row['mcf_login_param']))		$row['login_param'] = param_decode($row['mcf_login_param']);
			if(isset($row['mcf_idpw_param']))		$row['idpw_param'] = param_decode($row['mcf_idpw_param']);
			if(isset($row['mcf_join_param']))		$row['join_param'] = param_decode($row['mcf_join_param']);
			if(isset($row['mcf_confirm_param']))	$row['confirm_param'] = param_decode($row['mcf_confirm_param']);
			if(isset($row['mcf_password_param']))	$row['password_param'] = param_decode($row['mcf_password_param']);
			if(isset($row['mcf_modify_param']))		$row['modify_param'] = param_decode($row['mcf_modify_param']);
			if(isset($row['mcf_point_param']))		$row['point_param'] = param_decode($row['mcf_point_param']);
			if(isset($row['mcf_leave_param']))		$row['leave_param'] = param_decode($row['mcf_leave_param']);
		}
		
		return $row;
	}

	function insert($mb_nick) {
		$sql = array(
			'mb_id' => $this->input->post('mb_id'),
			'mb_password' => $this->encrypt->encode($this->input->post('mb_password')),
			'mb_name' => $this->input->post('mb_name'),
			'mb_jumin' => $this->input->post('mb_jumin'),
			'mb_sex' => $this->input->post('mb_sex'),
			'mb_birth' => $this->input->post('mb_birth'),
			'mb_nick' => $mb_nick,
			'mb_nick_date' => TIME_YMD,
			'mb_password_q' => $this->input->post('mb_password_q'),
			'mb_password_a' => $this->input->post('mb_password_a'),
			'mb_email' => $this->input->post('mb_email'),
			'mb_homepage' => $this->input->post('mb_homepage'),
			'mb_tel1' => $this->input->post('mb_tel1'),
			'mb_tel2' => $this->input->post('mb_tel2'),
			'mb_tel3' => $this->input->post('mb_tel3'),
			'mb_hp1' => $this->input->post('mb_hp1'),
			'mb_hp2' => $this->input->post('mb_hp2'),
			'mb_hp3' => $this->input->post('mb_hp3'),
			'mb_zip1' => $this->input->post('mb_zip1'),
			'mb_zip2' => $this->input->post('mb_zip2'),
			'mb_addr1' => $this->input->post('mb_addr1'),
			'mb_addr2' => $this->input->post('mb_addr2'),
			'mb_profile' => $this->input->post('mb_profile', TRUE),
			'mb_today_login' => TIME_YMDHIS,
			'mb_datetime' => TIME_YMDHIS,
			'mb_ip' => $this->input->server('REMOTE_ADDR'),
			'mb_level' => $this->config->item('cf_register_level'),
			'mb_login_ip' => $this->input->server('REMOTE_ADDR'),
			'mb_mailling' => $this->input->post('mb_mailling'),
			'mb_open' => $this->input->post('mb_open'),
			'mb_open_date' => TIME_YMD
		);

		// 이메일 인증을 사용하지 않는다면 이메일 인증시간을 바로 넣는다
		if (!$this->config->item('cf_use_email_certify'))
			$sql['mb_email_certify'] = TIME_YMDHIS;

		$this->db->insert($this->table, $sql);
	}

	function update() {
		$sql = array(
			'mb_password_q' => $this->input->post('mb_password_q'),
			'mb_password_a' => $this->input->post('mb_password_a'),
			'mb_mailling'   => $this->input->post('mb_mailling'),
			'mb_open'       => $this->input->post('mb_open'),
			'mb_email'      => $this->input->post('mb_email'),
			'mb_homepage'   => $this->input->post('mb_homepage'),
			'mb_tel1'       => $this->input->post('mb_tel1'),
			'mb_tel2'       => $this->input->post('mb_tel2'),
			'mb_tel3'       => $this->input->post('mb_tel3'),
			'mb_hp1'        => $this->input->post('mb_hp1'),
			'mb_hp2'        => $this->input->post('mb_hp2'),
			'mb_hp3'        => $this->input->post('mb_hp3'),
			'mb_zip1'       => $this->input->post('mb_zip1'),
			'mb_zip2'       => $this->input->post('mb_zip2'),
			'mb_addr1'      => $this->input->post('mb_addr1'),
			'mb_addr2'      => $this->input->post('mb_addr2'),
			'mb_profile'    => $this->input->post('mb_profile', TRUE)
		);
		if ($this->config->item('cf_use_nick') && $this->input->post('mb_nick'))
			$sql['mb_nick'] = $this->input->post('mb_nick');
		
		if ($this->input->post('mb_nick_default') != $this->input->post('mb_nick'))
			$sql['mb_nick_date'] = TIME_YMD;

		if ($this->input->post('mb_open_default') != $this->input->post('mb_open'))
			$sql['mb_open_date'] = TIME_YMD;

		// 이전 메일주소와 수정한 메일주소가 틀리다면 인증을 다시 해야하므로 값을 삭제
		if ($this->input->post('old_email') != $this->input->post('mb_email') && $this->config->item('cf_use_email_certify'))
			$sql['mb_email_certify'] = '';
            
        // 성별 & 생일
        if ($this->input->post('mb_sex'))   $sql['mb_sex']   = $this->input->post('mb_sex');
        if ($this->input->post('mb_birth')) $sql['mb_birth'] = $this->input->post('mb_birth');
   
		$this->db->where('mb_id', $this->input->post('mb_id'));
		$this->db->update($this->table, $sql);
	}

	function update_pwd() {
		$sql = array('mb_password' => $this->encrypt->encode($this->input->post('new_password')));
		$this->db->where('mb_id', $this->input->post('mb_id'));
		$this->db->update($this->table, $sql);
	}
	
	function leave() {
		$sql = array('mb_leave_date' => date('Ymd'));
		$this->db->where('mb_id', $this->input->post('mb_id'));
		$this->db->update($this->table, $sql);
	}
}
?>
