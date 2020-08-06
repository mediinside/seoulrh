<?php
class M_a_mail extends CI_Model {
	var $table = 'ki_mail';
	var $table_skin = 'ki_mail_skin';
	
	function __construct() {
		parent::__construct();
	}

	function list_result($table, $sst, $sod, $sfl, $stx, $limit, $offset) {
		$gr_join = $gr_field = '';
		
		$result['total_cnt'] = $this->db->count_all_results($table);
		
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				case 'ma_skin' :
					$this->db->join($this->table_skin.' b', 'a.ma_skin = b.ms_id');
					$this->db->like('ms_name', $stx, 'both');
					$gr_join = 'a';
					$gr_field = ', b.ms_name ';
					break;
				default :
					$this->db->like($sfl, $stx, 'both');
					break;
			}
		}
		$this->db->stop_cache();
		
		$this->db->select('*'. $gr_field);
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($table .' '. $gr_join, $limit, $offset);
		$result['qry'] = $qry->result_array();
		
		$this->db->flush_cache();
	
		return $result;
	}
	
	function row($table, $id, $fields='*') {
		if (!$id) return FALSE;
		
		$primary = $this->db->primary($table);
		
		return $this->db->select($fields)->get_where($table, array($primary => $id))->row_array();
	}
	
	function record($table, $w, $sql) {
		if(!$sql)
			return false;
		
		$regdate_colName = $table == $this->table ? 'ma_regdate' : 'ms_regdate';
		
		$primary = $this->db->primary($table);
		
		if ($w == '') {
			$sql[$regdate_colName] = TIME_YMDHIS;
			$this->db->insert($table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($table, $sql, array($primary => $this->input->post($primary)));
			return $this->input->post($primary);
		}
	}
	
	function list_update($table, $ids, $data) {
		$mdydate_colName = $table == $this->table ? 'ma_mdydate' : 'ms_mdydate';
		
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
	
	function delete($table, $ids) {
		$primary = $this->db->primary($table);
		
		if(is_array($ids))
			$this->db->where_in($primary, $ids);
		else
			$this->db->where($primary, $ids);
		
		return $this->db->delete($table);
	}
	
	function chk_skin_id($id, $skin_id) {
		if (!$skin_id) return FALSE;
	
		$is_skin_id = $this->db->where(array('ms_id <>' => $id, 'ms_skin_id' => $skin_id))->count_all_results($this->table_skin);
	
		return $is_skin_id;
	}
	
	function member_cnt() {
		$result['total_cnt'] = $this->db->count_all_results('ki_member');

		$this->db->where('mb_leave_date <>', '');
		$result['leave_cnt'] = $this->db->count_all_results('ki_member');
	
		$result['member_cnt'] = $result['total_cnt'] - $result['leave_cnt'];
		return $result;
	}

	function select_list() {
		$this->db->start_cache();

		if ($this->input->post('mb_email'))
			$this->db->like('mb_email', $this->input->post('mb_email'), 'both');

		if ($this->input->post('mb_birth_from') && $this->input->post('mb_birth_to'))
			$this->db->where("SUBSTRING(REPLACE(mb_birth,'-',''),5,4) BETWEEN ".$this->input->post('mb_birth_from')." AND ".$this->input->post('mb_birth_to'));

		if ($this->input->post('mb_area'))
			$this->db->like('mb_addr1', $this->input->post('mb_area'), 'after');

		if ($this->input->post('mb_mailling'))
			$this->db->where('mb_mailling', $this->input->post('mb_mailling'));

		$this->db->where('mb_level BETWEEN '.$this->input->post('mb_level_from').' AND '.$this->input->post('mb_level_to'));
		$this->db->where(array(
			'mb_leave_date' => ''
		));

		$this->db->stop_cache();

		$result['select_cnt'] = $this->db->count_all_results('ki_member');

		$this->db->select('mb_id,mb_name,mb_nick,mb_email,mb_birth,mb_datetime');
		$this->db->order_by('mb_id', 'asc');
		$qry = $this->db->get('ki_member');
		$result['qry'] = $qry->result_array();

		$this->db->flush_cache();

		return $result;
	}

	function option_update() {
		$ma_last_option = "mb_email=".$this->input->post('mb_email');
		$ma_last_option .= "||mb_birth_from=".$this->input->post('mb_birth_from');
		$ma_last_option .= "||mb_birth_to=".$this->input->post('mb_birth_to');
		$ma_last_option .= "||mb_area=".$this->input->post('mb_area');
		$ma_last_option .= "||mb_mailling=".$this->input->post('mb_mailling');
		$ma_last_option .= "||mb_level_from=".$this->input->post('mb_level_from');
		$ma_last_option .= "||mb_level_to=".$this->input->post('mb_level_to');
		
		$this->db->update($this->table, array('ma_last_option' => $ma_last_option), array('ma_id' => $this->input->post('ma_id')));
	}
	
	function get_skins() {
		return $this->db->get($this->table_skin)->result_array();
	}
}
?>
