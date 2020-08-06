<?php
class M_dbvars extends CI_Model {
	var $table = 'ki_dbvars';
	
	function __construct() {
		parent::__construct();
	}
	
	function get_data($type, $id, $fields='*') {
		return $this->get_vars($type, $id, '*', TRUE);
	}
	
	function get_data_cfg($type, $id, $fields='*') {
		return $this->get_vars($type, $id, $fields='*', FALSE);
	}
	
	function get_vars($type, $id, $fields='*', $useKeyName=FALSE) {
		if(!$type || !$id) return FALSE;
		
		$this->db->select($fields);
		$this->db->where(array('ref_type' => $type, 'ref_id' => $id));
		
		if($useKeyName) {
			$this->db->order_by('dbv_order', 'asc');
			$this->db->order_by('dbv_regdate', 'desc');
		}
		else {
			$this->db->order_by('dbv_regdate', 'asc');
		}
		
		$result = $this->db->get($this->table)->result_array();
		
		$dbVar = array();
		foreach($result AS $key => $con_data) {
			if(!isset($con_data[$con_data['dbv_var']])) {
				$keyName = $useKeyName ? 'dbv_var' : 'dbv_id';
				
				if(isset($dbVar[$con_data[$keyName]][0]) && is_array($dbVar[$row[$keyName]][0])) {
					$dbVar[$con_data[$keyName]] = array_merge($dbVar[$row[$keyName]], array($con_data));
				}
				else if(isset($dbVar[$con_data['dbv_var']])) {
					$dbVar[$con_data[$keyName]] = array($dbVar[$row[$keyName]], $con_data);
				}
				else {
					$dbVar[$con_data[$keyName]] = $con_data;
				}
			}
		}
		
		return $dbVar;
	}
	
	function ref_move($from_table, $from_ids, $to_table, $to_ids) {
		if(!is_array($from_ids)) {
			$from_ids = array($from_ids);
		}
		if(!is_array($to_ids)) {
			$to_ids = array($to_ids);
		}
		if(count($from_ids) != count($to_ids)) {
			return FALSE;
		}
		
		foreach($from_ids AS $key => $from_id) {
			$this->db->update($this->table, array('dbv_ref_table' => $to_table, 'dbv_ref_id' => $to_ids[$key]), array('dbv_ref_table' => $from_table, 'dbv_ref_id' => $from_id));
		}
	}
}
?>
