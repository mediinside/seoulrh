<?php
class M_daily_hit extends CI_Model {
	var $table = 'ki_daily_hit';
	var $type = array('board');
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('M_board'));
	}
	
	function list_result($type='', $table='', $find_type='weekly', $date=TIME_YMD, $limit=10) {
		$timestamp = strtotime($date);
		switch($find_type) {
			case 'daily' :
				$start_date = date('Y-m-d', $timestamp);
				$end_date = date('Y-m-d', $timestamp);
				break;
			case 'weekly' :
				$start_date = date('w', $timestamp)==0 ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('last Sunday', $timestamp));
				$end_date = date('w', $timestamp)==6 ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('next Saturday', $timestamp));
				break;
			case 'monthly' :
				$start_date = date('Y-m-01', $timestamp);
				$end_date = date('Y-m-d', strtotime('-1 day', strtotime('+1 month', strtotime(date('Y-m-01', $timestamp)))));
				break;
			default:
				return FALSE;
				break;
		}
		
		if($type) {
			$this->db->where('dh_type', $type);
		}
		
		if($table) {
			$this->db->where('dh_ref_table', $table);
		}
		
		$this->db->select('a.*, SUM(a.dh_count) AS dh_count, b.wr_subject');
		$this->db->where('dh_regdate >=', $start_date);
		$this->db->where('dh_regdate <=', $end_date);
		$this->db->join($this->M_board->table.' b', 'a.dh_ref_table = b.bid AND a.dh_ref_idx = b.wr_id');
		$this->db->group_by('dh_ref_table');
		$this->db->group_by('dh_ref_idx');
		$this->db->order_by('dh_count', 'desc');
		$qry = $this->db->get($this->table .' a', $limit, 0);
		$result = $qry->result_array();
		
		$list = array();
		foreach($result AS $key => $row) {
			if($row['dh_type'] == 'board') {
				$list[$key]['type'] = $row['dh_type'];
				$list[$key]['table'] = $row['dh_ref_table'];
				$list[$key]['idx'] = $row['dh_ref_idx'];
				$list[$key]['subject'] = $row['wr_subject'];
				$list[$key]['href'] = RT_PATH .'/board/'. $row['dh_ref_table'] .'/view/wr_id/'. $row['dh_ref_idx'];
			}
			else if($type == 'board') {	// 미구현
					
			}
			else {
				continue;
			}				
		}
		
		return $list;
	}
	
	function update($type, $table, $idx) {
		if(array_search($type, $this->type) === FALSE) {
			return FALSE;
		}
		
		$sql = "
			INSERT INTO ". $this->table ." (
				dh_type,
				dh_ref_table,
				dh_ref_idx,
				dh_count,
				dh_regdate )
			VALUES ('$type', '$table', '$idx', '1', '". TIME_YMD ."')
			ON DUPLICATE KEY UPDATE
				dh_count = dh_count + 1 ";
		$this->db->query($sql);
	}
}
?>
