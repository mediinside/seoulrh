<?php
class M_uninsured extends CI_Model {
	var $table = 'ki_uninsured';
	var $table_cate = 'ki_uninsured_cate';

	function __construct() {
		parent::__construct();
	}

	function getBaseCategory($is_active=false) {
		$this->db->select('uic_cd, uic_title');
		$this->db->where('uic_parent', '');
		if ($is_active) $this->db->where('uic_use', '1');
		$this->db->order_by('uic_sort', 'asc');
		$stmt = $this->db->get($this->table_cate);

		$list = $stmt->result_array();
		$result = array();
		foreach ($list as $row) {
			$result[$row['uic_cd']] = $row['uic_title'];
		}
		return $result;
	}

	function getSubCategory($cate_cd, $is_active=false) {
		$this->db->select('uic_cd, uic_title');
		$this->db->where('uic_parent', $cate_cd);
		if ($is_active) $this->db->where('uic_use', '1');
		$this->db->order_by('uic_sort', 'asc');
		$stmt = $this->db->get($this->table_cate);

		$list = $stmt->result_array();
		$result = array();
		foreach ($list as $row) {
			$result[$row['uic_cd']] = $row['uic_title'];
		}
		return $result;
	}

	function getDataList() {
		$result = array();
		$mapCate = $this->getBaseCategory();
		foreach ($mapCate as $code => $text) {
			$result[$code] = array(
					  'cate_nm' => $text
					, 'data' => array()
				);
		}

		$query = array();
		$query[] = "select t.*, d.ui_cnt from (";
		$query[] = "  select uic_cd, uic_title, uic_parent from ". $this->table_cate ." where uic_use = 1 and uic_parent != ''";
		$query[] = ") as t";
		$query[] = "join (";
		$query[] = "  select uic_cd, count(*) as ui_cnt from ". $this->table ." where ui_use = 1 group by uic_cd";
		$query[] = ") as d";
		$query[] = "on t.uic_cd = d.uic_cd";
		$sql = implode(' ', $query);
		$stmt = $this->db->query($sql);
		$list = $stmt->result_array();
		foreach ($list as $row) {
			$result[$row['uic_parent']]['data'][$row['uic_cd']] = array(
					  'uic_title' => $row['uic_title']
					, 'cnt' => $row['ui_cnt']
					, 'data' => array()
				);
		}

		$query = array();
		$query[] = "select * from ". $this->table ." where ui_use = 1 order by ui_sort";
		$sql = implode(' ', $query);
		$stmt = $this->db->query($sql);
		$list = $stmt->result_array();
		foreach ($list as $row) {
			$uic_parent = substr($row['uic_cd'], 0, 1);
			$result[$uic_parent]['data'][$row['uic_cd']]['data'][] = $row;
		}

		return $result;
	}
}
?>