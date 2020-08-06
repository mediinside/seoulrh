<?php
class M_a_gallery extends CI_Model {
	var $table = 'ki_gallery';
	
	function __construct() {
		parent::__construct();
	}
	
	function record($w, $sql) {
		if(!$sql)
			return false;

		$primary = $this->db->primary($this->table);
		
		if ($w == '') {
			$sql['ga_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array($primary => $this->input->post($primary)));
			return $this->input->post($primary);
		}
	}
		
	function list_update($ids, $data) {
		$primary = $this->db->primary($this->table);
		
		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['ga_mdydate']	= TIME_YMDHIS;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
	
		$this->db->update_batch($this->table, $batch, $primary);
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('ga_id', $ids);
		else
			$this->db->where('ga_id', $ids);
		
		return $this->db->delete($this->table);
	}
}
?>
