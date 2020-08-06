<?php
class M_latest extends CI_Model {
	var $table = 'ki_write';
	
	function __construct() {
		parent::__construct();
		
		$this->load->model('M_postlink');
    	$this->load->helper(array('board', 'textual', 'video'));
	}

	// 최신글
    function write($bid='', $limit=5, $cut=50, $thumb='50x50', $orderby='wr_datetime desc', $tbl_not_in=FALSE) {
        $this->db->cache_on();
        
		$this->db->select('bid, wr_id, wr_comment, ca_code, wr_subject, wr_content, wr_datetime, uf_count_image');
		$this->db->order_by($orderby);
		
		if(is_array($bid))
			$this->db->where_in('bid', $bid);
		else if($bid && $tbl_not_in == FALSE)
			$this->db->where('bid', $bid);
		else if($bid && $tbl_not_in == TRUE)
			$this->db->where_not_in('bid', $bid);
		
		if($limit) $qry = $this->db->get_where($this->table .' a', array('wr_is_comment' => 0), $limit);
		else $qry = $this->db->get_where($this->table .' a', array('wr_is_comment' => 0));
		
		$result = $qry->result_array();
		
        $this->db->cache_off();
        
        $list = array();
        /*
        if($limit) {
        	$thumbnail = $thumb ? '/useful/thumbnail/'.$thumb.'/'.$bid : '';
	        $list = array_fill(0, $limit,
						array_combine(
							array('href','subject','content','comment','thumb','date'),
							array('javascript:;','게시물이 없습니다.','','',$thumbnail,'')
					));
        }
		*/
        
		foreach($result as $i => $row) {
			//$list[$i] = $row;
			$list[$i]['href'] = RT_PATH.'/board/'.$row['bid'].'/view/wr_id/'.$row['wr_id'].($row['ca_code'] ? '/sca/'.$row['ca_code'] : '');
			$list[$i]['subject'] = cut_str(get_text($row['wr_subject']), $cut);
			$list[$i]['content'] = preg_replace("/\s+&nbsp;+/", '', get_text(strip_tags(htmlspecialchars_decode($row['wr_content']))));
			$list[$i]['comment'] = ($row['wr_comment']) ? '('.$row['wr_comment'].')' : '';
			$list[$i]['date'] = preg_replace('/\-/','.', substr($row['wr_datetime'],2,8));
			$list[$i]['thumb'] = thumbnail($row['bid'], $row['wr_id'], $row['uf_count_image'], $thumb, $row['wr_content']);

			preg_match('/(http(s)?:\/\/(www\.)?(youtube.com\/embed\/[^?]+))+/i', $row['wr_content'], $videos);
			$list[$i]['video'] = $videos ? $videos[0] : '';
			
			// 관련글
			$list[$i]['postlink'] = $this->M_postlink->list_result($row['bid'], $row['wr_id']);
		}
		
		return $list;
	}
    
    // 댓글
    function comment($limit=5, $cut=50) {
        $this->db->cache_on();
        
		$this->db->select('bid, wr_id, wr_parent, wr_option, wr_content');
		$this->db->order_by('wr_datetime', 'desc');
		$qry = $this->db->get_where($this->table, array('wr_is_comment' => 1), $limit);
		$result = $qry->result_array();
		
        $this->db->cache_off();
        
		$list = array();
		$this->load->helper('textual');
		foreach($result as $i => $row) {
            if (strpos($row['wr_option'], 'secret') !== FALSE)
				continue;

			$list[$i]->href = RT_PATH.'/board/'.$row['bid'].'/view/wr_id/'.$row['wr_parent'].'#c_'.$row['wr_id'];
			$list[$i]->content = cut_str(get_text($row['wr_content']), $cut); 
		}

		return $list;
	}
}
