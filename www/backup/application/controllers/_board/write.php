<?php if( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Write extends Widget {
	function index() {
		$board  =& $this->board;
		$member =& $this->member;
		$write  =& $this->write;
		$seg    =& $this->seg;
		
		$w     = $seg->get_seg('w');	 // 모드
		$wr_id = $seg->get_seg('wr_id'); // 게시물아이디
		$qstr  = $seg->get_qstr();		 // 쿼리스트링
				
		// I will be back.
		$return_url = url_encode('board/'.BO_TABLE.'/write'.$qstr);
		// 공지사항
		$notice_array = explode(',', trim($board['bo_notice']));
        
        if($w == 'u' || $w == 'r') {
	 		if(!isset($write['wr_id']))
	 			alert("글이 존재하지 않습니다.\\n\\n삭제되었거나 이동된 경우입니다.", 'board/'.BO_TABLE.'/lists');
	 	}
		
		if($w == '') {
		    if($wr_id)
		        alert('글쓰기에는 wr_id 값을 사용하지 않습니다.', 'board/'.BO_TABLE);
		
		    if($member['mb_level'] < $board['bo_write_level']) {
		        if(IS_MEMBER)
		            alert('글을 쓸 권한이 없습니다.');
		        else
		            alert("글을 쓸 권한이 없습니다.\\n\\n회원이라면 로그인 후 이용하세요.", "member/login/qry/".$return_url);
		    }
		
		    $title_msg = '글쓰기';
		}
		else if($w == 'u') {
			if(IS_MEMBER && $write['mb_id'] == $member['mb_id']) {
				// 자신의 글이면 통과
		    }
			else if($member['mb_level'] < $board['bo_write_level']) {
		        if(IS_MEMBER)
		            alert('글을 수정할 권한이 없습니다.');
		        else
		            alert("글을 수정할 권한이 없습니다.\\n\\n회원이라면 로그인 후 이용하세요.", "member/login/qry/".$return_url);
		    }

			// 수정 권한 IF
			if(IS_ADMIN == 'group' || IS_ADMIN == 'board') {
				$mb = $this->M_basic->get_member($write['mb_id'], 'mb_level');
				$mb_level = (isset($mb['mb_level'])) ? $mb['mb_level'] : 1;
			}
			
			if(IS_ADMIN == 'super') {
				// 통과
			}
			else if(IS_ADMIN == 'group') { // 그룹관리자
				if($member['mb_id'] == $board['gr_admin']) { // 자신이 관리하는 그룹인가
					if($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
						alert('그룹관리자의 권한보다 높은 회원의 글이므로 수정할 수 없습니다.');
				}
				else
					alert('자신이 관리하는 그룹의 게시판이 아니므로 글을 수정할 수 없습니다.');
			}
			else if(IS_ADMIN == 'board') { // 게시판관리자
				if($member['mb_id'] == $board['bo_admin']) { // 자신이 관리하는 게시판인가
					if($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
						alert('게시판관리자의 권한보다 높은 회원의 글이므로 수정할 수 없습니다.');
				}
				else
					alert('자신이 관리하는 게시판이 아니므로 글을 수정할 수 없습니다.');
			}
			else {
				if($write['mb_id']) { 
					if(!IS_MEMBER || $member['mb_id'] != $write['mb_id'])
						alert('자신의 글이 아니므로 수정할 수 없습니다.');
				}
				else {
					$CI =& get_instance();
					$CI->load->library('encrypt');
		        	if(md5($this->input->post('wr_password')) !== $CI->encrypt->decode($write['wr_password']))
						alert('비밀번호가 맞지 않습니다.');	
				}
			}
		
		    // 원글만 구한다.
		    $cnt = $this->M_board->is_reply(BO_DB, $wr_id, $write['wr_num'], $write['wr_reply']);
		    if($cnt && !IS_ADMIN)
		        alert("이 글과 관련된 답변글이 존재하므로 수정할 수 없습니다.\\n\\n답변글이 있는 원글은 수정할 수 없습니다.");
		
		    // 코멘트 달린 원글의 수정 여부
		    if($board['bo_count_modify'] > 0) {
			    $cnt = $this->M_board->is_comment(BO_DB, $wr_id, (IS_MEMBER) ? $member['mb_id'] : '');
			    if($cnt >= $board['bo_count_modify'] && !IS_ADMIN)
			        alert("이 글과 관련된 코멘트가 존재하므로 수정할 수 없습니다.\\n\\n코멘트가 ".$board['bo_count_modify']."건 이상 달린 원글은 수정할 수 없습니다.");
      		}
      		
			$title_msg = '글수정';
		}
		else if($w == 'r') {
		    if($member['mb_level'] < $board['bo_reply_level']) {
		        if(IS_MEMBER)
		            alert('글을 답변할 권한이 없습니다.');
		        else
		            alert("글을 답변할 권한이 없습니다.\\n\\n회원이라면 로그인 후 이용하세요.", "member/login/qry/".$return_url);
		    }
			
		    if(in_array((int)$wr_id, $notice_array))
		        alert('공지에는 답변 할 수 없습니다.');
		
		    // 코멘트에는 원글의 답변이 불가하므로
		    if($write['wr_is_comment'])
		        alert('정상적인 접근이 아닙니다.');

		    /*
		    // 비밀글인지를 검사
		    if(strpos($write['wr_option'], 'secret') !== FALSE) {
		        if($write['mb_id']) {
		            // 회원의 경우는 해당 글쓴 회원 및 관리자
		            if(!($write['mb_id'] == $member['mb_id'] || IS_ADMIN))
		                alert('비밀글에는 자신 또는 관리자만 답변이 가능합니다.');
		        }
				else {
		            // 비회원의 경우는 비밀글에 답변이 불가함
		            if(!IS_ADMIN)
		                alert('비회원의 비밀글에는 답변이 불가합니다.');
		        }
		    }
		    */
			
		    // 최대 답변은 테이블에 잡아놓은 wr_reply 사이즈만큼만 가능합니다.
		    if(strlen($write['wr_reply']) == 10)
		        alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 10단계 까지만 가능합니다.");
		
			$reply = $this->M_board->get_reply_step(BO_TABLE, $write['wr_num'], $board['bo_reply_order'], $write['wr_reply']);
			
		    $title_msg = '글답변';
		}
		else
		    alert('잘못된 접근입니다.');
		
		$is_notice = $notice_checked = $is_nocomt = FALSE;
        if(IS_ADMIN) {
			if($board['bo_use_comment'])
				$is_nocomt = TRUE;

            if($w != 'r') {
    		    $is_notice = TRUE;
                if($w == 'u') {
    		        // 답변 수정시 공지 체크 없음
    		        if($write['wr_reply'])
    		            $is_notice = FALSE;
    		        else
    		            $notice_checked = (in_array((int)$wr_id, $notice_array)) ? "checked='checked'" : '';
    		    }
            }
		}
		
		$is_tag			=	$board['bo_use_tag'];
		$is_postlink	=	$board['bo_use_postlink'];
		$is_secret		=	$board['bo_use_secret'];
		$is_editor		=	($board['bo_use_editor']) ? TRUE : FALSE;
		$is_email		=	($this->config->item('cf_use_email') && $board['bo_use_email'] && $this->config->item('cf_email_wr_write')) ? TRUE : FALSE;
		$is_sign		=	(!IS_MEMBER || (IS_ADMIN && $w == 'u' && $member['mb_id'] != $write['mb_id'])) ? TRUE : FALSE;
		
		$name = $email = $secret_checked = '';
		if($w == '' || $w == 'r') {
			if(IS_MEMBER) {
		        $name = cut_str(get_text($member['mb_name']), 20);
		        $email = $member['mb_email'];
	        }
	        
	        if($w == 'r' && strpos($write['wr_option'], 'secret') !== FALSE) {
		        $is_secret = TRUE;
		        $secret_checked = "checked='checked'";
		    }
		}
		else if($w == 'u') {
			$name = cut_str(get_text($write['wr_name']), 20);
		    $email = $write['wr_email'];
		    
		    if(strpos($write['wr_option'], 'secret') !== FALSE)
		        $secret_checked = "checked='checked'";
		}
		
		// 옵션 박스
		$option = $option_hidden = '';
	    if($is_notice) $option .= "<input type='checkbox' name='notice' ".$notice_checked." value='1'/> 공지&nbsp;";
		if($is_editor) $option_hidden .= "<input type='hidden' name='editor' value='editor'/>";
		if($is_secret) {
	        if(IS_ADMIN || $is_secret == 1)
	            $option .= "<input type='checkbox' name='secret' ".$secret_checked." value='secret'/> 비밀글&nbsp;";
			else
	            $option_hidden .= "<input type='hidden' name='secret' value='secret'/>";
	    }
	    if($is_email) {
	    	// 답변메일받기
			$recv_email_checked = ($w == 'u' && strpos($write['wr_option'], 'mail') !== FALSE) ? "checked='checked'" : '';
	        $option .= "<input type='checkbox' id='mail' name='mail' ".$recv_email_checked." value='mail'/> 답변메일받기&nbsp;";
     	}
        if($is_nocomt) {
            $nocomt_checked = (strpos($write['wr_option'], 'nocomt') !== FALSE) ? "checked='checked'" : '';	    
	        $option .= "<input type='checkbox' name='nocomt' ".$nocomt_checked." value='nocomt'/> 댓글금지&nbsp;";
        }
        
        // 관련글 HTML
        $postlink = '';
        if($is_postlink) {
        	$postlink_script = '';
        	if(is_array($this->postlink)) {
        		$board_names = array();
        		foreach($this->board_list AS $bo) {
        			$board_names[$bo['bid']] = $bo['bo_subject'];
        		}
        		foreach($this->postlink AS $postlink_row) {
        			if($board_names[$postlink_row['pl_link_table']]) {
	        			$json_data = json_encode(array('table' => $postlink_row['pl_link_table'], 'idx' => $postlink_row['pl_link_id']));
	        			$postlink_script .= "ret_postlink('".$board_names[$postlink_row['pl_link_table']]."','".$postlink_row['wr_subject']."','".$json_data."');";
        			}
        		}
        	}
        	
        	$postlink =	'<div id="post_link"><ul id="postlink_list"></ul><input type="button" class="btn_simp" onclick="add_postlink();" value=" + 추가 "/></div>'."\n".
        				'<script type="text/javascript">'.$postlink_script.'</script>';
        }
        
        // 태그 HTML
        $tag = '';
        if($is_tag) {
        	$tag =	'<input type="text" id="wr_tag" class="ed" name="wr_tag" value="'.$write['wr_tag'].'"/>'."\n";
        }
        
        // 제목
		$subject = cut_str(get_text($write['wr_subject']), 255);
		
		// 내용
		if($w == '')
		    $content = $board['bo_insert_content'];
		else if($w == 'r') {
			$subject = '';
			$content = $board['bo_insert_content'];
		}
		else if($is_editor)
			$content = $write['wr_content'];
		else
		    $content = get_text($write['wr_content']);
     	
     	// 에디터
        $editor = '';
        if($is_editor) {
            // 에디터 정보 수집
			if($w == 'u') {
				$CI =& get_instance();
				$CI->load->model('M_editor');
				$edt_info = $CI->M_editor->get_info(BO_DB, $wr_id);
				$edt_info = json_encode($edt_info);
			}
			// 에디터 매개변수
            $edt = array(
                'content'	=> $content,
				'edt_info' => isset($edt_info) ? $edt_info : '[]',
				'wr_table' => BO_TABLE,
				'upload_size' => $board['bo_upload_size'] * 1048576
            );
			$edt['buttons']['gallery'] = $board['bo_use_edt_img'] ? 1 : 0;
			$edt['buttons']['file'] = $board['bo_use_edt_file'] ? 1 : 0;
			$edt['buttons']['outcont'] = $board['bo_use_edt_ocon'] ? 1 : 0;
            $editor = $this->load->view('editor/editor', $edt, TRUE);
			$content = ''; // 그냥 비우기
        }
		
        $board['bo_title_img'] = $board['bo_title_img'] ? '<img src="'.$board['bo_title_img'].'" />' : '';
        
		// 폼 업로드 파일
		$this->load->model('M_upload_files');
		$upfile = $this->M_upload_files->get_files(BO_DB, $wr_id, '*');
		
		$vars = array(
			'_TITLE_'		=> $board['gr_subject'].' > '.$board['bo_subject'].' > '.$title_msg,
			'_BODY_'		=> 'board/'.$board['bo_skin'].'/write',
			'_CSS_'			=> array('board', 'editor'),
			'_JS_'			=> array('board', 'category', 'jvalidate', 'md5', 'kcaptcha'),
			
			'title_msg'		=> $title_msg,
			'w'				=> $w,
			
			'mb_id'			=> (!$w && IS_MEMBER) ? $member['mb_id'] : 'guest',
			'name'			=> $name,
			'email'			=> $email,
			'wr_parent'		=> $write['wr_parent'],
			'postlink'		=> $postlink,
			'tag'			=> $tag,
				
			'subject'		=> $subject,
			'content'		=> $content,
			'editor'		=> $editor,
			'upfile'		=> isset($upfile)?$upfile:'',
			
			'option_hidden'	=> $option_hidden,
			'option'		=> $option,
			'qstr'			=> qstr_rep($qstr, 'w,wr_id'),
			
			'is_editor'		=> $is_editor,
			'is_sign'		=> $is_sign
		);
		if($is_editor)
			$vars['_JS_'] = array_merge($vars['_JS_'], array('../editor/js/editor_loader'));
		
		// Extra
		if($board['bo_use_extra']) {
			if($w == 'u') {
				$extra = $this->M_board->get_extra(BO_DB, $wr_id);
				foreach ($extra AS $fld => $val) {
					if($fld == 'wr_id')
						continue;
					$vars[$fld] = $val;
				}
			}
			else {
				$extra = $this->db->list_fields('ki_extra_'.BO_DB);
				foreach ($extra AS $fld) {
					if($fld == 'wr_id')
						continue;
					$vars[$fld] = FALSE;
				}
			}
		}
		
		$this->load->view(null, $vars);
	}
}
?>
