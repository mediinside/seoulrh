<?php
class M_board extends CI_Model {
	var $table = 'ki_write';
	
	function __construct() {
		parent::__construct();
		$this->load->model(array('M_postlink', 'M_upload_files'));
	}

	function list_result($bid, $spt, $sca, $sst, $sod, $sfl, $stx, $limit, $offset, $wr_field='*', $where='') {
		if($where) {
			$this->db->where($where);
		}
		$this->db->where('wr_is_comment', '0');
		$this->db->where('bid', $bid);
		$this->_get_search_cache($sca, $sfl, $stx, $spt);
		
		//if ($sca || ($sfl && $stx)) {
			$this->db->distinct();
			$this->db->select('wr_id');
		//}
		//else {
		//	$this->db->select($wr_field);
		//	$this->db->where('wr_is_comment', '0');
		//}

		if ($sst && $sod) $this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table, $limit, $offset);
		$result = $qry->result_array();

		//if ($sca || ($sfl && $stx)) {
			foreach($result as $row)
				$wr_ids[] = (int)$row['wr_id'];
			
			if (isset($wr_ids)) {
				if ($sst && $sod) $this->db->order_by($sst, $sod);
				return $this->M_basic->get_write($bid, $wr_ids, $wr_field);
			}
		//}

		return $result;
	}

	function list_count($bid, $spt, $sca, $sfl, $stx, $where='') {
		if($where) {
			$this->db->where($where);
		}
		
		// 인기검색어
		if (trim($stx))
			$this->db->simple_query(" insert into ki_popular set pp_word = '".$stx."', pp_date = '".TIME_YMD."', pp_ip = '".$this->input->server('REMOTE_ADDR')."' ");

		// 원글만 얻는다. (코멘트의 내용도 검색하기 위함)
		$this->db->select(' COUNT(DISTINCT '.$this->db->protect_identifiers('wr_id').') as '.$this->db->protect_identifiers('wr_count'));
		$this->db->where('bid', $bid);
		$this->db->where('wr_is_comment', '0');
		$this->_get_search_cache($sca, $sfl, $stx, $spt);

		$qry = $this->db->get($this->table);
		$row = $qry->row();
		return $row->wr_count;
	}
	
	function list_select($bid, $wr_ids, $wr_field='*') {
		$key = count($wr_ids)-1;
		if ($wr_ids[$key] == '')
			unset($wr_ids[$key]);

		$this->db->select($wr_field);
		$this->db->where('bid', $bid);
		$this->db->where_in('wr_id', $wr_ids);
		$this->db->order_by('wr_datetime', 'desc');
		$qry = $this->db->get($this->table);
		return $qry->result_array();
	}

	// 검색 구문을 얻는다.
	function _get_search_cache($search_ca_code, $search_field, $search_text, $spt=FALSE) {
		if ($spt)
			$this->db->where("wr_num between '".$spt."' and '".($spt + $this->config->item('cf_search_part'))."'");

		if ($search_ca_code) {
			$code_exp = explode('.', $search_ca_code);
			if (!isset($code_exp[1]))
				$limit_code = $search_ca_code + 1;
			else {
				$code_ori = substr($code_exp[1], 0, -3);
				$code_num = substr($code_exp[1], -3) + 1;
				$code_plus = repeater('0', 3-strlen($code_num)).$code_num;
				$limit_code = $code_exp[0].'.'.$code_ori.$code_plus;
			}
			$this->db->where(array(
				'ca_code >=' => (float)$search_ca_code,
				'ca_code <' => (float)$limit_code
			));
		}

		if (!$search_field || !$search_text)
			return FALSE;

		// 검색어를 구분자로 나눈다. 여기서는 공백
		$s = array();
		$s = explode(' ', $search_text);

		// 검색필드를 구분자로 나눈다.
		$tmp = array();
		$tmp = explode('-', trim($search_field));
		$field = explode('.', $tmp[0]);

		$opt = '';
		$where = ' ( ';
		foreach ($s as $sval) {
			// 검색어
			$search_str = trim($sval);
			if ($search_str == '')
				continue;
			
			$opt2 = '';
			$where .= $opt.'(';
			foreach ($field as $fval) {  // 필드의 수만큼 다중 필드 검색 (필드1+필드2...)
				$where .= $opt2;
				switch ($fval) {
					case 'mb_id' :
					case 'wr_name' :
						$where .= $this->db->protect_identifiers($fval).' = '.$this->db->escape($search_str);
					break;
					// LIKE 보다 INSTR 속도가 빠름 (누가 그래?)
					default :
						if (preg_match('/[a-zA-Z]/', $search_str))
							$where .= 'INSTR(LOWER('.$this->db->protect_identifiers($fval).'), LOWER('.$this->db->escape($search_str).'))';
						else
							$where .= 'INSTR('.$this->db->protect_identifiers($fval).', '.$this->db->escape($search_str).')';
					break;
				}
				$opt2 = ' or ';
			}
			$where .= ')';
			$opt = ' and ';
		}
		$where .= ' ) ';
		$this->db->where($where, null, FALSE);

		if (isset($tmp[1]))
			$this->db->where('wr_is_comment', '0');
	}

	// 원본글 작성자 인가. 비밀글시.
	function is_owner($bid, $wr_num) {
		$this->db->select('mb_id');
		$qry = $this->db->get_where($this->table, array(
			'bid' => $bid,
			'wr_is_comment' => '0',
			'wr_num' => $wr_num,
			'wr_reply' => ''
		));
		return $qry->row_array();
	}

	// 조회수 증가
	function hit_update($bid, $wr_id) {
		$this->db->set('wr_hit', 'wr_hit + 1', FALSE);
		$this->db->update($this->table, null, array('bid' => $bid, 'wr_id' => $wr_id));
		
		$this->load->model('M_daily_hit');
		$this->M_daily_hit->update('board', $bid, $wr_id);
	}
	
	// 다음, 이전 글 링크
	function prev_next_link($bid, $wr_num, $wr_reply, $sca, $sfl, $stx, $notice) {
		$this->db->start_cache(); // S
		$this->db->select('wr_id, wr_subject');
		$this->db->where(array('bid' => $bid, 'wr_is_comment' => '0'));
		
		if($notice) {
			$this->db->where_not_in('wr_id', explode(',', $notice));
		}
		
		$this->db->stop_cache(); // E
		
		// 이전글 답변글
		$this->db->where(array('wr_num' => $wr_num, 'wr_reply <' => $wr_reply));
		$this->_get_search_cache($sca, $sfl, $stx);
		$this->db->order_by('wr_num desc, wr_reply desc');
		$qry = $this->db->get($this->table, 1);

		$next = $qry->row_array();
		// 다음 답변글이 없다면 다음글
		if (!isset($next['wr_id'])) {
			$this->db->where('wr_num <', $wr_num);
			$this->_get_search_cache($sca, $sfl, $stx);
			$this->db->order_by('wr_num desc, wr_reply desc');
			$qry = $this->db->get($this->table, 1);
			$next = $qry->row_array();

		}
		
		// 다음글 답변글
		$this->db->where(array('wr_num' => $wr_num, 'wr_reply >' => $wr_reply));
		$this->_get_search_cache($sca, $sfl, $stx);
		$this->db->order_by('wr_num desc, wr_reply desc');
		$qry = $this->db->get($this->table, 1);

		$prev = $qry->row_array();
		// 이전 답변글이 없다면 이전글
		if (!isset($prev['wr_id'])) {
			$this->db->where('wr_num >', $wr_num);
			$this->_get_search_cache($sca, $sfl, $stx);
			$this->db->order_by('wr_num, wr_reply');
			$qry = $this->db->get($this->table, 1);
			$prev = $qry->row_array();
		}

		$this->db->flush_cache();

		$result['prev'] = ($prev) ? $prev : FALSE;
		$result['next'] = ($next) ? $next : FALSE;

		return $result;
	}
	
	// 관련 답변 존재 여부
	function is_reply($bid, $wr_id, $wr_num, $wr_reply) {
		$len = strlen($wr_reply);
		$len = ($len < 0) ? 0 : $len;
		$reply = substr($wr_reply, 0, $len);

		$this->db->where('bid', $bid);
		$this->db->like('wr_reply', $reply, 'after');
		$this->db->where(array(
			'wr_id <>' => $wr_id,
			'wr_num' => $wr_num,
			'wr_is_comment' => 0
		));

		if ($this->db->count_all_results($this->table) > 0)
			return TRUE;

		return FALSE;
	}
	
	// 관련 코멘트 존재 여부
	function is_comment($bid, $wr_id, $mb_id) {
		$where = array(
			'bid' => $bid,
			'wr_parent' => $wr_id
		);
		if ($mb_id)
			$where['mb_id <>'] = $mb_id;	
		$where['wr_is_comment'] = 1;
		
		$this->db->where($where);
		return $this->db->count_all_results($this->table);
	}
	
	// 답변 단계 얻기
	function get_reply_step($bid, $wr_num, $bo_reply_order, $wr_reply) {
		$reply_len = strlen($wr_reply) + 1;

		if ($bo_reply_order) {
			$begin_reply_char = '1';
			$end_reply_char = 'z';
			$reply_number = +1;
	
			$this->db->select_max(' SUBSTRING(wr_reply, '.$reply_len.', 1) ', 'reply');
		}
		else {
			$begin_reply_char = 'z';
			$end_reply_char = '1';
			$reply_number = -1;

			$this->db->select_min(' SUBSTRING(wr_reply, '.$reply_len.', 1) ', 'reply');
		}

        $this->db->where(array(
			'bid' => $bid,
			'wr_num' => $wr_num,
			'SUBSTRING(wr_reply, '.$reply_len.', 1) <>' => ''
		));

		if ($wr_reply)
			$this->db->like('wr_reply', $wr_reply, 'after');

		$qry = $this->db->get($this->table);
		$row = $qry->row_array();

		if (!isset($row['reply']))
			$reply_char = $begin_reply_char;
		else if ($row['reply'] == $end_reply_char) // '1' 부터 'z'은 74 입니다. 0은 false라 사용 안함.
			alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 75개 까지만 가능합니다.");
		else
			$reply_char = chr(ord($row['reply']) + $reply_number);
		
		return $wr_reply.$reply_char;
	}
	
	// 답변 리스트
	function list_reply($bid, $wr_id) {		
		$this->db->select('wr_id, mb_id, wr_option, wr_content, wr_name, wr_datetime, wr_ip, wr_comment_reply');
		$this->db->order_by('wr_datetime');
		$qry = $this->db->get_where($this->table, array(
			'bid' => $bid,
			'wr_parent ' => $wr_id,
			'wr_is_comment' => 0
		));
		return $qry->result_array();
	}
	
	
	// 코멘트 리스트
	function list_comment($bid, $wr_id) {
		$this->db->select('wr_id, mb_id, wr_option, wr_content, wr_name, wr_datetime, wr_ip, wr_comment_reply');
		$this->db->order_by('wr_comment, wr_comment_reply');
		$qry = $this->db->get_where($this->table, array(
			'bid' => $bid,
			'wr_parent' => $wr_id,
			'wr_is_comment' => '1'
		));
		return $qry->result_array();
	}
	
	// 동일내용 연속 등록 불가
	function same_write($bid) {
		$this->db->select('MD5(CONCAT(wr_ip, wr_subject, wr_content)) as prev_md5', FALSE);
		$this->db->order_by('wr_id', 'desc');
		$qry = $this->db->get_where($this->table, array(
            'bid' => $bid,
            'wr_is_comment' => '0'
        ), 1);
		return $qry->row_array();
	}
	
	// 게시판의 최소 wr_num을 얻는다.
	function get_min_num($bid) {
	    // 가장 작은 번호를 얻어
	    $this->db->select_min('wr_num', 'min_wr_num');
	    $qry = $this->db->get_where($this->table, array('bid' => $bid));
	    $row = $qry->row_array();
	    
	    return $row['min_wr_num'];
	}
	
	function write_insert($bid, $wr_content, $wr_num, $wr_reply, $mb, $bo_notice, $p_id='') {
		if($p_id) {
			$ca_code = $this->db->select('ca_code')->get($this->table)->row()->ca_code;
		}
		else {
			$ca_code = ($this->input->post('ca_code')) ? str_replace('-', '.', $this->input->post('ca_code')) : '';
		}
		
		$sql = array(
			'bid'			=> $bid,
			'wr_num'		=> $wr_num,
            'wr_reply'		=> $wr_reply,
			'wr_parent'		=> $p_id,
            'wr_comment'	=> '0',
            'ca_code'		=> $ca_code,
            'wr_option'		=> $this->input->post('editor').','.$this->input->post('secret').','.$this->input->post('mail').','.$this->input->post('nocomt'),
            'wr_subject'	=> $this->input->post('wr_subject'),
            'wr_content'	=> $wr_content,
            'wr_tag'		=> $this->input->post('wr_tag'),
            'wr_hit'		=> '0',
            'mb_id'			=> $mb['mb_id'],
            'wr_password'	=> $mb['wr_password'],
            'wr_name'		=> $mb['wr_name'],
            'wr_email'		=> $mb['wr_email'],
            'wr_datetime'	=> TIME_YMDHIS,
            'wr_last'		=> TIME_YMDHIS,
            'wr_ip'			=> $this->input->server('REMOTE_ADDR')
		);
		
		$this->db->insert($this->table, $sql);
		
	    $wr_id = $this->db->insert_id();

	    //$this->db->update($this->table, array('wr_parent' => $wr_id), array('bid' => $bid, 'wr_id' => $wr_id));
	    
	    // 관련글 저장
	    $this->M_postlink->update($bid, $wr_id, $this->input->post('wr_postlink'));
	    
	    // 부모 아이디에 UPDATE
		$this->db->set('wr_reply_count', 'wr_reply_count + 1', FALSE);
	    $this->db->update($this->table, null, array('bid' => $bid, 'wr_id' => $p_id), false);
	    
	    // 게시글 1 증가
	    $this->db->set('bo_count_write', 'bo_count_write + 1', FALSE);

	    $sql_u = array();
		if ($this->input->post('w') == '') {
			if ($this->input->post('notice'))
				$sql_u['bo_notice'] = $bo_notice ? trim($wr_id.','.$bo_notice) : $wr_id;

			$sql_u['bo_min_wr_num'] = $wr_num;
		}
		
	    $this->db->update('ki_board', $sql_u, array('bid' => $bid));
	    
	    return $wr_id;
	}
	
	function write_update($bid, $wr_content, $wr_id, $wr_num, $mb, $bo_notice) {
		$ca_code = ($this->input->post('ca_code')) ? array('ca_code' => str_replace('-', '.', $this->input->post('ca_code'))) : array();
		
		$sql = array_merge(array(
            'wr_option' 	=> $this->input->post('editor').','.$this->input->post('secret').','.$this->input->post('mail').','.$this->input->post('nocomt'),
            'wr_subject'	=> $this->input->post('wr_subject'),
            'wr_content'	=> $wr_content,
            'wr_tag'		=> $this->input->post('wr_tag'),
            'mb_id'			=> $mb['mb_id'],
            'wr_name'		=> $mb['wr_name'],
            'wr_email'		=> $mb['wr_email']
		), $ca_code);
	    
	    if ($this->input->post('wr_password')) {
			$this->load->library('encrypt');			
			$sql['wr_password'] = $this->encrypt->encode($this->input->post('wr_password'));
		}
		
		if (!IS_ADMIN)
			$sql['wr_ip'] = $this->input->server('REMOTE_ADDR');
	
		$this->db->update($this->table, $sql, array('bid' => $bid, 'wr_id' => $wr_id));
		
		// 관련글 저장
		$this->M_postlink->update($bid, $wr_id, $this->input->post('wr_postlink'));
		
	    // 분류가 수정되는 경우 해당되는 코멘트의 분류명도 모두 수정함
	    // 코멘트와 답변의 분류를 수정하지 않으면 검색이 제대로 되지 않는다.
		if($ca_code) {
	    	$this->db->update($this->table, $ca_code, array('bid' => $bid, 'wr_num' => $wr_num));
		}
	    
	    // 공지사항
	    if(IS_ADMIN) {
	    	$this->db->update($this->M_basic->table, array('bo_notice' => trim($bo_notice)), array('bid' => $bid));
	    }
	}
	
	function write_delete($bid, $wr_ids, $bo_notice, $bo_min_wr_num, $bo_extra) {
		if (!$wr_ids) return FALSE;
		
		// 게시물 파일 삭제	
		$this->db->select('uf_file');
		$this->db->where('uf_table', $bid);
		$this->db->where_in('uf_id', $wr_ids);
		
		$qry = $this->db->get($this->M_upload_files->table);
		$result = $qry->result_array();		
		foreach ($result as $row) {
			// 파일 삭제
			@unlink(DATA_PATH.'/file/'.$bid.'/'.$row['uf_file']);
			// 썸네일 삭제
			del_thumb(DATA_PATH.'/file/'.$bid.'/thumb', $row['uf_file']);
		}
		
		// 파일 레코드 삭제
		$this->db->where('uf_table', $bid);
		$this->db->where_in('uf_id', $wr_ids);
		$this->db->delete($this->M_upload_files->table);
		
		// 답글이면 부모 아이디에 답글수 감소
		$write = $this->M_basic->get_write($bid, $wr_ids, 'wr_num, wr_reply');
		foreach($write as $row) {
			if($row['wr_reply']) {
				$parent_reply = substr($row['wr_reply'], 0, -1);
				$this->db->set('wr_reply_count', 'wr_reply_count - 1', FALSE);
				$this->db->update($this->table, null, array('bid' => $bid, 'wr_num' => $row['wr_num'], 'wr_reply' => $parent_reply));
			}
		}
		
		// 게시물/코멘트 삭제
		$this->db->where('bid', $bid);
		$this->db->where('(wr_id IN ('. implode(',',$wr_ids) .') OR wr_parent IN ('. implode(',',$wr_ids) .'))');
		$this->db->delete($this->table);
		
		// 관련글 삭제
		$this->db->where('pl_ref_table', $bid)->where_in('pl_ref_id', $wr_ids)->delete($this->M_postlink->table);
		$this->db->where('pl_link_table', $bid)->where_in('pl_link_id', $wr_ids)->delete($this->M_postlink->table);
		
		// 컨텐츠 확장 기능 데이터 삭제
		$this->db->where('dbv_ref_table', $bid)->where_in('dbv_ref_id', $wr_ids)->delete($this->M_dbvars->table);
		
		// 게시판 정보 글숫자 감소
		$count_write = count($wr_ids);
		$count_comment = $this->db->affected_rows() - $count_write; // 게시물 삭제개수에서 원글을 빼면 코멘트 개수
		if ($count_write > 0 || $count_comment > 0) {
			$this->db->set('bo_count_write', 'bo_count_write - '.$count_write, FALSE);
			$this->db->set('bo_count_comment', 'bo_count_comment - '.$count_comment, FALSE);
		}
		
		$sql = array();
		if (IS_ADMIN)
			$sql['bo_notice'] = trim($bo_notice);
		
		// min_wr_num 업데이트
		$min_wr_num = $this->get_min_num($bid);
		if ($min_wr_num != $bo_min_wr_num)
			$sql['bo_min_wr_num'] = $min_wr_num;
		
		$this->db->update('ki_board', $sql, array('bid' => $bid));

		// 확장테이블
		if ($bo_extra) {
			$this->db->where_in('wr_id', $wr_ids);
			$this->db->delete('ki_extra_'.$bid);
		}
	}

	function content_update($bid, $wr_id, $content) {
        $this->db->update($this->table, array(
            'wr_content' => $content
        ), array('bid' => $bid, 'wr_id' => $wr_id));
    }

	function get_extra($bid, $wr_ids) {
		$ids = is_array($wr_ids) ? $wr_ids : array($wr_ids);
		
		$this->db->where_in('wr_id', $ids);
		$qry = $this->db->get('ki_extra_'.$bid);
		$res = $qry->result_array();
		
		$flds = $this->db->get_columns('ki_extra_'.$bid);
		
		foreach($ids AS $id) {
			unset($flds['wr_id']);
			$ret_arr[$id] = $flds;
		}
		foreach($res AS $row) {
			$id = $row['wr_id'];
			unset($row['wr_id']);
			$ret_arr[$id] = $row;
		}
		
		$ret_arr = is_array($wr_ids) ? $ret_arr : $ret_arr[$wr_ids];
		
		return $ret_arr;
	}

	function extra_update($bo_table, $wr_id, $sql) {
		if(!$bo_table || !$wr_id || !$sql) {
			return FALSE;
		}
		
		$table = 'ki_extra_'. $bo_table;
		
		$field = $value = $fldVal = array();
		foreach($sql AS $fld => $val) {
			$field[] = $fld;
			$value[] = "'". addslashes($val) ."'";
			if($fld != 'wr_id') {
				$fldVal[] = "$fld = '". addslashes($val) ."'";
			}
		}
		$qry = "INSERT INTO $table (". implode(',', $field) .")
			VALUES (".implode(',', $value) .")
			ON DUPLICATE KEY UPDATE
			".implode(',', $fldVal);

		$this->db->query($qry);
	}
	
	// 첨부파일 리스트
	function getFiles($id, $cnt) {
		$this->db->where('wr_id', $id);
		$this->db->order_by('uf_datetime', 'asc');
		$qry = $this->db->get($this->M_upload_files->table, $cnt);
		
		return $qry->result_array();
	}

	// 자신의 작성글 리스트 리스트
	function my_wr($mb_id, $sca='', $sfl='', $stx='') {
		$this->db->where('mb_id', $mb_id);
		$this->db->select('wr_num');
		$qry = $this->db->get($this->table);
		$result = $qry->result_array();
		$list = array();
		foreach($result AS $row) {
			$list[] = $row['wr_num'];
		}
		
		return $list;
	}
}
?>
