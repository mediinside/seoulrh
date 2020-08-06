<?php
class M_config extends CI_Model {
	var $table = 'ki_config';
	
	function __construct() {
		parent::__construct();
	}
	
	function getConfig($fields='*') {
		$row = $this->db->select($fields)->get($this->table)->row_array();
		if(!$row) {
			$row = $this->db->get_columns($this->table);
		}

		foreach($row AS $key => $val) {
			$this->config->set_item($key, $val);
		}
		
		return $row;
	}
}
?>
