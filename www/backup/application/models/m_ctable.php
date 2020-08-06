<?php
class M_ctable extends CI_Model {
	var $table = 'ki_ctable';
	var $table_cate = 'ki_ctable_cate';
	
	function __construct() {
		parent::__construct();
	}

	function list_result_cate() {
		$this->db->select('*');
		$this->db->order_by('ctg_id', 'asc');
		$qry = $this->db->get($this->table_cate);
	
		$result = $qry->result_array();
	
		$list = array();
		foreach($result AS $key => $row) {
			$list[$row['ctg_id']] = $row['ctg_subject'];
		}
	
		return $list;
	}
	
	function get_tables() {
		$this->db->select('*');
		$this->db->join($this->table_cate .' b', 'a.cta_cate = b.ctg_id');
		$this->db->order_by('a.cta_cate', 'asc');
		$this->db->order_by('a.cta_id', 'asc');
		$result = $this->db->get($this->table .' a')->result_array();
		
		$list = array();
		foreach($result AS $key => $val) {
			$list[$val['cta_cate']][] = $val;
		}
		
		return $list;
	}
}
?>
