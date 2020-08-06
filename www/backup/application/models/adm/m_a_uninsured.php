<?php
class M_a_uninsured extends CI_Model {
	var $table = 'ki_uninsured';
	var $table_cate = 'ki_uninsured_cate';

	function __construct() {
		parent::__construct();
	}

	function getItemDataList($cate_cd) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->table_cate, $this->table .'.uic_cd = '. $this->table_cate .'.uic_cd');
		$this->db->where($this->table_cate .".uic_parent = '". $cate_cd ."'");
		$this->db->order_by($this->table_cate .'.uic_sort');
		$this->db->order_by($this->table .'.ui_sort');
		$stmt = $this->db->get();
		return $stmt->result_array();
	}

	function getItemDataRow($ui_id) {
		return $this->db->select('*')->get_where($this->table, array('ui_id' => $ui_id))->row_array();
	}

	function execInsertItem($data) {
		return $this->db->insert($this->table, $data);
	}

	function execUpdateItem($ui_id, $data) {
		return $this->db->update($this->table, $data, array('ui_id' => $ui_id));
	}

	function execDeleteItem($ui_id) {
		return $this->db->delete($this->table, array('ui_id' => $ui_id));
	}

	function execMoveUpItem($rowSource) {
		$this->db->where('uic_cd', $rowSource['uic_cd']);
		$this->db->where('ui_sort < '. $rowSource['ui_sort']);
		$this->db->limit(1);
		$this->db->order_by('ui_sort', 'desc');
		$rowTarget = $this->db->get($this->table)->row_array();
		if (!$rowTarget) return;

		$this->db->update($this->table, array('ui_sort' => $rowTarget['ui_sort']), array('ui_id' => $rowSource['ui_id']));
		$this->db->update($this->table, array('ui_sort' => $rowSource['ui_sort']), array('ui_id' => $rowTarget['ui_id']));
	}

	function execMoveDnItem($rowSource) {
		$this->db->where('uic_cd', $rowSource['uic_cd']);
		$this->db->where('ui_sort > '. $rowSource['ui_sort']);
		$this->db->limit(1);
		$this->db->order_by('ui_sort', 'asc');
		$rowTarget = $this->db->get($this->table)->row_array();
		if (!$rowTarget) return;

		$this->db->update($this->table, array('ui_sort' => $rowTarget['ui_sort']), array('ui_id' => $rowSource['ui_id']));
		$this->db->update($this->table, array('ui_sort' => $rowSource['ui_sort']), array('ui_id' => $rowTarget['ui_id']));
	}

	function getNewSort($cate_cd) {
		$this->db->select("(ifnull(max(ui_sort), 0) + 1) as sort", false);
		$this->db->where("uic_cd = '". $cate_cd ."'");
		$row = $this->db->get($this->table)->row_array();
		return $row['sort'];
	}

	function getNewCateCode($parent_cd) {
		$this->db->select("char(ascii(ifnull(max(right(uic_cd, 1)), '@')) + 1) as str", false);
		$this->db->where("uic_parent = '". $parent_cd ."'");
		$row = $this->db->get($this->table_cate)->row_array();
		return $parent_cd . $row['str'];
	}

	function getNewCateSort($parent_cd) {
		$sort_base = '';
		if ($parent_cd) {
			$row = $this->getCateRow($parent_cd);
			$sort_base = $row['uic_sort'];
		}
		$this->db->select("ifnull(max(right(uic_sort, 2)), '00') as str", false);
		$this->db->where("uic_parent = '". $parent_cd ."'");
		$row = $this->db->get($this->table_cate)->row_array();
		$sort_new = str_pad(intval($row['str']) + 1, 2, '0', STR_PAD_LEFT);
		return $sort_base . $sort_new;
	}

	function getCateRow($cate_cd) {
		return $this->db->select('*')->get_where($this->table_cate, array('uic_cd' => $cate_cd))->row_array();
	}

	function getCateAll() {
		$this->db->select('*');
		$this->db->order_by('uic_sort', 'asc');
		$stmt = $this->db->get($this->table_cate);
		return $stmt->result_array();
	}

	function execInsertCate($data) {
		return $this->db->insert($this->table_cate, $data);
	}

	function execUpdateCate($cate_cd, $data) {
		return $this->db->update($this->table_cate, $data, array('uic_cd' => $cate_cd));
	}

	function execDeleteCate($cate_cd) {
		$where = "left(uic_cd, ". strlen($cate_cd) .") = '". $cate_cd ."'";
		$this->db->where($where);
		$result = $this->db->delete($this->table);
		if (!$result) return $result;
		$this->db->where($where);
		return $this->db->delete($this->table_cate);
	}

	function execMoveUpCate($rowSource) {
		$base_sort = $rowSource['uic_sort'];
		$cnt_sort = strlen($base_sort);
		$cnt_substr = $cnt_sort + 1;

		$this->db->select('max(uic_sort) as uic_sort', false);
		$this->db->where("uic_sort < '". $base_sort ."'");
		$this->db->where("length(uic_sort) = ". $cnt_sort);
		$rowTarget = $this->db->get($this->table_cate)->row_array();
		if (!$rowTarget || !$rowTarget['uic_sort']) return;

		$temp_sort = str_pad('', $cnt_sort, '0');

		$query = array();
		$query[] = "update ". $this->table_cate ." set";
		$query[] = "uic_sort = concat('{$temp_sort}', substr(uic_sort, ". $cnt_substr ."))";
		$query[] = "where left(uic_sort, ". $cnt_sort .") = '{$rowSource['uic_sort']}'";
		$sql = implode(' ', $query);
		$this->db->query($sql);

		$query = array();
		$query[] = "update ". $this->table_cate ." set";
		$query[] = "uic_sort = concat('{$rowSource['uic_sort']}', substr(uic_sort, ". $cnt_substr ."))";
		$query[] = "where left(uic_sort, ". $cnt_sort .") = '{$rowTarget['uic_sort']}'";
		$sql = implode(' ', $query);
		$this->db->query($sql);

		$query = array();
		$query[] = "update ". $this->table_cate ." set";
		$query[] = "uic_sort = concat('{$rowTarget['uic_sort']}', substr(uic_sort, ". $cnt_substr ."))";
		$query[] = "where left(uic_sort, ". $cnt_sort .") = '{$temp_sort}'";
		$sql = implode(' ', $query);
		$this->db->query($sql);
	}

	function execMoveDnCate($rowSource) {
		$base_sort = $rowSource['uic_sort'];
		$cnt_sort = strlen($base_sort);
		$cnt_substr = $cnt_sort + 1;

		$this->db->select('min(uic_sort) as uic_sort', false);
		$this->db->where("uic_sort > '". $base_sort ."'");
		$this->db->where("length(uic_sort) = ". $cnt_sort);
		$rowTarget = $this->db->get($this->table_cate)->row_array();
		if (!$rowTarget || !$rowTarget['uic_sort']) return;

		$temp_sort = str_pad('', $cnt_sort, '0');

		$query = array();
		$query[] = "update ". $this->table_cate ." set";
		$query[] = "uic_sort = concat('{$temp_sort}', substr(uic_sort, ". $cnt_substr ."))";
		$query[] = "where left(uic_sort, ". $cnt_sort .") = '{$rowSource['uic_sort']}'";
		$sql = implode(' ', $query);
		$this->db->query($sql);

		$query = array();
		$query[] = "update ". $this->table_cate ." set";
		$query[] = "uic_sort = concat('{$rowSource['uic_sort']}', substr(uic_sort, ". $cnt_substr ."))";
		$query[] = "where left(uic_sort, ". $cnt_sort .") = '{$rowTarget['uic_sort']}'";
		$sql = implode(' ', $query);
		$this->db->query($sql);

		$query = array();
		$query[] = "update ". $this->table_cate ." set";
		$query[] = "uic_sort = concat('{$rowTarget['uic_sort']}', substr(uic_sort, ". $cnt_substr ."))";
		$query[] = "where left(uic_sort, ". $cnt_sort .") = '{$temp_sort}'";
		$sql = implode(' ', $query);
		$this->db->query($sql);
	}
}
?>