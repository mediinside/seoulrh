<?php
class M_a_ctable extends CI_Model {
	var $table = 'ki_ctable';
	var $table_cate = 'ki_ctable_cate';
	
	function __construct() {
		parent::__construct();
	}

	function list_result($cid, $sst, $sod, $sfl, $stx) {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
					break;
			}
		}

		$this->db->where('cta_cate', $cid);
		
		$this->db->stop_cache();
		
		$result['total_cnt'] = $this->db->count_all_results($this->table);
		
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table);
		$result['qry'] = $qry->result_array();
		
		$this->db->flush_cache();
	
		return $result;
	}

	function row_cate($id) {
		if (!$id) {
			return FALSE;
		}
	
		$this->db->select('*');
		$this->db->where('ctg_id', $id);
		$row = $this->db->get($this->table_cate)->row_array();
	
		return $row;
	}
	
	function record_cate($id, $sql) {
		if(!$sql)
			return FALSE;
		
		if(!$id) {
			$sql['ctg_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table_cate, $sql);
			$id = $this->db->insert_id();
		}
		else {
			$primary = $this->db->primary($this->table_cate);
			$this->db->update($this->table_cate, $sql, array($primary => $id));
		}
		
		return $id;
	}
	
	function delete_cate($id) {
		if(!$id) {
			return FALSE;
		}
		
		$this->db->where('cta_cate', $id);
		
		if($this->db->delete($this->table)) {
			$this->db->where('ctg_id', $id);
			
			return $this->db->delete($this->table_cate);
		}
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('cta_id', $ids);
		else
			$this->db->where('cta_id', $ids);
	
		return $this->db->delete($this->table);
	}

	function row($id, $fields='*') {
		$row = $this->db->select($fields)->get_where($this->table, array('cta_id' => $id))->row_array();
		
		return $row;
	}
	
	function record($cid, $id, $sql) {
		if(!$sql || !$cid) {
			return FALSE;
		}
		
		if(!$id) {
			$sql['cta_cate'] = $cid;
			$sql['cta_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			
			return $this->db->insert_id();
		}
		else {
			$primary = $this->db->primary($this->table);
			$this->db->update($this->table, $sql, array($primary => $id));
			
			return $id;
		}
	}
}
?>
