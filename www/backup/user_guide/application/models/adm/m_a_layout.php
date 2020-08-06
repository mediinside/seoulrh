<?php
class M_a_layout extends CI_Model {
	var $table = 'ki_layout';
	
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
			$sql['ly_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('ly_id' => $this->input->post('ly_id')));
			return $this->input->post('ly_id');
		}
	}
	
	function get_using_cnt($id='') {
		$useModels = array('M_a_content'=>'ct_layout', 'M_a_board'=>'bo_layout');		// 레이아웃을 사용하는 모델들
				
		$cnt = array();
		foreach($useModels AS $modelName => $fldName) {
			$this->load->model(ADM_F.'/'.$modelName);
			
			if($id) {
				$this->db->where($fldName, $id);
			}
		
			$primary = $this->db->primary($this->$modelName->table);
		
			$this->db->group_by($fldName);
			$result = $this->db->select('COUNT('.$primary.') AS cnt,'. $fldName)->get($this->$modelName->table)->result_array();
			
			foreach($result AS $row) {
				$cnt[$row[$fldName]] = isset($cnt[$row[$fldName]]) ? $cnt[$row[$fldName]] + $row['cnt'] : $row['cnt'];
			}
		}
		
		return $cnt;
	}
	
	function get_layouts() {
		$this->db->select('*');
		$this->db->where('ly_hidden', 0);
		$qry = $this->db->get($this->table);
		$result = $qry->result_array();
		
		return $result;
	}
	
	function chk_layout_id($id, $layout_id) {
		if (!$layout_id) return FALSE;
	
		$is_url = $this->db->where(array('ly_id <>' => $id, 'ly_file' => $layout_id))->count_all_results($this->table);
	
		return $is_url;
	}
	
	function list_update($ids, $data) {
		$this->load->model('M_layout');
		$this->load->helper('content');
		
		$primary = $this->db->primary($this->table);
		
		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['ly_mdydate']	= TIME_YMDHIS;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
			
			$old = $this->M_layout->row($id);
			// html 파일명 수정
			chgFileName($batch[$key]['ly_file'], $old['ly_file'], '/layout');			
		}
		
		$this->db->update_batch($this->table, $batch, $primary);
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('ly_id', $ids);
		else
			$this->db->where('ly_id', $ids);
		
		return $this->db->delete($this->table);
	}
}
?>
