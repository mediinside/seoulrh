<?php
class M_apply extends CI_Model {
	var $table = 'ki_apply_r_';
	var $table_cate = 'ki_apply_cate';
	var $table_form = 'ki_apply_form';
	var $ap_status = array(1 => '신청대기', 5 => '처리중', 9 => '처리완료', 0 => '신청취소');
	
	function __construct() {
		parent::__construct();
		
		$this->load->helper('textual');
	}

	function row($cid, $id, $fields='*') {
		if (!$cid || !$id) {
			return FALSE;
		}
		
		$row = $this->db->select($fields)->get_where($this->table. $cid, array('ap_id' => $id))->row_array();
		/*
		 foreach($row AS $key => $val) {
		$unserialize = @unserialize($val);
		if(is_array($unserialize)) {
		$row[$key] = $unserialize;
		}
		}
		*/
		return $row;
	}

	// 신청서 양식
	function row_form($cid, $fields='*') {
		if (!$cid) {
			return FALSE;
		}
		
		$this->db->select($fields);
		$this->db->where('apc_id', $cid);
		$row = $this->db->get($this->table_cate)->row_array();

		if(isset($row['apc_id']) && $row['apc_id']) {
			$this->db->order_by('apf_id', 'asc');
			$row['form'] = $this->db->get_where($this->table_form, array('apf_cid' => $row['apc_id']))->result_array();
			foreach($row['form'] AS $key => $form) {
				$row['form'][$key]['options'] = $form['apf_options'] ? unserialize($form['apf_options']) : array();
			}
		}
		
		return $row;
	}
	
	function list_result($sst, $sod, $sfl, $stx, $limit, $offset, $cid) {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
					break;
			}
		}
		$this->db->stop_cache();
	
		$result['total_cnt'] = $this->db->count_all_results($this->table . $cid);
	
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table . $cid, $limit, $offset);
		$result['qry'] = $qry->result_array();
	
		$this->db->flush_cache();
	
		return $result;
	}
	
	function record($cid, $sql='') {
		if(!$sql) {
			return FALSE;
		}
		
		$sql['ap_regdate'] = TIME_YMDHIS;
		$this->db->insert($this->table . $cid, $sql);
		
		return $this->db->insert_id();
	}
}
?>
