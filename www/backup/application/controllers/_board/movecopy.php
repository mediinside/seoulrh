<?php
class Movecopy extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('M_board_mvcp');
		define('IS_ADMIN', $this->input->post('is_admin'));
	}
	
	function index() {
		if (!IS_ADMIN)
		    show_404();
		
		$bid		= $this->input->post('bid');
		$wr_id		= $this->input->post('wr_id');
		$sw			= $this->input->post('sw');
		$qstr		= $this->input->post('qstr');
		
		if (!$wr_id) alert_close('잘못된 접근입니다.');
		switch ($sw) {
			case 'move' : $act = '이동'; break;
			case 'copy' : $act = '복사'; break;
			default: alert_close('잘못된 접근입니다.'); break;
		}
		
		$member = unserialize(MEMBER);
		$board = $this->M_basic->get_board($bid, 'bid, bo_db');
		$result = $this->M_board_mvcp->list_move_copy($board['bo_db'], $member['mb_id']);
		
		$list = array();
		$save_gr_subject = '';
		foreach($result as $i => $row) {
			$list[$i]->bid = $row['bid'];
			
			$span = ($save_gr_subject == $row['gr_subject']) ? "<span style='color:#cccccc;'>" : '<span>';
			
			$list[$i]->gr_subject = $span.$row['gr_subject'].' > </span>';
			$list[$i]->bo_subject = $row['bo_subject'];
			
			$save_gr_subject = $row['gr_subject'];
		}
		
		$vars = array(
			'_TITLE_'		=> '게시물 '.$act,
			'_BODY_'		=> 'board/movecopy',
			'_CSS_'			=> array('board_mvcp'),
			
			'sw'			=> $sw,
			'check_type'	=> $sw == 'copy' ? 'checkbox' : 'radio',
			'bid'			=> $bid,
			'wr_id'			=> serialize($wr_id),
			'qstr'			=> $qstr,
			'act'			=> $act,
			'list'			=> $list
		);
		$this->load->view('layout/layout_blank', $vars);
	}
}
?>
