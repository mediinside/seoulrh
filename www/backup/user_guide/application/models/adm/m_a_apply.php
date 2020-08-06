<?php
class M_a_apply extends CI_Model {
	var $table = 'ki_apply';
	
	function __construct() {
		parent::__construct();
	}
	
	function record($w, $sql) {
		if(!$sql)
			return false;
	
		if ($w == '') {
			$sql['ap_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('ap_id' => $this->input->post('ap_id')));
			return $this->input->post('ap_id');
		}
	}
	
	function list_update($ids, $data) {
		$primary = $this->db->primary($this->table);
		
		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['ap_mdydate']	= TIME_YMDHIS;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
	
		$this->db->update_batch($this->table, $batch, $primary);
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('ap_id', $ids);
		else
			$this->db->where('ap_id', $ids);
	
		return $this->db->delete($this->table);
	}
}
?>
