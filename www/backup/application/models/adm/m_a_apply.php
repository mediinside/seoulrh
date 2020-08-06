<?php
class M_a_apply extends CI_Model {
	var $table = 'ki_apply_r_';
	var $table_cate = 'ki_apply_cate';
	var $table_form = 'ki_apply_form';
	
	function __construct() {
		parent::__construct();
	}

	function list_result($sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
					break;
			}
		}
		$this->db->stop_cache();
	
		$result['total_cnt'] = $this->db->count_all_results($this->table_cate);
	
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table_cate, $limit, $offset);
		$result['qry'] = $qry->result_array();
		
		$this->db->flush_cache();
	
		return $result;
	}
	
	function record($w, $cid, $id, $sql) {
		if(!$sql || !$cid) {
			return FALSE;
		}
		
		if ($w == '') {
			$sql['ap_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table . $cid, $sql);
			return $this->db->insert_id();
		}
		else {
			$primary = $this->db->primary($this->table . $cid);
			$this->db->update($this->table . $cid, $sql, array($primary => $id));
			return $id;
		}
	}
	
	function record_cate($w, $sql, $id='') {
		if(!$sql)
			return FALSE;
		
		if ($w == '') {
			$sql['apc_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table_cate, $sql);
		}
		else {
			$primary = $this->db->primary($this->table_cate);
			$this->db->update($this->table_cate, $sql, array($primary => $id));
		}
		
		return $id;
	}
	
	function record_form($cid, $sql) {
		if(!$cid || !$sql)
			return FALSE;
		
		$ids = array();
		if(isset($sql['apf_field']) && is_array($sql['apf_field'])) {
			foreach($sql['apf_field'] AS $key => $field) {
				$id = isset($sql['apf_id'][$key]) ? $sql['apf_id'][$key] : '';
				$name = $sql['apf_name'][$key];
				$type = $sql['apf_type'][$key];
				$options = isset($sql['apf_options'][$key]) ? preg_replace('/\|/','/',serialize(array_diff($sql['apf_options'][$key], array('')))) : '';
				$align_r = isset($sql['apf_align_r'][$key]) ? $sql['apf_align_r'][$key] : '';
				$required = isset($sql['apf_required'][$key]) ? $sql['apf_required'][$key] : '';
				$listing = isset($sql['apf_listing'][$key]) ? $sql['apf_listing'][$key] : '';
				$date = TIME_YMDHIS;
				
				if($field) {
					$qry = "INSERT INTO ". $this->table_form ." (
								apf_id,
								apf_cid,
								apf_field,
								apf_name,
								apf_type,
								apf_options,
								apf_align_r,
								apf_required,
								apf_listing,
								apf_mdydate,
								apf_regdate )
							VALUES ('$id', '$cid', '$field', '$name', '$type', '$options', '$align_r', '$required', '$listing', '$date', '$date')
							ON DUPLICATE KEY UPDATE
								apf_field = '$field',
								apf_name = '$name',
								apf_type = '$type',
								apf_options = '$options',
								apf_align_r = '$align_r',
								apf_required = '$required',
								apf_listing = '$listing',
								apf_mdydate = '$date'
					";
					
					$this->db->query($qry);
					$ids[] = $id ? $id : $this->db->insert_id();
				}
			}
		}
		
		$this->db->where('apf_cid', $cid);
		if($ids) {
			$this->db->where_not_in('apf_id', $ids);
		}
		$this->db->delete($this->table_form);
	}

	function list_update($ids, $data, $table) {
		$primary = $this->db->primary($table);
		
		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
	
		$this->db->update_batch($table, $batch, $primary);
	}
	
	function delete($ids, $table, $field='') {
		if(!$field) {
			$field = $this->db->primary($table);
		}
		
		if(is_array($ids))
			$this->db->where_in($field, $ids);
		else
			$this->db->where($field, $ids);
	
		return $this->db->delete($table);
	}
}
?>
