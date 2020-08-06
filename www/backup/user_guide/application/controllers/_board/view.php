<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class View extends Widget {
	function index() {
		$board  =& $this->board;
		$member =& $this->member;
		$seg    =& $this->seg;
		
		$wr_id = $seg->get_seg('wr_id'); // 게시물아이디
		$stx   = $seg->get_seg('stx');   // 검색어
		$sfl   = $seg->get_seg('sfl');   // 검색필드
		$sca   = $seg->get_seg('sca');   // 카테고리
		$qstr  = $seg->get_qstr();       // 쿼리스트링
		$dqstr = qstr_rep($qstr, 'wr_id');

		if ($wr_id) {
			$write =& $this->write;
			
			if (!isset($write['wr_id']))
				alert('글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동된 경우입니다.', 'board/'.BO_TABLE);
				
			if ($write['wr_is_comment'])
				goto_url('board/'.BO_TABLE.'/view/wr_id/'.$write['wr_parent'].'#c_'.$wr_id);

			// 로그인된 회원의 권한이 설정된 읽기 권한보다 작다면
			if ($member['mb_level'] < $board['bo_read_level']) {
				if (IS_MEMBER)
					alert('글을 읽을 권한이 없습니다.');
				else 
					alert('글을 읽을 권한이 없습니다.\\n\\n회원이라면 로그인 후 이용하세요.', 'member/login/qry/'.url_encode('board/'.BO_TABLE.'/view'.$qstr));
			}

			// 자신의 글 and 관리자가 아니라면 비밀글 체크
			if (!(IS_MEMBER && $write['mb_id'] && $write['mb_id'] == $member['mb_id']) && !IS_ADMIN) {
				if (strpos($write['wr_option'], 'secret') !== FALSE) {

					$is_owner = FALSE;
					if ($write['wr_reply'] && IS_MEMBER) {
						// 자신의 비밀글의 답변이라면 통과
						$row = $this->M_board->is_owner(BO_DB, $write['wr_num']);

						if ($row['mb_id'] == $member['mb_id'])
							$is_owner = TRUE;
					}

					$ss_name = 'ss_secret_'.BO_TABLE.'_'.$write['wr_num'];

					if (!$is_owner) {
						// 한번 읽은 게시물의 번호는 세션에 저장되어 있고 같은 게시물을 읽을 경우는 다시 비밀번호를 묻지 않습니다.
						// 이 게시물이 저장된 게시물이 아니면서 관리자가 아니라면
						if (!$this->session->userdata($ss_name))
							goto_url('board/'.BO_TABLE.'/password/w/s/wr_id/'.$wr_id.$dqstr);
					}

					$this->session->set_userdata($ss_name, TRUE);
				}
			}

			// 한번 읽은글은 브라우저를 닫기전까지는 카운트를 증가시키지 않음
			$ss_name = 'ss_view_'.BO_TABLE.'_'.$wr_id;
			if (!$this->session->userdata($ss_name)) {
				$this->M_board->hit_update(BO_TABLE, $wr_id);
				$this->session->set_userdata($ss_name, TRUE);
			}
		}
		else
			goto_url('board/'.BO_TABLE);

		// IP 표시
		$is_ip_view = $board['bo_use_ip_view'];
		if (IS_ADMIN) {
			$is_ip_view = TRUE;
			$ip = $write['wr_ip'];
		}
		else // 관리자가 아니라면 IP 주소를 감춘후 보여줍니다.
			$ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.♡.\\3.\\4", $write['wr_ip']);

		if ($stx)
			$stx = get_text(search_decode($stx));
		
		$button = array_false(array('copy', 'move', 'list', 'update', 'delete', 'reply', 'write'));
		
		// 최고, 그룹관리자라면 글 복사, 이동 버튼
		if ($write['wr_reply'] == '' && (IS_ADMIN == 'super' || IS_ADMIN == 'group')) {
            $start = "post_win('mvcp', '_board/movecopy', {'is_admin':'".IS_ADMIN."','bid':'".BO_TABLE."','wr_id':'".$wr_id."','sw':'";
            $end = "'}, 'left=50, top=50, width=500, height=550, scrollbars=1');";
            $button['copy'] = "<a href='javascript:;' onclick=\"".$start."copy".$end."\"><img src='".BO_IMG_PATH."/btn_copy.gif' title='복사' alt='복사'/></a> ";
            $button['move'] = "<a href='javascript:;' onclick=\"".$start."move".$end."\"><img src='".BO_IMG_PATH."/btn_move.gif' title='이동' alt='이동'/></a> ";
		}
		
		// 목록 버튼
		$button['list'] = "<a href='javascript:;' onclick='boExec(\"".RT_PATH."/board/".BO_TABLE."/lists".$dqstr."\",\"list\",".$member['mb_level'].",".$board['bo_list_level'].",\"".IS_ADMIN."\");'><img src='".BO_IMG_PATH."/btn_list.gif' title='목록' alt='목록'/></a> ";
		
		// 글쓰기 버튼
        $button['write'] = $button['reply'] = "";
		if ($member['mb_level'] >= $board['bo_write_level']) {
			$button['write'] = "<a href='javascript:;' onclick='boExec(\"".RT_PATH."/board/".BO_TABLE."/write".$dqstr."\",\"write\",".$member['mb_level'].",".$board['bo_write_level'].",\"".IS_ADMIN."\");'><img src='".BO_IMG_PATH."/btn_write.gif' title='글쓰기' alt='글쓰기'/></a> ";
		}

		// 답변 버튼
		if ($member['mb_level'] >= $board['bo_reply_level']) {
			$button['reply'] = "<a href='javascript:;' onclick='boExec(\"".RT_PATH."/board/".BO_TABLE."/write/w/r".$qstr."\",\"reply\",".$member['mb_level'].",".$board['bo_reply_level'].",\"".IS_ADMIN."\");'><img src='".BO_IMG_PATH."/btn_reply.gif' title='답변' alt='답변'/></a> ";
		}
		
		// 수정 & 삭제 버튼 (회원 자신글 or 관리자는 비번 확인 패스)
		if ((IS_MEMBER && $member['mb_id'] == $write['mb_id']) || IS_ADMIN) {
			$button['update'] = "<a href='".RT_PATH."/board/".BO_TABLE."/write/w/u".$qstr."'><img src='".BO_IMG_PATH."/btn_modify.gif' title='수정' alt='수정'/></a> ";
			$button['delete'] = "<a href='javascript:;' onclick=\"javascript:post_s('_board/record_write/delete', {bid:'".BO_TABLE."', wr_id:'".$wr_id."', is_admin:'".IS_ADMIN."', qstr:'".$dqstr."'}, true);\"><img src='".BO_IMG_PATH."/btn_delete.gif' title='삭제' alt='삭제'/></a> ";
		}
		else if (!$write['mb_id']) { // 회원이 쓴 글이 아니라면
			$button['update'] = "<a href='".RT_PATH."/board/".BO_TABLE."/password/w/u".$qstr."'><img src='".BO_IMG_PATH."/btn_modify.gif' title='수정' alt='수정'/></a> ";
			$button['delete'] = "<a href='".RT_PATH."/board/".BO_TABLE."/password/w/d".$qstr."'><img src='".BO_IMG_PATH."/btn_delete.gif' title='삭제' alt='삭제'/></a> ";
		}

		$wr_prev = $wr_next = array();
		if (!$board['bo_use_list_view']) {
			$pn = $this->M_board->prev_next_link(BO_TABLE, $write['wr_num'], $write['wr_reply'], $sca, $sfl, $stx, $board['bo_notice']);
			
			// 이전글 링크
			$prev = $pn['prev'];
			if ($prev['wr_id']) {
				$prev_wr_subject = cut_str(get_text($prev['wr_subject']), 255);
				$wr_prev['href'] = RT_PATH."/board/".BO_TABLE."/view".qstr_rep($qstr, 'wr_id', $prev['wr_id']);
				$wr_prev['subject'] = $prev_wr_subject;
			}
			
			// 다음글 링크
			$next = $pn['next'];
			if ($next['wr_id']) {
				$next_wr_subject = cut_str(get_text($next['wr_subject']), 255);
				$wr_next['href'] = RT_PATH."/board/".BO_TABLE."/view".qstr_rep($qstr, 'wr_id', $next['wr_id']);
				$wr_next['subject'] = $next_wr_subject;
			}
		}
				
		// 사이드 뷰
		if ($board['bo_use_sideview'])
			$this->load->helper('sideview');

		// 전체목록보이기
		$list_view = FALSE;
		if ($member['mb_level'] >= $board['bo_list_level'] && $board['bo_use_list_view'])
			$list_view = TRUE;

		// 가공
		$view = get_convert($write, $board, BO_IMG_PATH, 255, $qstr);

		if (strpos($sfl, 'subject'))
			$view['subject'] = search_font($view['subject'], $stx);

		// 이미지 리사이즈
		if ($write['uf_count_image'] > 0) {
			define('RESIZE_WIDTH', $board['bo_image_width']);
            $this->load->helper('resize');
			$view['wr_content'] = resize_content($view['wr_content']);
        }

		$is_editor = (strpos($view['wr_option'], 'editor') !== FALSE) ? TRUE : FALSE;
		
		$view['content'] = conv_content($view['wr_content'], $is_editor);
		if (strpos($sfl, 'content'))
			$view['content'] = search_font($view['content'], $stx);

		// 첨부파일
		$files = $videoSrc = array();
		$this->load->model('M_upload_files');
		$result = $this->M_upload_files->get_files(BO_DB, $wr_id, 'uf_no,uf_source,uf_file,uf_type');
		foreach ($result as $row) {
			$fileDirs = $this->M_upload_files->get_dir(BO_DB);
			$files[$row['uf_no']]['size'] = @getimagesize($fileDirs['data_url'].'/'.$row['uf_file']);
			$files[$row['uf_no']]['url'] = $fileDirs['data_url'].'/'.$row['uf_file'];
			$files[$row['uf_no']]['href'] = '/board/'.BO_TABLE.'/download/wr_id/'.$wr_id.'/no/'.$row['uf_no'];
			$files[$row['uf_no']]['filename'] = $row['uf_source'];
			
			$fileType = strtolower(substr($row['uf_source'], -3, 3));
			if($fileType=='asf' || $fileType=='mov' || $fileType=='avi' || $fileType=='mp4') {
				$videoSrc[] = $this->config->item('base_url').'/data/file/'.BO_TABLE.'/'.$row['uf_file'];
			}
		}
		
		
		//<!-- 코멘트 시작 -->//
		$view_comment = '';
		if ($board['bo_use_comment'] && strpos($write['wr_option'], 'nocomt') === FALSE) {
			$is_comment_write = FALSE;
			if ($member['mb_level'] >= $board['bo_comment_level'])
				$is_comment_write = TRUE;

			$result = $this->M_board->list_comment(BO_DB, $wr_id);

			$list = array();
			foreach ($result as $i => $row) {
				// 코멘트ID 약어
				$list[$i]->comment_id = $row['wr_id'];
				$list[$i]->wr_comment_reply = $row['wr_comment_reply'];
				// 답변 깊이;
				$list[$i]->wr_coreply_len = strlen($row['wr_comment_reply']) * 20;

				$tmp_name = cut_str(get_text($row['wr_name']), 30);
				
				$list[$i]->name = $board['bo_use_sideview'] ? get_sideview($row['mb_id'], $tmp_name) : "<span class='".($row['mb_id']?'member':'guest')."'>".$tmp_name."</span>";

				if (strpos($row['wr_option'], 'secret') === FALSE || IS_ADMIN ||
					(IS_MEMBER && $write['mb_id'] == $member['mb_id']) ||
					(IS_MEMBER && $row['mb_id'] == $member['mb_id'])) {
					$list[$i]->secret = strpos($row['wr_option'], 'secret');
					$list[$i]->content_s = get_text($row['wr_content']);

					$content = conv_content($row['wr_content'], FALSE);
					$list[$i]->content = (strpos($sfl, 'content')) ? search_font($content, $stx) : $content;	
				}
				else if (strpos($row['wr_option'], 'secret') !== FALSE) {
					$list[$i]->secret = TRUE;
					$list[$i]->content = "<span style='color:#ff6600;'>* 비밀글 입니다.</span>";
					$list[$i]->content_s = '비밀글 입니다.';
				}

				$list[$i]->datetime = preg_replace('/\-/','.',$row['wr_datetime']);

				// IP 표시
				$list[$i]->ip = '';
				if (IS_ADMIN)
					$list[$i]->ip = $row['wr_ip'];
				else if ($is_ip_view && !IS_ADMIN)
					$list[$i]->ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.♡.\\3.\\4", $row['wr_ip']);
					
				$list[$i]->is_reply = $list[$i]->is_edit = $list[$i]->is_del = FALSE;
				if ($is_comment_write || IS_ADMIN) {
					if (IS_MEMBER) {
						if ($row['mb_id'] == $member['mb_id'] || IS_ADMIN) {
							$list[$i]->del_link = "<a href='javascript:;' onclick=\"javascript:post_s('_board/record_comment/delete', {bid:'".BO_TABLE."', comment_id:'".$row['wr_id']."', is_admin:'".IS_ADMIN."', qstr:'".$qstr."'}, true)\">";
							$list[$i]->is_edit = TRUE;
							$list[$i]->is_del = TRUE;
						}
					}
					else {
						if (!$row['mb_id']) {
							$list[$i]->del_link = "<a href=\"javascript:del('board/".BO_TABLE."/password/w/x/comment_id/".$row['wr_id'].$qstr."')\">";
							$list[$i]->is_del = TRUE;
						}
					}

					if (strlen($row['wr_comment_reply']) < 10)
						$list[$i]->is_reply = TRUE;
				}

				// 답변있는 코멘트는 수정, 삭제 불가
				if ($i > 0 && !IS_ADMIN) {
					if ($row['wr_comment_reply']) {
						$tmp_comment_reply = substr($row['wr_comment_reply'], 0, strlen($row['wr_comment_reply']) - 1);
						if ($tmp_comment_reply == $be_comment_reply) {
							$list[$i-1]->is_edit = FALSE;
							$list[$i-1]->is_del = FALSE;
						}
					}
				}
				// 전 코멘트
				$be_comment_reply = $row['wr_comment_reply'];

				if ($list[$i]->is_reply) $list[$i]->is_reply = "<a href=\"javascript:comment_box('".$list[$i]->comment_id."', 'c');\"><img src='".BO_IMG_PATH."/co_btn_reply.gif' title='답변' alt='답변'/></a> ";
				if ($list[$i]->is_edit) $list[$i]->is_edit = "<a href=\"javascript:comment_box('".$list[$i]->comment_id."', 'cu');\"><img src='".BO_IMG_PATH."/co_btn_modify.gif' title='수정' alt='수정'/></a> ";
				if ($list[$i]->is_del) $list[$i]->is_del = $list[$i]->del_link."<img src='".BO_IMG_PATH."/co_btn_delete.gif' title='삭제' alt='삭제'/></a> ";
			}
			
			$data = array(
				'list' => $list,
				'wr_id' => $wr_id,
				'qstr' => $qstr,
				'is_comment_write' => $is_comment_write
			);

			$view_comment = $this->load->view('board/'.$board['bo_skin'].'/view_comment', $data, TRUE);
		}
		//<-- 코멘트 끝 !-->//
		
		$board['bo_title_img'] = $board['bo_title_img'] ? '<img src="'.$board['bo_title_img'].'" />' : '';
		
		$vars = array(
			'_TITLE_'		=> $board['gr_subject'].' &gt; '.$board['bo_subject'].' &gt; '.strip_tags($view['subject']),
			'_BODY_'		=> 'board/'.$board['bo_skin'].'/view',
			'_CSS_'			=> array('board'),
			'_JS_'			=> array('board'),
			
			// 한글 깨짐시 cut_hangul_last 채용
			'pTitle'		=> ($board['bo_show_gr']?$board['gr_subject'].' &gt; ':'').'<strong>'.$board['bo_subject'].'</strong>',
			'view'			=> $view,

			'ip'			=> ($is_ip_view) ? '('.$ip.')' : '',
			'files'			=> $files,
			'videoSrc'		=> $videoSrc,
				
			'wr_next'		=> $wr_next,
			'wr_prev'		=> $wr_prev,
			'button'		=> $button,
			
			'view_comment'	=> $view_comment,
			'btn_sns'		=> ($board['bo_use_sns']) ? sns_post(BO_TABLE, $wr_id, $view['subject'], $view['content']) : ''
		);
		
		// Extra
		if ($board['bo_use_extra'])
			$vars = array_merge($vars, $this->M_board->get_extra(BO_DB, $wr_id));
		
		if ($board['bo_use_sideview']) $vars['_JS_'][] = 'sideview';
		
		if ($list_view)
			if (IS_ADMIN) $vars['_JS_'][] = 'board_check';
		
		$this->load->view(null, $vars);
	}
}
?>
