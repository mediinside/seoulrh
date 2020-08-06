<?php
class Record_write extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->helper('board');
		$this->load->model(array('M_board', 'M_mail', 'M_editor', 'M_upload_files'));
		$this->load->config('cf_board');
		$this->load->library(array('encrypt', 'form_validation'));
		
		define('IS_ADMIN', $this->input->post('is_admin'));
	}
	
	function update() {
		$w =			$this->input->post('w');
		$bid =			$this->input->post('bid');
		$wr_id =		$this->input->post('wr_id');
		$notice =		$this->input->post('notice');
		$wr_content =	$this->input->post('wr_content');
		$secret =		$this->input->post('secret');
		$editor =		$this->input->post('editor');
		
		// 스팸 필터링
		if( blockWord($this->config->item('cf_block_keyword'), $this->input->post('wr_subject')) ||
			blockWord($this->config->item('cf_block_keyword'), $this->input->post('wr_content')) ||
			blockWord($this->config->item('cf_block_ip'), $this->input->server('REMOTE_ADDR')))
				alert('광고성 문구가 포함되어 있거나 차단된 IP대역입니다.');
		
		// 회원 정보
		$member = unserialize(MEMBER);
		
		// 게시판 정보
		$board = $this->M_basic->get_board($bid);
		
		// 게시물 정보
		$write = $this->M_basic->get_write($board['bo_db'], $wr_id);
		
		// 작성자 정보 (회원 or 비회원)
		$writer = $this->writer_info($member, $w, $write, $board['bo_use_name']);
		
		// 게시판 공지
		$bo_notice_arr = explode(',', trim($board['bo_notice']));
		
		// 필수입력 설정
		$config = array(
			array('field'=>'bid', 'label'=>'게시판아이디', 'rules'=>'trim|required|alpha_dash'),
			array('field'=>'wr_subject', 'label'=>'제목', 'rules'=>'trim|required')
		);
		if(!IS_MEMBER) {
			$config[] = array('field'=>'wr_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[10]');
			$pass_req = ($w == 'u') ? '' : '|required';
			$config[] = array('field'=>'wr_password', 'label'=>'비밀번호', 'rules'=>'trim'.$pass_req.'|max_length[20]|md5');
		}
		if($board['bo_use_captcha']) {
			$config[] = array('field'=>'wr_key', 'label'=>'자동등록방지', 'rules'=>'trim|required');
		}
		$this->form_validation->set_rules($config);
		
		// 필수입력 확인
		if($this->form_validation->run() == FALSE)
			alert('올바르지 않은 접근입니다.');
		else {
			// Captcha 확인
			if(!IS_MEMBER && $board['bo_use_captcha']) {
				check_wrkey();
			}
			
			// 오류 체크
			$this->chk_error($member, $w, $board, $wr_id, $write, $bo_notice_arr, array('notice'=>$notice,'secret'=>$secret));
			
			if($w == '' || $w == 'r') {			
			    if($w == 'r') {
			        // 답변의 원글이 비밀글이라면 패스워드는 원글과 동일하게 넣는다.
			        if($secret) {
			            $writer['wr_password'] = $write['wr_password'];
			        }
			        $wr_num = $write['wr_num'];
			        $wr_reply = $this->M_board->get_reply_step($board['bo_db'], $write['wr_num'], $board['bo_reply_order'], $write['wr_reply']);
			    }
			    else {
			    	// 가장 작은 번호에 1을 빼서 넘겨줌
			        $wr_num = $this->M_board->get_min_num($board['bo_db']);
	    			$wr_num = (int)($wr_num - 1);
			        $wr_reply = '';
			    }
				
				// Insert
				$parent_id = isset($write['wr_id']) ? $write['wr_id'] : '';
				$wr_id = $this->M_board->write_insert($board['bo_db'], $wr_content, $wr_num, $wr_reply, $writer, $board['bo_notice'], $parent_id);
			}
			else if($w == 'u') {
				// 공지사항 체크
				if(IS_ADMIN) {
					if($notice) {
				        if(!in_array((int)$wr_id, $bo_notice_arr))
				            $board['bo_notice'] = $board['bo_notice'] ? $wr_id.','.$board['bo_notice'] : $wr_id;
				    }
				    else {
				        $nokey = array_search((int)$wr_id, $bo_notice_arr);
                        if(is_int($nokey)) {
                            unset($bo_notice_arr[(int)$nokey]);
                            $board['bo_notice'] = implode(',', $bo_notice_arr);
                        }
				    }
			    }
				
				// Update
				$this->M_board->write_update($board['bo_db'], $wr_content, $wr_id, $write['wr_num'], $writer, $board['bo_notice']);
			    
			    $wr_num = $write['wr_num']; // 비밀글 세션 저장에 필요
			}

			// Extra
			if ($board['bo_use_extra']) {
				$extra = $this->db->list_fields('ki_extra_'.$board['bo_db']);
				foreach($extra AS $fld) {
					$sql[$fld] = $this->input->post($fld);
				}
				
				if(!$w) {
					$sql['wr_id'] = $wr_id;
				}
			
				$this->M_board->extra_update($board['bo_db'], $wr_id, $sql);
			}
			
			// 에디터 파일 업로드
            if($editor) {
				// Files upload
				$wr_content = $this->M_editor->uploadFile($board['bo_db'], $wr_id, $wr_content);
				// 내용에서 첨부 파일 경로 수정
				$this->M_board->content_update($board['bo_db'], $wr_id, $wr_content);
			}
			
			// 폼 파일 업로드
			if(isset($_FILES['uf_file'])) {
				if(count($_FILES['uf_file']['tmp_name']) > 0) {
					$this->M_upload_files->form_upload($board['bo_db'], $wr_id, $_FILES['uf_file']);
				}
			}
			// 폼 파일 삭제
			if($del_no = $this->input->post('del_chk')) {
				foreach($del_no as $val) {
					$this->M_upload_files->file_delete($board['bo_db'], $wr_id, $val);
				}
			}
			
			// 비밀글이라면 세션에 비밀글의 아이디를 저장한다. 자신의 글은 다시 패스워드를 묻지 않기 위함
			if($secret)
			    $this->session->set_userdata('ss_secret_'.$board['bo_db'].'_'.$wr_num, TRUE);
			
			// 메일발송 사용 (수정글은 발송하지 않음)
			if($w != 'u' && $this->config->item('cf_use_email') && $board['bo_use_email']) {
			    // 관리자의 정보를 얻고
				//$super_admin = $this->M_basic->get_member(ADMIN, 'mb_email');
				$webmaster = $this->config->item('cf_webmaster');
				$group_admin = $this->M_basic->get_member($board['gr_admin'], 'mb_email');
				$board_admin = $this->M_basic->get_member($board['bo_admin'], 'mb_email');
				
				$this->load->helper('textual');
				$wr_subject = get_text(stripslashes($this->input->post('wr_subject')));			
			    $wr_content = conv_content(stripslashes($wr_content), $this->input->post('editor'));
				
			    $warr = array(''=>'입력', 'u'=>'수정', 'r'=>'답변');
			    $str = $warr[$w];
				
				$subject = "'".$board['bo_subject']."' 게시판에 ".$str."글이 올라왔습니다.";
			    $link_url = $this->config->item('base_url').'/board/'.$bid.'/view/wr_id/'.$wr_id;
			
			    $data = array(
					'wr_name' => $writer['wr_name'],
					'wr_email' => $writer['wr_email'],
					'wr_phone' => $this->input->post('ex_phone'),
					'wr_subject' => $wr_subject,
					'wr_content' => $wr_content,
					'link_url' => $link_url
				);
			    
			    $mail_skin = $this->M_mail->row_skin($board['bo_mail_skin']);
				$content = $this->load->view('mail/write_update', $data, TRUE);
				$content = preg_replace('({{\[_BODY_\]}})', $content, $mail_skin['ms_code']);
				
				$to_email = array();
				$this->load->library('email');

				$this->email->clear();
				$this->email->from(($writer['wr_email'] ? $writer['wr_email'] : $webmaster), $writer['wr_name']);
				
				// 게시판 관리자에게 보내는 메일
				if($this->config->item('cf_email_wr_board_admin') && $board_admin['mb_email']) {
					$to_email[] = $board_admin['mb_email'];
				}
				
				// 그룹 관리자에게 보내는 메일
				if($this->config->item('cf_email_wr_group_admin') && $group_admin['mb_email']) {
					if($group_admin['mb_email'] != $board_admin['mb_email']) {
						$to_email[] = $group_admin['mb_email'];
					}
				}
				
				// 웹마스터에게 보내는 메일
				if($this->config->item('cf_email_wr_webmaster') && $webmaster) {
					if($webmaster != $board_admin['mb_email'] && $webmaster != $group_admin['mb_email']) {
						$to_email[] = $webmaster;
					}
				}
				
				// 답변글에만 원게시자가 있음
				// 답변 메일받기 (원게시자에게 보내는 메일)
				if($w == 'r' && strpos($write['wr_option'], 'mail') !== FALSE && $write['wr_email'] && $write['wr_email'] != $writer['wr_email']) {
					if($this->config->item('cf_email_wr_write'))
						$to_email[] = $write['wr_email'];
			    }
			    
			    $this->email->to($to_email);
				$this->email->subject($subject);
				$this->email->message($content);
				$this->email->send();
			}

            $this->db->cache_delete('default', 'index');

			goto_url('board/'.$bid.'/view/wr_id/'.$wr_id.$this->input->post('qstr'));
		}
	}
	
	function delete() {
		$bid = $this->input->post('bid');
		$wr_id = $this->input->post('wr_id');

		$member = unserialize(MEMBER);
		$board = $this->M_basic->get_board($bid, 'bid, bo_db, bo_admin, bo_count_delete, bo_use_extra, bo_notice, bo_min_wr_num', TRUE);
		$write = $this->M_basic->get_write($board['bo_db'], $wr_id, 'wr_id, wr_num, wr_reply, wr_is_comment, wr_option, wr_content, mb_id, wr_password');
		
		$wr_ids = array();
		// 단일삭제
		if(!is_array($wr_id)) {
			if(!isset($write['wr_id']) || $write['wr_is_comment'])
			    alert('등록된 글이 없거나 코멘트 글입니다.');
			    
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
						alert('그룹관리자의 권한보다 높은 회원의 글이므로 삭제할 수 없습니다.');
				}
				else
					alert('자신이 관리하는 그룹의 게시판이 아니므로 글을 삭제할 수 없습니다.');
			}
			else if(IS_ADMIN == 'board') { // 게시판관리자
				if($member['mb_id'] == $board['bo_admin']) { // 자신이 관리하는 게시판인가
					if($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
						alert('게시판관리자의 권한보다 높은 회원의 글이므로 삭제할 수 없습니다.');
				}
				else
					alert('자신이 관리하는 게시판이 아니므로 글을 삭제할 수 없습니다.');
			}
			else {
				if($write['mb_id']) { 
					if(!IS_MEMBER || $member['mb_id'] != $write['mb_id'])
						alert('자신의 글이 아니므로 삭제할 수 없습니다.');
				}
				else {
					$this->load->library('encrypt');
		        	if(md5($this->input->post('wr_password')) != $this->encrypt->decode($write['wr_password']))
						alert("비밀번호가 맞지 않습니다.");	
				}
			}
			
			// 원글만 구한다.
			/*
		    $cnt = $this->M_board->is_reply($board['bo_db'], $wr_id, $write['wr_num'], $write['wr_reply']);
		    if ($cnt)
		        alert("이 글과 관련된 답변글이 존재하므로 삭제할 수 없습니다.\\n\\n우선 답변글부터 삭제하여 주십시오.");
		    */
		
		    // 코멘트 달린 원글의 수정 여부
		    if($board['bo_count_delete'] > 0) {
			    $cnt = $this->M_board->is_comment($board['bo_db'], $wr_id, (IS_MEMBER) ? $member['mb_id'] : '');
			    if($cnt >= $board['bo_count_delete'] && !IS_ADMIN)
			        alert("이 글과 관련된 코멘트가 존재하므로 삭제할 수 없습니다.\\n\\n코멘트가 ".$board['bo_count_delete']."건 이상 달린 원글은 삭제할 수 없습니다.");
	  		}
	  		
	  		$wr_ids = array($wr_id); // 배열화
		}
		else {
			foreach($write as $row) {
				if(!isset($row['wr_id']) || $row['wr_is_comment'])
			    	continue;
			    
				// 수정 권한 IF
				if(IS_ADMIN == 'group' || IS_ADMIN == 'board') {
					$mb = $this->M_basic->get_member($row['mb_id'], 'mb_level');
					$mb_level = (isset($mb['mb_level'])) ? $mb['mb_level'] : 1;
				}
				
				if(IS_ADMIN == 'super') {
					// 통과
				}
				else if(IS_ADMIN == 'group') { // 그룹관리자
					if($member['mb_id'] == $board['gr_admin']) { // 자신이 관리하는 그룹인가
						if($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
							continue;
					}
					else
						continue;
				}
				else if(IS_ADMIN == 'board') { // 게시판관리자
					if ($member['mb_id'] == $board['bo_admin']) { // 자신이 관리하는 게시판인가
						if ($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
							continue;
					}
					else
						continue;
				}
				else
					continue; // 나머지는 삭제 불가
				
				/*
				$cnt = $this->M_board->is_reply($board['bo_db'], $row['wr_id'], $row['wr_num'], $row['wr_reply']);
		    	if ($cnt)
		    		continue;
		    	*/
		    		
	    		$wr_ids[] = $row['wr_id'];
			}
		}

		// 공지사항
		$bo_notice = array();
		if(IS_ADMIN && $wr_ids) {
			$notice_array = explode(',', trim($board['bo_notice']));
	        foreach($notice_array as $row) {
	        	if($row && !in_array((int)$row, $wr_ids)) {
	                $bo_notice[] = $row;
          		}
			}
		}

		$this->M_board->write_delete($board['bo_db'], $wr_ids, implode(',', $bo_notice), $board['bo_min_wr_num'], $board['bo_use_extra']);
		
        $this->db->cache_delete('default', 'index');
        
		goto_url('board/'.$bid.'/lists'.$this->input->post('qstr'));
	}
	
	function writer_info($member='', $w='', $write='', $bo_use_name=false) {
		if(IS_MEMBER) {
			// 관리자가 타인의 글 수정시..
			if(IS_ADMIN && $w == 'u') {
				$mb['mb_id']		= $write['mb_id'];
				$mb['wr_name']		= setValue($write['wr_name'], $this->input->post('wr_name'));
				$mb['wr_email']		= setValue($write['wr_email'], $this->input->post('wr_email'));
			}
			else {
				$mb['mb_id']		= $member['mb_id'];
				$mb['wr_name']		= $bo_use_name ? $member['mb_name'] : $member['mb_nick'];
				$mb['wr_password']	= $member['mb_password'];
				$mb['wr_email']		= $member['mb_email'];
			}
		}
		else {
			$mb['mb_id']		= '';
			$mb['wr_name']		= $this->input->post('wr_name');
			$mb['wr_password']	= $this->encrypt->encode($this->input->post('wr_password'));
			$mb['wr_email']		= $this->input->post('wr_email');
		}
		
		return $mb;
	}
	
	function chk_error($member, $w, $board, $wr_id, $write='', $notice_array='', $options='') {
		if($w == 'u' || $w == 'r') {			 
			if(!$write) {
				alert('글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다.');
			}
		}
			
		// 비밀글은 사용일 경우에만 가능해야 함
		if(!IS_ADMIN && !$board['bo_use_secret'] && $options['secret']) {
			alert('비밀글 미사용 게시판 이므로 비밀글로 등록할 수 없습니다.');
		}
			
		if($w == '' || $w == 'u') {
			// 글쓰기 권한과 수정은 별도로 처리되어야 함
			if($w == 'u' && IS_MEMBER && $write['mb_id'] == $member['mb_id']) {
				// 통과
			}
			else if ($member['mb_level'] < $board['bo_write_level']) {
				alert('글을 쓸 권한이 없습니다.');
			}
		
			if (!IS_ADMIN && $options['notice']) {
				alert('관리자만 공지할 수 있습니다.');
			}
		}
		else if($w == 'r') {
			if (in_array((int)$wr_id, $notice_array)) {
				alert('공지에는 답변 할 수 없습니다.');
			}
				
			if ($member['mb_level'] < $board['bo_reply_level']) {
				alert('글을 답변할 권한이 없습니다.');
			}
				
			// 답변의 답변 단계 체크 - wr_reply varchar(10)
			if (strlen($write['wr_reply']) == 10) {
				alert('더 이상 답변하실 수 없습니다.\\n\\n답변은 10단계 까지만 가능합니다.');
			}
		}
		else {
			alert('w 값이 제대로 넘어오지 않았습니다.');
		}
		
		if(!IS_ADMIN && ($w == '' || $w == 'r')) {
			if($this->session->userdata('ss_datetime') >= (time() - $this->config->item('cf_delay_sec'))) {
				alert('너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.');
			}
			
			$this->session->set_userdata('ss_datetime', time());
			
			// 동일내용 연속 등록 불가
			$row = $this->M_board->same_write($board['bid']);
			$curr_md5 = md5($this->input->server('REMOTE_ADDR').$this->input->post('wr_subject').$this->input->post('wr_content'));
		
			if($row && $row['prev_md5'] == $curr_md5) {
				alert('동일한 내용을 연속해서 등록할 수 없습니다.');
			}
		}
	}
}
