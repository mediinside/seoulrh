<?php
class M_mail extends CI_Model {
	var $table = 'ki_mail';
	var $table_skin = 'ki_mail_skin';
	
	function __construct() {
		parent::__construct();
	}
	
	function row($id, $fields='*') {
		if (!$id) return FALSE;
		
		$primary = $this->db->primary($this->table_skin);
		return $this->db->select($fields)->get_where($this->table, array($primary => $id))->row_array();
	}
	
	function row_skin($id='', $fields='*') {
		$primary = $this->db->primary($this->table_skin);
		
		if($id) {
			$this->db->where($primary, $id);
		}
		
		return $this->db->select($fields)->get($this->table_skin)->row_array();
	}
}
?>
