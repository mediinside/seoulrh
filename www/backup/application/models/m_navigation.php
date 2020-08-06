<?php
class M_navigation extends CI_Model {
	var $table = 'ki_navigation';
	
	function __construct() {
		parent::__construct();
	}

	function list_result($sst, $sod, $sfl, $stx, $limit, $offset, $where='') {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
				break;
			}
		}
		if($where) {
			$this->db->where($where);
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

	function row($ga_id, $fields='*') {
		if (!$ga_id) return FALSE;
		
		return $this->db->select($fields)->get_where($this->table, array('ga_id' => $ga_id))->row_array();
	}
}
?>
