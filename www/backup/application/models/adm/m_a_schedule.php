<?php
class M_a_schedule extends CI_Model {
	var $table = 'ki_schedule';
	
	function __construct() {
		parent::__construct();
	}

	function record($w, $sql, $table='') {
		if(!$sql)
			return false;
		
		if(!$table) {
			$table = $this->table;
		}
		
		$primary = $this->db->primary($table);
		
		$id = $this->input->post($primary);
		
		if ($w == '') {
			$sql['sc_regdate'] = TIME_YMDHIS;
			$this->db->insert($table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($table, $sql, array($primary => $id));
			return $id;
		}
	}
	
	function list_update($ids, $data, $table='') {
		if(!$table) {
			$table = $this->table;
		}
		
		$mdydate_colName = $table == $this->table ? 'sc_mdydate' : 'bg_mdydate';
		
		$primary = $this->db->primary($table);
		
		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key][$mdydate_colName]	= TIME_YMDHIS;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
	
		$this->db->update_batch($table, $batch, $primary);
	}

	function update($id, $sql) {
		if(!$sql)
			return false;
		
		$this->db->update($this->table, $sql, array('sc_id' => $id));
		
		return $id;
	}
	
	function delete($ids, $table='') {
		if(!$table) {
			$table = $this->table;
		}
		
		$primary = $this->db->primary($table);
		
		if(is_array($ids))
			$this->db->where_in($primary, $ids);
		else
			$this->db->where($primary, $ids);
		
		return $this->db->delete($table);
	}
}
?>
