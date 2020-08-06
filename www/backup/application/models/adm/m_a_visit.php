<?php
class M_a_visit extends CI_Model {
	var $table = 'ki_visit';
	var $table_cnt = 'ki_visit_cnt';
	
	function __construct() {
		parent::__construct();
	}
	
	function list_result($fr_date, $to_date, $limit='', $offset='') {
		$this->db->start_cache();
		$this->db->where("vs_regdate between '".$fr_date."' and '".$to_date."'");
		$this->db->stop_cache();
	
		$result['total_cnt'] = $this->db->count_all_results('ki_visit');
	
		$this->db->select('*');
		$this->db->order_by('vs_id', 'desc');
		
		if($limit) $qry = $this->db->get($this->table, $limit, $offset);
		else $qry = $this->db->get($this->table);
		
		$result['qry'] = $qry->result_array();
	
		$this->db->flush_cache();
	
		return $result;
	}
	
	function get_count($fr_date, $to_date, $group_by='vsc_regdate', $show_robot=FALSE) {
		$this->db->select('sum(vsc_count) AS vsc_count, '. $group_by);
		$this->db->where("vsc_regdate between '".$fr_date."' and '".$to_date."'");
		
		if(!$show_robot) {
			$this->db->where("vsc_browser <> 'Robot'");
			$this->db->where("vsc_os <> 'Robot'");
		}
		
		$this->db->group_by($group_by);
		$qry = $this->db->get($this->table_cnt);
		$result = $qry->result_array();

		return $result;
	}
	
	function count_result($year='', $month='', $day='') {
		if(!$year) $year = date("Y", time());
		if(!$month) $month = date("m", time());
		
		$this->db->where("vc_year = '".$year."' and vc_month = '".$month."'");
		
		if($day) {
		$this->db->select('*');
			$this->db->where("vc_day = '".$day."'");
		}
		else {
			$this->db->select('*, sum(vc_count) as vc_count');
			$this->db->group_by('vc_day');
		}
		
		$qry = $this->db->get($this->table_cnt);
		$result = $qry->result_array();
		
		return $result;
	}
}
?>
