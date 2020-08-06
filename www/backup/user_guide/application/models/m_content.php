<?php
class M_content extends CI_Model {
	var $table = 'ki_content';
	
	function __construct() {
		parent::__construct();
		
		$this->load->model('M_layout');
	}
	
	function get_content($url_filename, $fields='*') {
		if (!$url_filename) return FALSE;
	
		$filename = end(explode('/', $url_filename));
		$url = preg_replace('/(\/?)'.$filename.'/', '', $url_filename);
		
		$this->db->select($fields);
		$result = $this->db->get_where($this->table, array('ct_url' => $url, 'ct_filename' => $filename, 'ct_hidden' => 0));
		$content = $result->row_array();
		
		return $content;
	}
	
	function row($id, $fields='*') {
		if (!$id) return FALSE;
		
		return $this->db->select($fields)->get_where($this->table, array('ct_id' => $id))->row_array();
	}
}
?>
