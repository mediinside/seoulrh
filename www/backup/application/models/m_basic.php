<?php
class M_basic extends CI_Model {
	var $table		= 'ki_board';
	var $table_gr	= 'ki_board_group';
	
	function __construct() {
		parent::__construct();
	}

	// 회원 정보
	function get_member($mb_id, $fields='*') {
		if(!$mb_id) return FALSE;
		
		$this->db->select($fields);
		$qry = $this->db->get_where('ki_member', array('mb_id' => $mb_id));
		$mb = $qry->row_array();

		if(isset($mb['mb_birth']))
			$mb['mb_birth'] = $mb['mb_birth']=='0000-00-00' ? '' : $mb['mb_birth'];
		
		return $mb;
	}

	// 게시판 정보
	function get_board($bid=false, $fields='*', $gr_join=true, $ly_join=true) {
		$this->load->helper('board');
		
       	$bo_tbl = is_array($bid) ? implode(',', $bid) : $bid;
        
		$gr_field = $ly_field = '';
		if ($gr_join) {
			$this->db->join($this->table_gr.' b', 'a.gr_id = b.gr_id');
			$gr_field = ', b.gr_id, b.gr_subject, b.gr_admin ';
		}
		if($ly_join) {
			$this->db->join($this->M_layout->table.' c', 'a.bo_layout = c.ly_id', 'left');
			$ly_field = ',c.ly_file';
		}
		
		$this->db->select('a.'.$fields.$gr_field.$ly_field);
		
		if($bo_tbl) {
			$this->db->where_in('a.bid', $bo_tbl);
		}
		
		$qry = $this->db->get($this->table.' a');;
		
		if($boards = setBoardDb($qry->result_array())) {		
			return $bid ? $boards[0] : $boards;
		}
		
		return FALSE;
	}
	
	// 게시물 정보
	function get_write($bid, $wr_ids, $fields='*') {
		if (!$wr_ids) return FALSE;

		$this->db->select($fields);
		$this->db->where('bid', $bid);
		if (is_array($wr_ids)) {
			$this->db->where_in('wr_id', $wr_ids);
			$qry = $this->db->get('ki_write');
			return $qry->result_array();
		}
		else {
			$this->db->where('wr_id', $wr_ids);
			$qry = $this->db->get('ki_write');
			return $qry->row_array();
		}
	}
}
?>
