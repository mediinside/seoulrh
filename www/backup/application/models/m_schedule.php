<?php
class M_schedule extends CI_Model {
	var $table = 'ki_schedule';
	var $fld_arr = array('뇌성마비', '재생의학', '근경직', '스포츠재활', '뇌졸증/뇌손상', '지폐장애' ,'발달지연', '척수손상', '보조기' ,'통증' ,'소아섬식장애');
	
	function __construct() {
		parent::__construct();
	}

	function list_result($sst, $sod, $sfl, $stx, $limit, $offset, $group='') {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
				break;
			}
		}
		if($group) {
			$this->db->where('sc_group', $group);
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
	
	function row($id, $fields='*', $table='') {
		if (!$id) return FALSE;
		
		if(!$table) {
			$table = $this->table;
		}
	
		$primary = $this->db->primary($table);
		
		return $this->db->select($fields)->get_where($table, array($primary => $id))->row_array();
	}

	function filepath() {
		return DATA_PATH.'/schedule';
	}
}
?>
