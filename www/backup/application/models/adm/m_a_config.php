<?php
class M_a_config extends CI_Model {
	var $table = 'ki_config';
	
	function __construct() {
		parent::__construct();
	}
	
	function setConfig($sql) {
		if(!$sql) {
			return FALSE;
		}
		
		if($this->db->count_all_results($this->table) > 0) {
			$this->db->update($this->table, $sql);
		}
		else {
			$this->db->insert($this->table, $sql);
		}
		
		return TRUE;
	}
}
?>
