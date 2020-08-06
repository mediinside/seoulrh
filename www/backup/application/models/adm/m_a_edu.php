<?php
class M_a_edu extends CI_Model {
	var $table = 'ki_edu';
	var $table_opt = 'ki_edu_opt';
	var $table_reg = 'ki_edu_reg';
	//var $pg_co = array('agspay' => '올더게이트', 'ini' => '이니시스', 'kcp' => 'KCP');
	var $pg_co = array('agspay' => '올더게이트');
	
	function __construct() {
		parent::__construct();
	}
	
	function setConfig($sql) {
		if(!$sql)
			return false;
		
		foreach($sql AS $scf_id => $scf_value) {
			$scf_value = is_array($scf_value) ? $scf_value : array($scf_value,'');
			$query = "
				INSERT INTO ". $this->table_cfg ." (scf_id, scf_value, scf_extend, scf_mdydate)
				VALUES
					('$scf_id', '" . $scf_value[0] ."', '" . addslashes($scf_value[1]) ."', '". TIME_YMDHIS ."')
				ON DUPLICATE KEY UPDATE
					scf_value =			'". $scf_value[0] ."',
					scf_extend =		'". addslashes($scf_value[1]) ."',
					scf_mdydate =		'". TIME_YMDHIS ."'
			";
			
			if(!$this->db->query($query)) {
				return FALSE;
			}
		}
		
		return true;
	}
	
	function list_result($cate, $sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		
		if ($stx) {
			$this->db->start_cache();
			$this->db->like($sfl, $stx, 'center');
		}
		
		$this->db->where('pd_cate', $cate);
		
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
		
		$this->db->where('reg_edu_id', $id);
		
		$this->db->stop_cache();
		
		$this->db->select('*');
		$this->db->order_by($sst, $sod);

		$qry = $this->db->get($this->table_reg, $limit, $offset);
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
			$sql['pd_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('pd_id' => $this->input->post('pd_id')));
			return $this->input->post('pd_id');
		}
	}

	function reg_record($w, $sql) {
		if(!$sql)
			return false;
	
		if ($w == '') {
			$sql['reg_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table_reg, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table_reg, $sql, array('reg_id' => $this->input->post('reg_id')));
			return $this->input->post('reg_id');
		}
	}
	
	function set_options($id, $options) {		// $options = array('id'=>'', 'name'=>'', 'calculate'=>'', 'price'=>'');
		if(!$id)
			return false;
		
		$primary = $this->db->primary($this->table_opt);
		
		// 넘어오지 않은 기존 id는 삭제
		$this->db->where('pd_id', $id);
		if($options['id']) {
			$this->db->where_not_in($primary, $options['id']);
		}
		$this->db->delete($this->table_opt);
		
		$sql = "INSERT INTO ". $this->table_opt.
			"(pd_id, pdo_name, pdo_price, pdo_regdate) VALUES ";
		
		$opt_sql = $batch = array();
		if($options) {
			foreach($options['name'] AS $key => $name) {
				$price = (int)($options['calculate'][$key] . $options['price'][$key]);
				
				if(trim($name) && $options['id'][$key]) {
					$batch[$key][$primary]			= $options['id'][$key];
					$batch[$key]['pdo_name']		= $name;
					$batch[$key]['pdo_price']		= $price;
				}
				else if(trim($name)) {
					$opt_sql[] =  "('$id', '$name', '". $price ."', '". TIME_YMDHIS ."')";					
				}
			}
		}
		
		// Insert
		if($opt_sql) {
			$this->db->query($sql . implode(',', $opt_sql));
		}
		
		// Update
		if($batch) {
			$this->db->update_batch($this->table_opt, $batch, $primary);
		}
	}
	
	function list_update($ids, $data) {
		$primary = $this->db->primary($this->table);
		
		$batch = array();		
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['pd_mdydate']	= TIME_YMDHIS;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
		
		$this->db->update_batch($this->table, $batch, $primary);
	}

	function reg_list_update($ids, $data) {
		$primary = $this->db->primary($this->table_reg);
	
		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['reg_mdydate']	= TIME_YMDHIS;

			foreach ($data as $fld => $val) {
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
			}
		}
		
		$this->db->update_batch($this->table_reg, $batch, $primary);
	}
	
	function reg_lists($ids) {
		$this->db->where_in('reg_id', $ids);
		$qry = $this->db->get($this->table_reg);
		
		return $qry->result_array();
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('pd_id', $ids);
		else
			$this->db->where('pd_id', $ids);
		
		return $this->db->delete($this->table);
	}

	function reg_delete($ids) {
		if(is_array($ids))
			$this->db->where_in('reg_id', $ids);
		else
			$this->db->where('reg_id', $ids);
	
		return $this->db->delete($this->table_reg);
	}
}
?>
