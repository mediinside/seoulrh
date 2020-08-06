<?php
class Record_comment extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('M_board_comt');
		$this->load->config('cf_board');
		define('IS_ADMIN', $this->input->post('is_admin'));
	}

	function update() {
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'bid', 'label'=>'게시판아이디', 'rules'=>'trim|required|alpha_dash'),
			array('field'=>'wr_id', 'label'=>'원글아이디', 'rules'=>'trim|required|is_natural_no_zero'),
			array('field'=>'comment_id', 'label'=>'댓글아이디', 'rules'=>'trim|is_natural_no_zero'),
			array('field'=>'wr_content', 'label'=>'내용', 'rules'=>'trim|required|xss_clean'),
			array('field'=>'w', 'label'=>'w', 'rules'=>'trim|required'),
		);
		if (!IS_MEMBER) {
			$config[] = array('field'=>'wr_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[10]');
			$config[] = array('field'=>'wr_password', 'label'=>'비밀번호', 'rules'=>'trim|required|max_length[20]|md5');
			$config[] = array('field'=>'wr_key', 'label'=>'자동등록방지', 'rules'=>'trim|required');
		}

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE)
			alert('올바르지 않은 접근입니다.');
		else {
			$w = $this->input->post('w');
			$wr_id = $this->input->post('wr_id');
			$bid = $this->input->post('bid');
			$comment_id = $this->input->post('comment_id');
			
			$board = $this->M_basic->get_board($bid, 'bid,bo_db,bo_subject,bo_admin,bo_comment_level,bo_use_captcha,bo_use_name,bo_reply_order,bo_use_email', TRUE);
			
			if (!IS_MEMBER && $board['bo_use_captcha'])
				check_wrkey();
			
			$member = unserialize(MEMBER);

			if ($w == 'c' || $w == 'cu')  {
				if ($member['mb_level'] < $board['bo_comment_level'])
					alert('댓글을 쓸 권한이 없습니다.');
			}
			else
				alert('잘못된 접근입니다.');

			$wr = $this->M_basic->get_write($board['bo_db'], $wr_id, 'wr_id, wr_num, ca_code, wr_option, wr_subject, mb_id, wr_email');

			if (!isset($wr['wr_id']))
				alert("글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다.");

			// 세션의 시간 검사 (글쓰기 딜레이)
			if ($w == 'c' && $this->session->userdata('ss_datetime') >= (time() - $this->config->item('cf_delay_sec')) && !IS_ADMIN)
				alert('너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.');

			$this->session->set_userdata('ss_datetime', time());

			// 동일내용 연속 등록 불가
			$row = $this->M_board_comt->same_comment($board['bo_db'], $comment_id);
			$curr_md5 = md5($this->input->server('REMOTE_ADDR').''.$this->input->post('wr_content'));

			if ($row && $row['prev_md5'] == $curr_md5)
				alert('동일한 내용을 연속해서 등록할 수 없습니다.');

			if (IS_MEMBER) {
				$mb['mb_id']       = $member['mb_id'];
				$mb['wr_name']     = $board['bo_use_name'] ? $member['mb_name'] : $member['mb_nick'];
				$mb['wr_password'] = $member['mb_password'];
				$mb['wr_email']    = $member['mb_email'];
			}
			else {
				$this->load->library('encrypt');
				$mb['mb_id']	   = '';
				$mb['wr_name']	   = $this->input->post('wr_name');
				$mb['wr_password'] = $this->encrypt->encode($this->input->post('wr_password'));
				$mb['wr_email']	   = '';
			}

			// 댓글 입력
			if ($w == 'c') {
                // 댓글 답변
				if ($comment_id) {
					$reply_array = $this->M_basic->get_write($board['bo_db'], $comment_id, 'wr_id, wr_comment, wr_comment_reply, mb_id');
					if (!isset($reply_array['wr_id']))
						alert("답변할 댓글이 없습니다.\\n\\n답변하는 동안 댓글이 삭제되었을 수 있습니다.");

					$tmp_comment = $reply_array['wr_comment'];
					if (strlen($reply_array['wr_comment_reply']) == 10)
						alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 10단계 까지만 가능합니다.");

					$tmp_comment_reply = $this->M_board_comt->get_comment_reply_step($board['bo_db'], $wr_id, $tmp_comment, $board['bo_reply_order'], $reply_array['wr_comment_reply']);
				}
				else {
					$tmp_comment = $this->M_board_comt->get_comment_max($board['bo_db'], $wr_id);
					$tmp_comment_reply = '';
				}

				$comment_id = $this->M_board_comt->comment_insert($board['bo_db'], $wr_id, $wr['wr_num'], $tmp_comment, $tmp_comment_reply, $wr['ca_code'], $mb);

				// 메일발송 사용
				if ($this->config->item('cf_use_email') && $board['bo_use_email']) {
					$super_admin = $this->M_basic->get_member(ADMIN, 'mb_email');
					$group_admin = $this->M_basic->get_member($board['gr_admin'], 'mb_email');
					$board_admin = $this->M_basic->get_member($board['bo_admin'], 'mb_email');

					$this->load->helper('textual');
					$wr_subject = get_text(stripslashes($wr['wr_subject']));
					$wr_content = nl2br(get_text(stripslashes("----- 원글 -----\n\n".$wr['wr_subject']."\n\n\n----- 댓글 -----\n\n".$this->input->post('wr_content'))));

					$warr = array('c'=>'댓글', 'cu'=>'댓글 수정');
					$str = $warr[$w];

					$subject = "'".$board['bo_subject']."' 게시판에 ".$str."글이 올라왔습니다.";
					$link_url = $this->config->item('base_url').'/board/'.$board['bo_db'].'/view/wr_id/'.$wr_id.'#c_'.$comment_id;

					$data = array(
						'wr_name' => $mb['wr_name'],
						'wr_subject' => $wr_subject,
						'wr_content' => $wr_content,
						'link_url' => $link_url
					);
					$content = $this->load->view('mail/write_update', $data, TRUE);

					$to_email = array();
					$this->load->library('email');

					$this->email->clear();
					$this->email->from(($mb['wr_email'] ? $mb['wr_email'] : $super_admin['mb_email']), $mb['wr_name']);
			
					// 게시판 관리자에게 보내는 메일
					if ($this->config->item('cf_email_wr_board_admin') && $board_admin['mb_email']) {
						$to_email[] = $board_admin['mb_email'];
					}

					// 그룹 관리자에게 보내는 메일
					if ($this->config->item('cf_email_wr_group_admin') && $group_admin['mb_email']) {
						if ($group_admin['mb_email'] != $board_admin['mb_email']) {
							$to_email[] = $group_admin['mb_email'];
						}
					}

					// 최고관리자에게 보내는 메일
					if ($this->config->item('cf_email_wr_super_admin') && $super_admin['mb_email']) {
						if ($super_admin['mb_email'] != $board_admin['mb_email'] && $super_admin['mb_email'] != $group_admin['mb_email']) {
							$to_email[] = $super_admin['mb_email'];
						}
					}

					// 답변 메일받기 (원게시자에게 보내는 메일)
					if (strpos($wr['wr_option'], 'mail') !== FALSE && $wr['wr_email'] && $wr['wr_email'] != $mb['wr_email']) {
						if ($this->config->item('cf_email_wr_write'))
							$to_email[] = $wr['wr_email'];
					}

					$this->email->to($to_email);
					$this->email->subject($subject);
					$this->email->message($content);
					$this->email->send();
				}
			}
			else if ($w == 'cu') { // 댓글 수정
				$comment = $reply_array = $this->M_basic->get_write($board['bo_db'], $comment_id, 'mb_id, wr_comment, wr_comment_reply');
				$tmp_comment = $reply_array['wr_comment'];
				$tmp_comment_reply = $reply_array['wr_comment_reply'];

				// 수정 권한 IF
				if (IS_ADMIN == 'group' || IS_ADMIN == 'board') {
					$mb = $this->M_basic->get_member($comment['mb_id'], 'mb_level');
					$mb_level = (isset($mb['mb_level'])) ? $mb['mb_level'] : 1;
				}
				
				if (IS_ADMIN == 'super') {
					// 통과
				}
				else if (IS_ADMIN == 'group') { // 그룹관리자
					if ($member['mb_id'] == $board['gr_admin']) { // 자신이 관리하는 그룹인가
						if ($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
							alert('그룹관리자의 권한보다 높은 회원의 댓글이므로 수정할 수 없습니다.');
					}
					else
						alert('자신이 관리하는 그룹의 게시판이 아니므로 댓글을 수정할 수 없습니다.');
				}
				else if (IS_ADMIN == 'board') { // 게시판관리자
					if ($member['mb_id'] == $board['bo_admin']) { // 자신이 관리하는 게시판인가
						if ($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
							alert('게시판관리자의 권한보다 높은 회원의 댓글이므로 수정할 수 없습니다.');
					}
					else
						alert('자신이 관리하는 게시판이 아니므로 댓글을 수정할 수 없습니다.');
				}
				else if (IS_MEMBER) {
					if ($member['mb_id'] != $comment['mb_id'])
						alert('자신의 글이 아니므로 수정할 수 없습니다.');
				}

				$cnt = $this->M_board_comt->is_comment_reply($board['bo_db'], $wr_id, $comment_id, $tmp_comment, $tmp_comment_reply);
				if ($cnt && !IS_ADMIN)
					alert('이 댓글과 관련된 답변댓글이 존재하므로 수정할 수 없습니다.');

				$this->M_board_comt->comment_update($board['bo_db'], $comment_id);
			}

            $this->db->cache_delete('default', 'index');

            goto_url('board/'.$bid.'/view'.$this->input->post('qstr').'#c_'.$comment_id);
		}
	}

	function delete() {
		$bid = $this->input->post('bid');
		$comment_id = $this->input->post('comment_id');
		
		$board = $this->M_basic->get_board($bid, 'bid,bo_db,bo_admin', TRUE);
		$write = $this->M_basic->get_write($board['bo_db'], $comment_id, 'wr_id, wr_is_comment, mb_id, wr_password, wr_comment_reply, wr_parent, wr_comment');
		$member = unserialize(MEMBER);

		if (!isset($write['wr_id']) || !$write['wr_is_comment'])
		    alert('등록된 댓글이 없거나 댓글이 아닙니다.');
		
		// 수정 권한 IF
		if (IS_ADMIN == 'group' || IS_ADMIN == 'board') {
			$mb = $this->M_basic->get_member($write['mb_id'], 'mb_level');
			$mb_level = (isset($mb['mb_level'])) ? $mb['mb_level'] : 1;
		}

		if (IS_ADMIN == 'super') {
			// 통과
		}
		else if (IS_ADMIN == 'group') { // 그룹관리자
			if ($member['mb_id'] == $board['gr_admin']) { // 자신이 관리하는 그룹인가
				if ($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
					alert('그룹관리자의 권한보다 높은 회원의 댓글이므로 삭제할 수 없습니다.');
			}
			else
				alert('자신이 관리하는 그룹의 게시판이 아니므로 댓글을 삭제할 수 없습니다.');
		}
		else if (IS_ADMIN == 'board') { // 게시판관리자
			if ($member['mb_id'] == $board['bo_admin']) { // 자신이 관리하는 게시판인가
				if ($member['mb_level'] < $mb_level) // 자신의 레벨이 낮다면
					alert('게시판관리자의 권한보다 높은 회원의 댓글이므로 삭제할 수 없습니다.');
			}
			else
				alert('자신이 관리하는 게시판이 아니므로 댓글을 삭제할 수 없습니다.');
		}
		else if (IS_MEMBER) {
			if ($member['mb_id'] != $write['mb_id'])
				alert('자신의 글이 아니므로 삭제할 수 없습니다.');
		}
		else {
			$this->load->library('encrypt');
		    if (md5($this->input->post('wr_password')) != $this->encrypt->decode($write['wr_password']))
		        alert('비밀번호가 맞지 않습니다.');
		}
		
		$cnt = $this->M_board_comt->is_comment_reply($board['bo_db'], $write['wr_parent'], $comment_id, $write['wr_comment'], $write['wr_comment_reply']);
		if ($cnt)
		    alert('이 댓글과 관련된 답변댓글이 존재하므로 삭제할 수 없습니다.');
		
		$this->M_board_comt->comment_delete($board['bo_db'], $comment_id, $write['wr_parent']);

        $this->db->cache_delete('default', 'index');

		goto_url('board/'.$bid.'/view'.$this->input->post('qstr'));
	}
}
?>
