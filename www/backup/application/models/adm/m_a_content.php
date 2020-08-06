<?php
class M_a_content extends CI_Model {
	var $table = 'ki_content';
	
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

		$result['total_cnt'] = $this->db->count_all_results($this->table);

		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();

		$this->db->flush_cache();

		return $result;
	}
	
	function record($w, $sql) {
		if(!$sql)
			return false;
		
		if ($w == '') {
			$sql['ct_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('ct_id' => $this->input->post('ct_id')));
			return $this->input->post('ct_id');
		}
	}
	/*
	function chk_field($id, $fld, $name) {
		if (!$fld || !$name) return FALSE;
	
		$is_name = $this->db->where(array('ct_id <>' => $id, $fld => $name))->count_all_results($this->table);
	
		return $is_name;
	}
	*/
	function list_update($ids, $data) {
		$primary = $this->db->primary($this->table);

		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['ct_mdydate']	= TIME_YMDHIS;
			foreach ($data as $fld => $val) {
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
			}
		}
		
		$this->db->update_batch($this->table, $batch, $primary);
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('ct_id', $ids);
		else
			$this->db->where('ct_id', $ids);
		
		return $this->db->delete($this->table);
	}
}
?>
