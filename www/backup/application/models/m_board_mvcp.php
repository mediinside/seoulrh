<?php
class M_board_mvcp extends CI_Model {
	var $table = 'ki_write';
	
	function __construct() {
		parent::__construct();
	}
	
	// 이동, 복사 게시판 리스트
	function list_move_copy($bid, $mb_id) {
		$this->db->join($this->M_basic->table_gr.' b', 'a.gr_id = b.gr_id');
		$this->db->select('a.bid,a.bo_subject, b.gr_subject');
		$this->db->where('bid <>', $bid);
		$this->db->where('bo_db', '');
		if (IS_ADMIN == 'group')
			$this->db->where('b.gr_admin', $mb_id);
		else if (IS_ADMIN == 'board')
			$this->db->where('a.bo_admin', $mb_id);
		
		$this->db->order_by('a.gr_id, a.bid');
		$qry = $this->db->get($this->M_basic->table.' a');
		return $qry->result_array();
	}
	
	// 게시판의 최소 wr_num을 얻는다. ( M_board 에 있음 ㄱ- )
	function get_min_num($bid) {
	    // 가장 작은 번호를 얻어
	    $this->db->select_min('wr_num', 'min_wr_num');
	    $qry = $this->db->get_where($this->table, array('bid' => $bid));
	    $row = $qry->row_array();
	    
	    return $row['min_wr_num'];
	}
	
	// 대상 게시물의 중복되지 않는 wr_num 추출 ( 답변까지 대상에 포함)
	function get_dist_num($bid, $wr_ids) {
		$this->db->distinct();
		$this->db->select('wr_num');
		$this->db->where('bid', $bid);
		$this->db->where_in('wr_id', $wr_ids);
		$this->db->order_by('wr_id');
		$qry = $this->db->get($this->table);
		$result = $qry->result_array();
		
		$wr_nums = array();
		foreach ($result as $row) {
			$wr_nums[] = $row['wr_num'];
		}
		
		return $wr_nums;
	}
	
	// 해당 wr_num 에 관련된 게시물 정보
	function get_write_num($bid, $wr_nums) {
		if (!$wr_nums) return FALSE;

		$this->db->select('*');
		$this->db->order_by('wr_num desc, wr_parent, wr_is_comment, wr_id');
		$this->db->where('bid', $bid);
		$this->db->where_in('wr_num', $wr_nums);
		$qry = $this->db->get($this->table);
		return $qry->result_array();
	}
	
	// 게시물 Insert
	function write_insert($bid, $wr_num, $wr) {
		$sql = array(
  			'bid'    	   => $bid,
			'wr_num'           => $wr_num,
            'wr_reply'         => $wr['wr_reply'],
            'wr_is_comment'    => $wr['wr_is_comment'],
            'wr_comment'       => $wr['wr_comment'],
            'wr_comment_reply' => $wr['wr_comment_reply'],
            'ca_code'          => $wr['ca_code'],
            'wr_option'        => $wr['wr_option'],
            'wr_subject'       => $wr['wr_subject'],
            'wr_content'       => $wr['wr_content'], // addslashes()
            'wr_tag'           => $wr['wr_tag'],
            'wr_hit'           => $wr['wr_hit'],
            'mb_id'            => $wr['mb_id'],
            'wr_password'      => $wr['wr_password'],
            'wr_name'          => $wr['wr_name'],
            'wr_email'         => $wr['wr_email'],
            'wr_datetime'      => $wr['wr_datetime'],
            'wr_last'          => $wr['wr_last'],
            'wr_ip'            => $wr['wr_ip'],
			'uf_count_file'    => $wr['uf_count_file'],
			'uf_count_image'   => $wr['uf_count_image']
		);
  		$this->db->insert($this->table, $sql);
  		
  		return $this->db->insert_id();
	}
	
	// 게시물 파일 정보
	function get_write_file($bid, $wr_id) {
		$this->db->select('uf_table,uf_id,uf_no,uf_editor,uf_source,uf_file,uf_download,uf_filesize,uf_width,uf_height,uf_type,uf_datetime');
    	$this->db->order_by('uf_no');
    	$qry = $this->db->get_where('ki_upload_files', array(
    		'uf_table' => $bid,
    		'uf_id' => $wr_id
		));
		return $qry->result_array();
	}
	
	// 게시물 파일 Insert
	function write_file_insert($bid, $wr_id, $uf) {
		$sql = array(
        	'uf_table'    => $bid,
            'uf_id'       => $wr_id,
            'uf_no'       => $uf['uf_no'],
			'uf_editor'	  => $uf['uf_editor'],
            'uf_source'   => $uf['uf_source'],
            'uf_file'     => $uf['uf_file'],
            'uf_download' => $uf['uf_download'],
            'uf_filesize' => $uf['uf_filesize'],
            'uf_width'    => $uf['uf_width'],
            'uf_height'   => $uf['uf_height'],
            'uf_type'     => $uf['uf_type'],
            'uf_datetime' => $uf['uf_datetime']
		);
		$this->db->insert('ki_upload_files', $sql);
	}
	
	// 원글 Update
	function write_parent_update($bid, $wr_ids, $wr_parent) {
		$this->db->where('bid', $bid);
		$this->db->where_in('wr_id', $wr_ids);
		$this->db->update($this->table, array('wr_parent' => $wr_parent));
	}
	
	// 게시판 글/코멘트/wr_num Update
	function bo_count_update($bid, $count_write, $count_comment, $min_wr_num, $op) {
		$this->db->set('bo_count_write', 'bo_count_write '.$op.' '.$count_write, FALSE);
		$this->db->set('bo_count_comment', 'bo_count_comment '.$op.' '.$count_comment, FALSE);
		$this->db->update($this->M_basic->table, array('bo_min_wr_num' => $min_wr_num), array('bid' => $bid)); 
	}
	
	// 이동시 원글/파일 삭제
	function write_delete($bid, $wr_ids) {
		if (!$bid || !$wr_ids) return FALSE;
		
		$board = $this->db->query("SELECT bo_notice FROM ".$this->M_basic->table." WHERE bid='$bid'")->row_array();
		$bo_notice = explode(',', $board['bo_notice']);
		$new_notice = array_diff($bo_notice, $wr_ids);
		$this->db->update($this->M_basic->table, array('bo_notice' => implode(',',$new_notice)), array('bid' => $bid)); 
		
		$this->db->where('bid', $bid);
		$this->db->where_in('wr_id', $wr_ids);
		$this->db->delete($this->table);
		
		$this->db->where('uf_table', $bid);
		$this->db->where_in('uf_id', $wr_ids);
		$this->db->delete('ki_upload_files');
	}

	// 첨부파일로 인한 본문 업데이트
	function content_update($bid, $wr_id, $content) {
        $this->db->update($this->table, array(
            'wr_content' => $content
        ), array('bid' => $bid, 'wr_id' => $wr_id));
    }
}
