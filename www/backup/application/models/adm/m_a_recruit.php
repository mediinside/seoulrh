<?php
class M_a_recruit extends CI_Model {
	var $table = 'ki_recruit';
	var $table_opt = 'ki_recruit_opt';
	var $table_reg = 'ki_recruit_reg';
	
	function __construct() {
		parent::__construct();
	}
	
	function list_result($cate, $sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		
		if ($stx) {
			$this->db->start_cache();
			$this->db->like($sfl, $stx, 'center');
		}
		
		$this->db->where('recr_cate', $cate);
		
		$this->db->stop_cache();
		
		$this->db->select('*');
		$this->db->order_by($sst, $sod);

		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();
		$result['total_cnt'] = $this->db->count_all_results($this->table);
		
		$this->db->flush_cache();

		return $result;
	}
		
	function reg_list_result($id, $sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		
		if ($stx) {
			$this->db->start_cache();
			$this->db->like($sfl, $stx, 'center');
		}
		
		if($id) {
			$this->db->where('rreg_recr_id', $id);
		}
		
		$this->db->stop_cache();
		
		$this->db->select('a.*, b.recr_subject');
		$this->db->join($this->table .' b', 'a.rreg_recr_id = b.recr_id');
		$this->db->order_by($sst, $sod);

		$qry = $this->db->get($this->table_reg .' a', $limit, $offset);
		$result['qry'] = $qry->result_array();
		$result['total_cnt'] = $this->db->count_all_results($this->table_reg);
		
		$this->db->flush_cache();

		return $result;
	}
	
	function reg_row($id, $fields='*') {
		if (!$id) return FALSE;
	
		$row = $this->db->get_row($id, $this->table_reg, $fields);
	
		return $row;
	}
		
	function record($w, $sql) {
		if(!$sql)
			return false;
		
		if ($w == '') {
			$sql['recr_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('recr_id' => $this->input->post('recr_id')));
			return $this->input->post('recr_id');
		}
	}

	function reg_record($w, $sql) {
		if(!$sql)
			return false;
	
		if ($w == '') {
			$sql['rreg_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table_reg, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table_reg, $sql, array('rreg_id' => $this->input->post('rreg_id')));
			return $this->input->post('rreg_id');
		}
	}
	
	function list_update($ids, $data) {
		$primary = $this->db->primary($this->table);
		
		$batch = array();		
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['recr_mdydate']	= TIME_YMDHIS;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
		
		$this->db->update_batch($this->table, $batch, $primary);
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('recr_id', $ids);
		else
			$this->db->where('recr_id', $ids);
		
		return $this->db->delete($this->table);
	}

	function reg_delete($ids) {
		if(is_array($ids))
			$this->db->where_in('rreg_id', $ids);
		else
			$this->db->where('rreg_id', $ids);
	
		return $this->db->delete($this->table_reg);
	}
}
?>
