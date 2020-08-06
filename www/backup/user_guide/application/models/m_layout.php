<?php
class M_layout extends CI_Model {
	var $table = 'ki_layout';
	
	function __construct() {
		parent::__construct();
	}
	
	function get_layout($file, $fields='*') {
		if (!$file) return FALSE;
	
		$layout = $this->db->select($fields)->get_where($this->table, array('ly_file' => $file))->row_array();

		return $layout;
	}
	
	function row($id, $fields='*') {
		if (!$id) return FALSE;
		
		$primary = $this->db->primary($this->table);
		return $this->db->select($fields)->get_where($this->table, array($primary => $id))->row_array();
	}
}
?>
