<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Password extends Widget {
	function index() {
		$seg =& $this->seg;
		
		$w     	    = $seg->get_seg('w');          // 모드
		$wr_id	    = $seg->get_seg('wr_id');      // 게시물아이디
		$comment_id = $seg->get_seg('comment_id'); // 코멘트아이디
		$qstr  		= $seg->get_qstr();            // 쿼리스트링
		
		switch ($w) {
			case 'u' : $action = 'board/'.BO_TABLE.'/write'.$qstr; break;
			case 'd' :
				$qstr = qstr_rep($qstr, 'wr_id');
				$action = '_board/record_write/delete';
			break;
			case 'x' : $action = '_board/record_comment/delete'; break;
			case 's' :
				if (IS_ADMIN) // 관리자 통과
					goto_url('board/'.BO_TABLE.'/view/wr_id/'.$wr_id);
				
				$write = $this->M_basic->get_write(BO_DB, $wr_id, 'mb_id');
				
				// 회원의 글이라면
				if(isset($write['mb_id']) && $write['mb_id']) {
					$member =& $this->member;
					if (IS_MEMBER && $member['mb_id'] == $write['mb_id']) // 자신의 글
						goto_url('board/'.BO_TABLE.'/view/wr_id/'.$wr_id);
					else {
						$msg = '글을 읽을 권한이 없습니다.';
					 	if (!IS_MEMBER)
					 		$msg .= '\\n\\n답글의 경우 비회원은 본인글을 읽은 후 읽어 주시기 바랍니다.';
						alert($msg);
					}
				}
				else // 비회원
					$action = '_board/record_password/check';
			break;
			default: alert('잘못된 접근입니다.'); break;
		}
		
		$vars = array(
			'_TITLE_'		=> '비밀번호 확인',
			'_BODY_'		=> 'board/password',
			'_JS_'			=> array('jvalidate','capslock'),
			
			'w' => $w,
			'comment_id' => $comment_id,
			'action' => $action,
			'qstr' => qstr_rep($qstr, 'w,comment_id')
		);
		
		$this->load->view(null, $vars);
	}
}
?>
