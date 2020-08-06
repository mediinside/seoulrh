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
				if($val['dbv_var'] && ($val['dbv_ref_table'] || $val['dbv_ref_id'])) {
					$dbv_id = is_numeric($val['dbv_id']) ? $val['dbv_id'] : '';
					
					if(isset($val['dbv_ext'])) {
						$dbv_ext = is_array($val['dbv_ext']) ? json_encode($val['dbv_ext']) : $val['dbv_ext'];
					}
					else {
						$dbv_ext = '';
					}
					
					$sql = "INSERT INTO ".$this->table." (
							dbv_id,
							ref_type,
							ref_id,
							dbv_var,
							dbv_type,
							dbv_ref_table,
							dbv_ref_id,
							dbv_ext,
							dbv_order,
							dbv_regdate  )
						VALUES ('".$dbv_id."', '".$ref_type."', '".$ref_id."', '".$val['dbv_var']."', '".$val['dbv_type']."', '".$val['dbv_ref_table']."', '".$val['dbv_ref_id']."', '".$dbv_ext."', '".$order."', '".TIME_YMDHIS."')
						ON DUPLICATE KEY UPDATE
							dbv_var = '".$val['dbv_var']."',
							dbv_type = '".$val['dbv_type']."',
							dbv_ref_table = '".$val['dbv_ref_table']."',
							dbv_ref_id = '".$val['dbv_ref_id']."',
							dbv_ext = '". $dbv_ext ."',
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

	function delete($ref_type, $ids) {
		if(!$ref_type) {
			return FALSE;
		}

		$this->db->where('ref_type', $ref_type);
		
		if(is_array($ids))
			$this->db->where_in('ref_id', $ids);
		else
			$this->db->where('ref_id', $ids);
	
		return $this->db->delete($this->table);
	}
	
	function copy($new_id, $where) {
		if(!$new_id || !$where) {
			return FALSE;
		}
		
		$list = $this->db->get_where($this->table, $where)->result_array();
		if($list) {
			foreach($list AS $key => $row) {
				unset($list[$key]['dbv_id']);
				$list[$key]['ref_id'] = $new_id;
				$list[$key]['dbv_regdate'] = TIME_YMDHIS;
			}
			
			$this->db->insert_batch($this->table, $list);
		}
	}
}
?>
