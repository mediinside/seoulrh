<?php
class M_staff extends CI_Model {
	var $table = 'ki_staff';
	var $type = array(1 => 'MD', 2 => '강사');
	
	function __construct() {
		parent::__construct();
		$this->load->helper('textual');
	}

	function list_result($wr_field='*', $where='') {
		if($where) $this->db->where($where);
		
		$this->db->distinct();
		$this->db->select($wr_field);
		$this->db->order_by('st_id', 'desc');
		$qry = $this->db->get($this->table);
		$result = $qry->result_array();
		
		return $result;
	}
	
	function row($id, $fields='*') {
		if (!$id) return FALSE;
	
		return $this->db->select($fields)->get_where($this->table, array('st_id' => $id))->row_array();
	}
	
	function filepath() {
		return DATA_PATH.'/staff';
	}
}
?>
