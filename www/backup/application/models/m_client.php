<?php
class M_client extends CI_Model {
	var $table = 'ki_clients';
	var $product = array(
		'개인단 9단',
		'개인단 8단',
		'개인단 7단',
		'개인단 6단',
		'개인단 5단',
		'개인단 4단',
		'개인단 3단',
		'개인단 2단',
		'개인단 1단',
		'부부단 9단',
		'부부단 8단',
		'부부단 7단',
		'부부단 6단',
		'부부단 5단',
		'부부단 4단',
		'부부단 3단',
		'부부단 2단',
		'부부단 1단'
	);
	
	function __construct() {
		parent::__construct();
	}

	function list_result($sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
	
		if ($stx) {
			$this->db->start_cache();
			$this->db->like($sfl, $stx, 'center');
			$this->db->stop_cache();
		}
	
		$this->db->stop_cache();
	
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
	
		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();
		$result['total_cnt'] = $this->db->count_all_results($this->table);
	
		$this->db->flush_cache();
	
		return $result;
	}

	function get_list($where='', $sst='cl_regdate', $sod='desc') {
		if($where) {
			$this->db->where($where);
		}
		
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		
		$qry = $this->db->get($this->table);
		$result['qry'] = $qry->result_array();
		$result['total_cnt'] = $this->db->count_all_results($this->table);
	
		$this->db->flush_cache();
	
		return $result;
	}
	
	function row($id, $fields='*', $where='') {
		if (!$id) return FALSE;
	
		if($where) {
			$this->db->where($where);
		}
		
		$this->db->select($fields);
		$this->db->where('cl_id', $id);
		
		return $this->db->get($this->table)->row_array();
	}
}
?>
