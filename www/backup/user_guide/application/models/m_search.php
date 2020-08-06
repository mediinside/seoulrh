<?php
class M_search extends CI_Model {
	var $table = 'ki_write';
	var $table_bo = 'ki_board';
	var $table_pou = 'ki_popular';
	
	function __construct() {
		parent::__construct();
	}
	
	function search_board($mb_level=0, $where_not_table='') {
		if($where_not_table) $this->db->where_not_in('bid', $where_not_table);
		$this->db->select('gr_id, bid, bo_read_level, bo_subject');
		$this->db->order_by('bo_order_search, gr_id, bid');
		$qry = $this->db->get_where($this->table_bo, array(
			'bo_use_search' => 1,
			'bo_list_level <=' => (int)$mb_level
		));
		return $qry->result_array();
	}

	function list_result($sfl, $stx, $limit, $offset, $boards, $opt_where='', $opt_order='') {
		// 인기검색어
		if($stx)
        	$this->db->simple_query(" insert into ".$this->table_pou." set pp_word = '".$stx."', pp_date = '".date("Y-m-d",time())."', pp_ip = '".$this->input->server('REMOTE_ADDR')."' ");
		
		if (!$boards)
			return FALSE;
		
		if($stx) $s = explode(' ', $stx);
		if($sfl) $field = explode('.', trim($sfl));
		
		if($stx && $sfl) {
			$opt = '';
			$where = ' ( ';
			foreach ($s as $sval) {
				// 검색어
				$search_str = trim($sval);
				if ($search_str == '')
					continue;
				
				$opt2 = '';
				$where .= $opt.'(';
				foreach ($field as $fval) {
					$where .= $opt2;
					
					if (preg_match('/[a-zA-Z]/', $search_str))
						$where .= 'INSTR(LOWER('.$this->db->protect_identifiers($fval).'), LOWER('.$this->db->escape($search_str).'))';
					else
						$where .= 'INSTR('.$this->db->protect_identifiers($fval).', '.$this->db->escape($search_str).')';
								
					$opt2 = ' or ';
				}
				$where .= ')';
				$opt = ' and ';
			}
			$where .= ' ) ';
		}
		
		$this->db->start_cache();
		if($opt_where)
			$this->db->where($opt_where);
		
		$this->db->where_in('bid', $boards);
		if(isset($where)) $this->db->where($where, null, FALSE);
		$this->db->stop_cache();
		
		$result['total_cnt'] = $this->db->count_all_results($this->table);
		
		$this->db->select('*');
		if($opt_order) $this->db->order_by($opt_order);
		$this->db->order_by('wr_datetime desc');
		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();
		
        $this->db->flush_cache();
        
		return $result;
	}
}
