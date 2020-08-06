<?php
class M_edu extends CI_Model {
	var $table = 'ki_edu';
	var $table_opt = 'ki_edu_opt';
	var $table_reg = 'ki_edu_reg';
	var $cate = array(1 => '작업치료세미나', 2 => '신경과학세미나', 3 => '소아물리작업치료세미나', 4 => '보행훈련세미나', 5 => '임상작업치료세미나', 6 => '청소년치료세미나', 7 => '일상생활동작치료세미나', 8 => '작업치료상담세미나', 9 => '뇌졸중/뇌성마비 세미나', 10 => '재활간호세미나', 11 => '소아작업세미나');
	var $pay_arr = array(0 => '입금대기', 1 => '입금완료', 2 => '쿠폰', 9 => '환불');
	var $cert_arr = array(0 => '미발급', 1 => '발급', 9 => '발급취소');

	function __construct() {
		parent::__construct();
		$this->load->helper('textual');
	}

	function row($id, $fields='*') {
		if (!$id) return FALSE;

		if($row = $this->db->get_row($id, $this->table, $fields)) {
			$row['pd_options'] = $this->get_options($row['pd_id']);
		}

		return $row;
	}

	function edu_reg_row($reg_id, $fields='*') {
		if (!$reg_id) return FALSE;

		$this->db->join($this->table .' b', 'b.pd_id = a.reg_edu_id', 'left');

		$this->db->select($fields);
		$this->db->where('reg_id', $reg_id);
		$qry = $this->db->get($this->table_reg .' a');

		if($row =$qry->row_array()) {
			$row['pd_options'] = $this->get_options($row['pd_id']);
		}

		return $row;
	}

	function reg_seach($name, $phone, $email, $where = '') {
		if (!$name || !$phone || !$email) return FALSE;

		$this->db->select('reg_id');
		$this->db->where('reg_name', $name);
		$this->db->where('reg_phone', $phone);
		$this->db->where('reg_email', $email);

		if($where) {
			$this->db->where($where);
		}

		$qry = $this->db->get($this->table_reg);

		return $qry->row_array();
	}

	function list_result($cate, $sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();

		if ($stx) {
			$sfl = explode('|', $sfl);
			$like = array();
			foreach($sfl AS $s) {
				$like[] = $s ." LIKE '%$stx%'";
			}
			if($like) {
				$this->db->where('('. implode(' OR ', $like) .')');
			}
		}

		if($cate) {
			$this->db->where('pd_cate', $cate);
		}

		$this->db->where('pd_hidden', '0');

		$this->db->stop_cache();

		$this->db->select('a.*, b.pdo_id AS is_option');
		$this->db->join($this->table_opt.' b', 'a.pd_id = b.pd_id', 'left');
		$this->db->group_by('a.pd_id');
		$this->db->order_by('pd_id', 'desc');
		$qry = $this->db->get($this->table.' a', $limit, $offset);
		$result = $qry->result_array();

		foreach($result AS $key => $row) {
			$result[$key]['pd_options'] = $result[$key]['is_option'] ? $this->get_options($row['pd_id']) : array();
		}

		$result['qry'] = $result;
		$result['total_cnt'] = $this->db->count_all_results($this->table);

		$this->db->flush_cache();

		return $result;
	}

	function record($sql) {
		if(!$sql)
			return false;

		$sql['reg_regdate'] = TIME_YMDHIS;
		$this->db->insert($this->table_reg, $sql);
		return $this->db->insert_id();
	}

	function get_options($id) {
		if(!$id) {
			return FALSE;
		}

		$this->db->select('*');
		$this->db->where('pd_id', $id);
		$this->db->order_by('pdo_id', 'asc');
		$qry = $this->db->get($this->table_opt);
		return $qry->result_array();
	}
}
?>
