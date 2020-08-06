<?php
class M_a_client extends CI_Model {
	var $table = 'ki_clients';
	
	function __construct() {
		parent::__construct();
	}

	function record($w, $sql) {
		if(!$sql) {
			return FALSE;
		}

		$primary = $this->db->primary($this->table);
		
		if ($w == '') {
			$sql['cl_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array($primary => $this->input->post($primary)));
			return $this->input->post($primary);
		}
	}
	
	function delete($ids) {
		$primary = $this->db->primary($this->table);
		
		if(is_array($ids))
			$this->db->where_in($primary, $ids);
		else
			$this->db->where($primary, $ids);
		
		return $this->db->delete($this->table);
	}
}
?>
