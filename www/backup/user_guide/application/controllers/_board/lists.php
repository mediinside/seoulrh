<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Lists extends Widget {
	function index($view=FALSE) {
		$board		=& $this->board;
		$member		=& $this->member;
		$wr_field	=& $this->wr_field;
		$seg		=& $this->seg;

		$page		= $seg->get_seg('page');				// 페이지
		$wr_id		= $seg->get_seg('wr_id');				// 게시물아이디
		$sst		= $seg->get_seg('sst');					// 정렬필드
		$sod		= $seg->get_seg('sod');					// 정렬순서
		$sfl		= $seg->get_seg('sfl');					// 검색필드
		$stx		= $seg->get_seg('stx');					// 검색어
		$sca		= $seg->get_seg('sca');					// 분류
		$spt		= $seg->get_seg('spt');					// 검색 파트
		$qstr		= qstr_rep($seg->get_qstr(), 'wr_id');	// 쿼리스트링
		$dqstr		= qstr_rep($seg->get_qstr(), 'wr_id,sfl,stx');	// 쿼리스트링
		
		// 권한 확인
		if($member['mb_level'] < $board['bo_list_level']) {
			if(!IS_MEMBER) {
				goto_url('member/login/qry/'.url_encode('board/'.BO_TABLE.'/lists'.$qstr));
			}
			else {
				alert($this->lang->line('permission_list'));
			}
		}
		
		// 사이드 뷰 사용
		if ($board['bo_use_sideview']) {
			$this->load->helper('sideview');
		}
		
		// 검색 세그먼트
		//$sca_str = ($sca) ? '/sca/'.$sca : '';

		$where = bo_list_where($board, IS_ADMIN);
		
		// 검색 파트 row
		$search_part = $this->config->item('cf_search_part');
		$btn_prev_part = $btn_next_part = '';
		
		// 공지 id
		$notice = $board['bo_notice'] ? explode(',', trim($board['bo_notice'])) : array();
		
		// 분류 선택, 검색어, 검색 파트 적용
		if($sca || ($sfl && $stx) || $board['bo_count_write'] > $search_part) {
			if($stx) {
				$stx = get_text(search_decode($stx));
			}

			$min_spt = $board['bo_min_wr_num'];
			if(!$spt)
				$spt = $min_spt;

			$total_count = $this->M_board->list_count(BO_DB, $spt, $sca, $sfl, $stx, $where);
			
			$prev_spt = $spt - $search_part;
			if($min_spt && $prev_spt >= $min_spt)
				$btn_prev_part = "<a href='".RT_PATH."/board/".BO_TABLE."/lists".qstr_rep($qstr, 'spt', $prev_spt)."'>이전검색</a>";

			$next_spt = $spt + $search_part;
			if($next_spt < 0) 
				$btn_next_part = "<a href='".RT_PATH."/board/".BO_TABLE."/lists".qstr_rep($qstr, 'spt', $next_spt)."'>다음검색</a>";
		}
		else if($board['bo_where']) {
			$total_count = $this->M_board->list_count(BO_DB, $spt, $sca, $sfl, $stx, $where);
		}
		else {
			$total_count = $board['bo_count_write'] - count($notice);
		}
		
		// 정렬
		if (!$sst) {
			if ($board['bo_sort_field'])
				$sst = $board['bo_sort_field'];
			else {
				$sst = 'wr_num, wr_reply';
				$sod = 'asc';
			}
		}
		else {
			$sst = preg_match("/^(wr_datetime|wr_hit)$/i", $sst) ? $sst : FALSE;
		}
		
		// 페이지
		if(!is_numeric($page) || $page < 1) {
			$page = 1;
		}
		
		// 페이징
		$config['suffix']			= $qstr;
		$config['base_url']			= RT_PATH.'/board/'.BO_TABLE.'/lists/page/';
		$config['per_page']			= $board['bo_page_rows'];
		$config['total_rows']		= $total_count;
        $config['uri_segment']		= $seg->get_order('page');
		
		$CI =& get_instance();
		$CI->load->library('pagination');
		$CI->pagination->initialize($config);
		$paging = $CI->pagination->create_links();
		
		$offset = ($page - 1) * $config['per_page'];
		
		// 리스트
		$result = $this->M_board->list_result(BO_DB, $spt, $sca, $sst, $sod, $sfl, $stx, $config['per_page'], $offset, '*', $where);
		
		// 일반 리스트
		$list = $list_nt = $wr_ids = array();
		foreach ($result as $i => $row) {
			//$row['img'] = $row['uf_count_image'] > 0 ? $this->M_board->getFiles($row['wr_id'], 1) : '';
			$row = get_convert($row, $board, BO_IMG_PATH, $board['bo_subject_len'], $qstr, TRUE);
			
			$list[$i] = $row;
			$list[$i]['num'] = $total_count - ($page - 1) * $config['per_page'] - $i;
			$list[$i]['href'] = ($member['mb_level'] >= $board['bo_read_level']) ? $row['href'] : 'javascript:boExec(0,0,1,2,0);';
			$list[$i]['subject'] = (strpos($sfl, 'subject')) ? search_font($row['subject'], $stx) : $row['subject'];
			$list[$i]['date'] = substr($row['datetime'], 0, 10);

			$wr_ids[$row['wr_id']] = $i;
		}

		// Extra
		if ($board['bo_use_extra'] && $wr_ids) {
			$result = $this->M_board->get_extra(BO_DB, array_keys($wr_ids));
			foreach ($result as $wr_id => $row) {
				$i = $wr_ids[$wr_id];
				foreach ($row as $fld => $val) {
					$list[$i][$fld] = $val;
				}
			}
		}
		
		// 관련글
		if ($board['bo_use_postlink'] && $wr_ids) {
			$postlink = $this->M_postlink->list_result(BO_DB, array_keys($wr_ids));
			$list = $this->M_postlink->list_merge($list, $postlink);
		}
		
		// 공지사항 리스트
		if (!$sca && !$stx) {
			if ($board['bo_notice']) {
				$result = $this->M_board->list_select(BO_DB, $notice);
				
				foreach ($result as $i => $row) {
					$row = get_convert($row, $board, BO_IMG_PATH, $board['bo_subject_len'], $qstr, TRUE);
					
					$list_nt[$i] = $row;
					$list_nt[$i]['href'] = ($member['mb_level'] >= $board['bo_read_level']) ? $row['href'] : 'javascript:read_permission();';
					$list_nt[$i]['comment_cnt'] = $row['comment_cnt'];
				}
			}
		}
		
		$button = array_false(array('list', 'write', 'rss', 'chkbox'));
		
		// 리스트 버튼
		if($sfl && $stx) {
			$button['list'] = "<a href='javascript:;' onclick='boExec(\"".RT_PATH."/board/".BO_TABLE."/lists".$dqstr."\",\"list\",".$member['mb_level'].",".$board['bo_list_level'].",\"".IS_ADMIN."\");'><img src='".BO_IMG_PATH."/btn_list.gif' title='목록' alt='목록'/></a>";
		}

		// 글쓰기 버튼
        $button['write'] = "";
        if($member['mb_level'] >= $board['bo_write_level']) {
			$button['write'] = "<a href='javascript:;' onclick='boExec(\"".RT_PATH."/board/".BO_TABLE."/write".$qstr."\",\"write\",".$member['mb_level'].",".$board['bo_write_level'].",\"".IS_ADMIN."\");'><img src='".BO_IMG_PATH."/btn_write.gif' title='글쓰기' alt='글쓰기'/></a>";
        }

		// RSS 버튼
        if($board['bo_use_rss']) {
			$button['rss'] = "<a href='".RT_PATH."/board/".BO_TABLE."/rss' target='_blank' class='lh0'><img src='".BO_IMG_PATH."/ico_rss.gif' title='RSS' alt='RSS'/></a>";
        }

		// 관리자 버튼
		if(SU_ADMIN) {
			$button['admin'] = "<a href='".RT_PATH.'/'.ADM_F.'/board/form/u/'.BO_TABLE."' target='_blank'><img src='".BO_IMG_PATH."/btn_admin.gif' title='관리자' alt='관리자'/></a>";
		}

		// 관리자 체크박스 및 버튼 표시
		if(IS_ADMIN) {
			$button['chkbox'] = "<a href=\"javascript:select_delete('$qstr');\" class='lh0'><img src='".BO_IMG_PATH."/btn_select_delete.gif' title='선택삭제' alt='선택삭제'/></a> ";
			if (SU_ADMIN || IS_ADMIN == 'group') {
				$button['chkbox'] .= "<a href=\"javascript:select_copy('copy','$qstr');\" class='lh0'><img src='".BO_IMG_PATH."/btn_select_copy.gif' title='선택복사' alt='선택복사'/></a> ";
				$button['chkbox'] .= "<a href=\"javascript:select_copy('move','$qstr');\" class='lh0'><img src='".BO_IMG_PATH."/btn_select_move.gif' title='선택이동' alt='선택이동'/></a> ";
			}
		}
		
		$board['bo_title_img'] = $board['bo_title_img'] ? '<img src="'.$board['bo_title_img'].'" />' : '';
		
		// 정렬 링크
		$vars = array(
			'_TITLE_'		=> $board['gr_subject'].' &gt; '.$board['bo_subject'],
			'_BODY_'		=> 'board/'.$board['bo_skin'].'/list',
			'_CSS_'			=> array('board'),
			'_JS_'			=> array('board', 'category'),
			
			'total_count'	=> $total_count,
			
			'button'		=> $button,
			
			'search'		=> array('sca' => $sca, 'sfl' => $sfl, 'stx' => $stx),
			'list'			=> $list,
			'list_nt'		=> $list_nt,
			'qstr'			=> $qstr,
			'paging'		=> $paging,
			
			'sort_date'		=> sort_link('wr_datetime', 'desc'),
			'sort_hit'		=> sort_link('wr_hit', 'desc')
		);
		
		if($view) {
			$this->load->view('board/'.$board['bo_skin'].'/list', $vars);
		}
		else {
			if (IS_ADMIN) $vars['_JS_'][] = 'board_check';
			if ($board['bo_use_sideview']) $vars['_JS_'] = 'sideview';
			
			$this->load->view(null, $vars);
		}
	}
}
?>
