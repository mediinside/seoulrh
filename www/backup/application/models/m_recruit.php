<?php
class M_recruit extends CI_Model {
	var $table = 'ki_recruit';
	var $table_reg = 'ki_recruit_reg';
	var $cate = array(1 => '분류1');
	
	function __construct() {
		parent::__construct();
		$this->load->helper('textual');
	}

	function latest($limit=5) {
		$this->db->select('*');
		$this->db->group_by('recr_id');
		$this->db->order_by('recr_id', 'desc');
		$this->db->where('recr_sdatetime <= now()');
		$this->db->where('recr_edatetime >= now()');
		$this->db->where('recr_soldout', '0');
		$this->db->where('recr_hidden', '0');
		$qry = $this->db->get($this->table, $limit);
		
		return $qry->result_array();
	}
	
	function row($id, $fields='*') {
		if (!$id) return FALSE;
	
		$row = $this->db->get_row($id, $this->table, $fields);
		
		return $row;
	}
	
	function list_result($cate, $sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		
		if ($stx) {
			$sfl = explode('|', $sfl);
			$like = array();
			foreach($sfl AS $s) {
				$like[] = $s ." LIKE '%$stx%'"; 
			}
			if($like) {
				$this->db->where('('. implode(' OR ', $like) .')');
			}
		}
		
		if($cate) {
			$this->db->where('recr_cate', $cate);
		}
		
		$this->db->where('recr_hidden', '0');
		
		$this->db->stop_cache();		
		
		$this->db->select('*');
		$this->db->group_by('recr_id');
		$this->db->order_by('recr_id', 'desc');
		$qry = $this->db->get($this->table, $limit, $offset);
		$result = $qry->result_array();
		
		$result['qry'] = $result;
		$result['total_cnt'] = $this->db->count_all_results($this->table);
		
		$this->db->flush_cache();
		
		return $result;
	}
	
	function record($sql) {
		if(!$sql)
			return false;
		
		$sql['rreg_regdate'] = TIME_YMDHIS;
		$this->db->insert($this->table_reg, $sql);
		return $this->db->insert_id();
	}
}
?>
