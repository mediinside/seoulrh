<?php
class M_mb_forget extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function check() {
		if (!$this->input->post('w'))
			return FALSE;
			
		$this->db->select('mb_id, mb_level, mb_password_q');
		if (!$this->session->flashdata('mb_idpwd')) {
			if ($this->config->item('cf_use_jumin'))
				$where['mb_jumin'] = md5(sha1($this->input->post('mb_jumin')));
			else
				$where['mb_email'] = $this->input->post('mb_email');
			
			$where['mb_name'] = $this->input->post('mb_name');
			if (!$this->input->post('not_mb_id'))
				$where['mb_id'] = $this->input->post('mb_id');
		}
		else
			$where['mb_id'] = $this->session->flashdata('mb_idpwd');

		$query = $this->db->get_where('ki_member', $where);
		return $query->row_array();
	}
	
	function new_pwd($pwd) {
		$this->load->library('encrypt');
		$this->db->where('mb_id', $this->input->post('mb_id'));
		$this->db->update('ki_member', array('mb_password' => $this->encrypt->encode(md5($pwd))));
	}
}
?>
