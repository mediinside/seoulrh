<?php
class M_board_comt extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	// 동일내용 연속 등록 불가
	function same_comment($bid, $commend_id) {
		$this->db->select('MD5(CONCAT(wr_ip, wr_subject, wr_content)) as prev_md5', FALSE);
		$this->db->where(array(
            'bid' => $bid,
            'wr_is_comment' => '1'
        ));
		if ($this->input->post('w') == 'cu')
			$this->db->where('wr_id <>', $commend_id);

		$this->db->order_by('wr_id', 'desc');
		$qry = $this->db->get('ki_write', 1);
		return $qry->row_array();
	}

	// 관련 답변댓글 존재 여부
	function is_comment_reply($bid, $wr_id, $comment_id, $tmp_comment, $tmp_comment_reply) {
		$len = strlen($tmp_comment_reply);
		$len = ($len < 0) ? 0 : $len;
		$comment_reply = substr($tmp_comment_reply, 0, $len);

		$this->db->where('bid', $bid);
		$this->db->like('wr_comment_reply', $comment_reply, 'after');
		$this->db->where(array(
			'wr_id <>' => $comment_id,
			'wr_parent' => $wr_id,
			'wr_comment' => $tmp_comment,
			'wr_is_comment' => 1
		));

		if ($this->db->count_all_results('ki_write') > 0)
			return TRUE;

		return FALSE;
	}

	// 댓글 답변 단계 얻기
	function get_comment_reply_step($bid, $wr_id, $tmp_comment, $bo_reply_order, $wr_comment_reply) {
		$reply_len = strlen($wr_comment_reply) + 1;

		if ($bo_reply_order) {
			$begin_reply_char = '1';
			$end_reply_char = 'z';
			$reply_number = +1;

			$this->db->select_max(' SUBSTRING(wr_comment_reply, '.$reply_len.', 1) ', 'reply');
		}
		else {
			$begin_reply_char = 'z';
			$end_reply_char = '1';
			$reply_number = -1;

			$this->db->select_min(' SUBSTRING(wr_comment_reply, '.$reply_len.', 1) ', 'reply');
		}
        
        $this->db->where(array(
			'bid' => $bid,
			'wr_parent' => $wr_id,
			'wr_comment' => $tmp_comment,
			'SUBSTRING(wr_comment_reply, '.$reply_len.', 1) <>' => ''
		));

		if ($wr_comment_reply)
			$this->db->like('wr_comment_reply', $wr_comment_reply, 'after');

		$qry = $this->db->get('ki_write');
		$row = $qry->row_array();

		if (!isset($row['reply']))
			$reply_char = $begin_reply_char;
		else if ($row['reply'] == $end_reply_char) // '1' 부터 'z'은 74 입니다. 0은 false라 사용 안함.
			alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 75개 까지만 가능합니다.");
		else
			$reply_char = chr(ord($row['reply']) + $reply_number);

		return $wr_comment_reply.$reply_char;
	}

	// 댓글 갯수 증가
	function get_comment_max($bid, $wr_id) {
		$this->db->select_max('wr_comment', 'max_comment');
		$qry = $this->db->get_where('ki_write', array(
			'bid' => $bid,
			'wr_parent' => $wr_id,
			'wr_is_comment' => '1'
		));
		$row = $qry->row_array();
		return $row['max_comment'] += 1;
	}

	// 댓글 등록
	function comment_insert($bid, $wr_id, $wr_num, $tmp_comment, $tmp_comment_reply, $ca_code, $mb) {
		$sql = array(
			'bid'		   => $bid,
			'ca_code'		   => $ca_code,
			'wr_option'		   => $this->input->post('wr_secret'),
			'wr_num'		   => $wr_num,
			'wr_reply'		   => '',
			'wr_parent'		   => $wr_id,
			'wr_is_comment'    => 1,
			'wr_comment'	   => $tmp_comment,
			'wr_comment_reply' => $tmp_comment_reply,
			'wr_subject'	   => '',
			'wr_content'	   => $this->input->post('wr_content'),
			'mb_id'			   => $mb['mb_id'],
			'wr_password'	   => $mb['wr_password'],
			'wr_name'		   => $mb['wr_name'],
			'wr_email'		   => $mb['wr_email'],
			'wr_datetime'	   => TIME_YMDHIS,
			'wr_last'		   => '',
			'wr_ip'			   => $this->input->server('REMOTE_ADDR')
		);
		$this->db->insert('ki_write', $sql);

		$comment_id = $this->db->insert_id();

		// 원글에 댓글수 증가 & 마지막 시간 반영
		$this->db->set('wr_comment', 'wr_comment + 1', FALSE);
		$this->db->update('ki_write', array('wr_last' => TIME_YMDHIS), array('bid' => $bid, 'wr_id' => $wr_id));

		// 댓글 1 증가
		$this->db->set('bo_count_comment', 'bo_count_comment + 1', FALSE);
		$this->db->update('ki_board', null, array('bid' => $bid));
		
		return $comment_id;
	}

	// 댓글 수정
	function comment_update($bid, $comment_id) {
		$sql = array(
			'wr_subject' => '',
			'wr_content' => $this->input->post('wr_content')
		);

		if (!IS_ADMIN)
			$sql['wr_ip'] = $this->input->server('REMOTE_ADDR');

		if ($this->input->post('wr_secret'))
			$sql['wr_option'] = $this->input->post('wr_secret');

		$this->db->update('ki_write', $sql, array('bid' => $bid, 'wr_id' => $comment_id));
	}
	
	// 댓글 삭제
	function comment_delete($bid, $comment_id, $wr_parent) {
		if (!$comment_id) return FALSE;
		
		// 댓글 삭제
		$this->db->delete('ki_write', array('bid' => $bid, 'wr_id' => $comment_id));
				
		// 댓글이 삭제되므로 해당 게시물에 대한 최근 시간을 다시 얻는다.
		// 이전 댓글 등록 시간으로 변경
		$this->db->select_max('wr_datetime', 'wr_last');
		$qry = $this->db->get_where('ki_write', array('bid' => $bid, 'wr_parent' => $wr_parent));
		$row = $qry->row_array();
		
		// 원글의 댓글 숫자를 감소
		$this->db->set('wr_comment', 'wr_comment - 1', FALSE);
		$this->db->update('ki_write', array('wr_last' => $row['wr_last']), array('bid' => $bid, 'wr_id' => $wr_parent));
		
		// 댓글 숫자 감소
		$this->db->set('bo_count_comment', 'bo_count_comment - 1', FALSE);
		$this->db->update('ki_board', null, array('bid' => $bid));
	}
}
