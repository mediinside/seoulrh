<?php
class M_a_menus extends CI_Model {
	var $table = 'ki_a_menus';
		
	function __construct() {
		parent::__construct();
	}

	function list_result() {
		$result['total_cnt'] = $this->db->count_all_results($this->table);

		$this->db->select('*');
		$this->db->order_by('am_sort', 'ASC');
		$this->db->order_by('am_pid', 'ASC');
		$result = $this->db->get($this->table)->result_array();

		return $result;
	}
	
	function row($am_id, $fields='*') {
		if (!$am_id)
			return FALSE;
		
		return $this->db->select($fields)->get_where($this->table, array('am_id' => $am_id))->row_array();
	}
	
	function getInfo($menu_id, $fields = '*') {
		if (!$menu_id) return FALSE;
		
		$this->db->select($fields);
		$this->db->join($this->table .' b', 'a.am_pid = b.am_id', 'left');
		$this->db->where('a.am_id = '.$menu_id);
		$qry = $this->db->get($this->table .' a');

		return $qry->row_array();
	}

	function record($w, $sql) {
		if(!$sql)
			return false;
	
		if ($w == '') {
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('am_id' => $this->input->post('am_id')));
			return $this->input->post('am_id');
		}
	}
	
	function delete($id) {
		if($this->db->delete($this->table, array('am_pid' => $id)))
			return $this->db->delete($this->table, array('am_id' => $id));
	}
}
?>
