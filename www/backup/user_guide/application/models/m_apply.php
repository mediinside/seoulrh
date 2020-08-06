<?php
class M_apply extends CI_Model {
	var $table = 'ki_apply';
	var $ap_status = array(1 => '신청대기', 5 => '처리중', 9 => '처리완료', 0 => '신청취소');
	
	function __construct() {
		parent::__construct();
		
		$this->load->helper('textual');
	}

	function row($id, $fields='*') {
		if (!$id) return FALSE;
		
		return $this->db->select($fields)->get_where($this->table, array('ap_id' => $id))->row_array();
	}

	function list_result($sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
					break;
			}
		}
		$this->db->stop_cache();
	
		$result['total_cnt'] = $this->db->count_all_results($this->table);
	
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();
	
		$this->db->flush_cache();
	
		return $result;
	}
	
	function record($w='') {
		$sql = array(
			'ap_mb_id' => $this->input->post('ap_mb_id'),
			'ap_name' => $this->input->post('ap_name'),
			'ap_phone' => $this->input->post('ap_phone'),
			'ap_email' => $this->input->post('ap_email'),
			'ap_write' => $this->input->post('ap_write'),
			'ap_status' => $this->input->post('ap_status'),
			'ap_mdydate' => TIME_YMDHIS
		);
	
		if ($w == '') {
			$sql['ap_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('ap_id' => $this->input->post('ap_id')));
			return $this->input->post('ap_id');
		}
	}
}
?>
