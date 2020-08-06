<?php
class M_a_dbvars extends CI_Model {
	var $table = 'ki_dbvars';
	
	function __construct() {
		parent::__construct();
	}

	function record($ref_type, $ref_id, $data) {
		if(!$ref_id || !$ref_type) {
			return FALSE;
		}
		
		$order = 1;
		$insert_id = array();
		
		if($data = array_sort($data, 'dbv_order')){
			foreach($data AS $key => $val) {
				if($val['dbv_var'] && $val['dbv_ref_id']) {
					$dbv_id = is_numeric($val['dbv_id']) ? $val['dbv_id'] : '';
					
					$sql = "INSERT INTO ".$this->table." (
							dbv_id,
							ref_type,
							ref_id,
							dbv_var,
							dbv_type,
							dbv_ref_table,
							dbv_ref_id,
							dbv_order,
							dbv_regdate  )
						VALUES ('".$dbv_id."', '".$ref_type."', '".$ref_id."', '".$val['dbv_var']."', '".$val['dbv_type']."', '".$val['dbv_ref_table']."', '".$val['dbv_ref_id']."', '".$order."', '".TIME_YMDHIS."')
						ON DUPLICATE KEY UPDATE
							dbv_var = '".$val['dbv_var']."',
							dbv_type = '".$val['dbv_type']."',
							dbv_ref_table = '".$val['dbv_ref_table']."',
							dbv_ref_id = '".$val['dbv_ref_id']."',
							dbv_order = '".$val['dbv_order']."'
					";
					
					$this->db->query($sql);
					$insert_id[] = $dbv_id ? $dbv_id : $this->db->insert_id();
				}
			}
		}
		
		$this->db->where('ref_type', $ref_type);
		$this->db->where('ref_id', $ref_id);
		if($insert_id) {
			$this->db->where_not_in('dbv_id', $insert_id);
		}
		$this->db->delete($this->table);
		
		return TRUE;
	}
}
?>
