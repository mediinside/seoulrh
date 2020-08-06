<?php
class M_research extends CI_Model {
	var $table_conf = 'ki_research_conf';
	var $table_opt = 'ki_research_opt';
	var $table = 'ki_research';
	var $score_flds = array(
		'semi'	=> array(
			'sex'			=> array('남성', '여성'),
			'type'			=> array('물리', '작업', '언어', '심리'),
			'grade'			=> array('1학년', '2학년', '3학년', '4학년'),
			'plan'			=> array('5', '4', '3', '2', '1'),
			'time'			=> array('5', '4', '3', '2', '1'),
			'circum'		=> array('5', '4', '3', '2', '1'),
			'data'			=> array('5', '4', '3', '2', '1'),
			'recommend'		=> array('예', '아니오', '잘 모르겠다', '기타'),
			'satisfaction'	=> array()
		),
		'sati'	=> array(
			'circum'		=> array('7', '6', '5', '4', '3', '2', '1'),
			'process'		=> array('7', '6', '5', '4', '3', '2', '1'),
			'kind'			=> array('7', '6', '5', '4', '3', '2', '1'),
			'service'		=> array('7', '6', '5', '4', '3', '2', '1'),
			'route'			=> array('타병원소개(의료진)', '타병원소개(직원)', '타병원소개(간병인)', '타병원소개(동료환우)', '주변인의추천', '거리상가까움', '대중매체(인터넷)', '대중매체(신문)', '기타'),
			'recommend'		=> array('예', '아니오'),
			'homepage'		=> array('7', '6', '5', '4', '3', '2', '1'),
			'newspaper'		=> array('7', '6', '5', '4', '3', '2', '1')
		)
	);
	var $essay_flds = array(
		'motive'		=> '',
		'supplement'	=> ''
	);
	
	function __construct() {
		parent::__construct();
		
		foreach($this->score_flds AS $k => $v) {
			foreach($v AS $key => $fld) {
				$this->score_flds[$k][$key] = array_fill_keys($fld, 0);
			}
		}
	}
	
	function record($cate, $form_data) {
		$id = $this->get_max_id($cate);
		
		$sql = array('rsho_rsh_id' => $id);		
		foreach($form_data AS $fld => $val) {
			$is_text = $order = 0;
			$answer = $text = '';
			
			if(preg_match('/^sati_/', $fld)) {
				// 강의 만족도
				$ord = preg_replace('/^sati_/', '', $fld);
				$type = 'satisfaction';
				$order = $ord;
			}
			else {
				// 일반 객관식
				$type = $fld;
			}
			
			// 텍스트는 텍스트 항목에 답 저장
			if(isset($this->essay_flds[$fld])) {
				if(!trim($val)) {
					continue;
				}
				
				$this->db->select_max('rsho_is_text', 'max');
				$qry = $this->db->get_where($this->table_opt, array(
						'rsho_rsh_id' => $id,
						'rsho_type' => $type
				));
				$row = $qry->row_array();
				
				$is_text = $row['max'] + 1;
				$text = $val;
			}
			else {
				$answer = $val;
			}
			
			$date = TIME_YMDHIS;
			
			$qry = "INSERT INTO ". $this->table_opt ." (
					rsho_rsh_id,
					rsho_is_text,
					rsho_type,
					rsho_order,
					rsho_answer,
					rsho_count,
					rsho_text,
					rsho_mdydate,
					rsho_regdate )
				VALUES ('$id', '$is_text', '$type', '$order', '$answer', 1, '$text', '$date', '$date')
				ON DUPLICATE KEY UPDATE
					rsho_count = rsho_count + 1,
					rsho_mdydate = '$date'
			";
			loger($qry);
			$this->db->query($qry);
		}
		
		return $this->db->insert_id();
	}
	
	function get_semi_count($id='') {
		$this->db->select('rshc_count');
		
		if($id) {
			$this->db->where('rshc_rsh_id', $id);
		}
		
		$this->db->order_by('rshc_id', 'desc');
		$row = $this->db->get($this->table_conf, 1)->row_array();
		
		return $row ? $row['rshc_count'] : 0;
	}
	
	function ext_sati($cnt) {
		$score_arr = array();
		for($i = 1; $i <= $cnt; $i++) {
			$score_arr[$i] = array_fill(1, 5, 0);
		}
		
		return $score_arr;
	}
	
	function get_max_id($cate) {
		$this->db->select('*');
		$this->db->where('rsh_cate', $cate);
		$this->db->order_by('rsh_id', 'desc');
		
		if(!$row = $this->db->get($this->table, 1)->row_array()) {
			$row = $this->db->get_columns($this->table);
			$row['rsh_cate'] = $cate;
		}
		
		return $row['rsh_id'];
	}
}
?>
