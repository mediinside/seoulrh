<?php
class M_a_research extends CI_Model {
	var $table_conf = 'ki_research_conf';
	var $table_opt = 'ki_research_opt';
	var $table = 'ki_research';
	
	function __construct() {
		parent::__construct();
	}
	
	function record($sql) {
		if(!$sql)
			return false;
		
		$sql['rsh_regdate'] = TIME_YMDHIS;
		$this->db->insert($this->table, $sql);
		
		return $this->db->insert_id();
	}
	
	function record_conf($sql) {
		if(!$sql)
			return false;
		
		$sql['rshc_regdate'] = TIME_YMDHIS;
		$this->db->duplicate($this->table_conf, $sql, array('rshc_rsh_id', 'rshc_regdate'));
		
		return $this->db->insert_id();
	}

	function get_conf($cate) {
		$id = $this->M_research->get_max_id($cate);
		
		$this->db->select('*');
		$this->db->where('rshc_rsh_id', $id);
		$this->db->order_by('rshc_rsh_id', 'desc');
		$row = $this->db->get($this->table_conf, 1)->row_array();
		
		return $row;
	}
	
	function get_score($cate) {
		$max_id = $this->M_research->get_max_id($cate);
		
		$this->db->select('*');
		$this->db->where('b.rsh_cate', $cate);
		$this->db->where('a.rsho_is_text', 0);
		$this->db->join($this->table .' b', 'a.rsho_rsh_id = b.rsh_id');
		$this->db->where('b.rsh_id', $max_id);
		$rows = $this->db->get($this->table_opt .' a')->result_array();
		
		$score = $this->init_score($cate, $max_id);
		
		foreach($rows AS $row) {
			if(isset($this->M_research->score_flds[$cate][$row['rsho_type']][$row['rsho_answer']]) && !$row['rsho_order']) {
				$score[$row['rsho_type']][$row['rsho_answer']] = $row['rsho_count'];
			}
			else if(isset($this->M_research->score_flds[$cate][$row['rsho_type']][$row['rsho_order']][$row['rsho_answer']])) {
				$score[$row['rsho_type']][$row['rsho_order']][$row['rsho_answer']] = $row['rsho_count'];
			}
		}
		
		return $score;
	}
	
	function get_essay() {
		$this->db->where('rsho_is_text <>', 0);
		$this->db->where('rsho_text <>', '');
		$this->db->order_by('rsho_regdate', 'desc');
		$rows = $this->db->get($this->table_opt)->result_array();
		
		return $rows;
	}
	
	function init_score($cate, $id='') {
		if($cate == 'semi') {
			$cnt = $this->M_research->get_semi_count($id);
		
			$score_arr = $this->M_research->ext_sati($cnt);
			$this->M_research->score_flds[$cate]['satisfaction'] = $score_arr;
		}		
		
		return $this->M_research->score_flds[$cate];
	}
	
	function delete_opt($rsh_id, $rsho_is_text, $rsho_type, $rsho_order, $rsho_answer) {
		$this->db->where('rsho_rsh_id', $rsh_id);
		$this->db->where('rsho_is_text', $rsho_is_text);
		$this->db->where('rsho_type', $rsho_type);
		$this->db->where('rsho_order', $rsho_order);
		$this->db->where('rsho_answer', $rsho_answer);
		
		$this->db->limit(1);
		
		return $this->db->delete($this->table_opt);
	}
}
?>
