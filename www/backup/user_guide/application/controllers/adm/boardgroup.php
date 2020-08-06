<?php
class Boardgroup extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_boardgroup');
	}

	function lists() {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper(array('admin', 'search'));

		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sst  = $seg->get_seg('sst');
		$sod  = $seg->get_seg('sod');
		$sfl  = $seg->get_seg('sfl');
		$stx  = $seg->get_seg('stx');

		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'gr_id';
		if (!$sod) $sod = 'asc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/boardgroup/lists/page/';
		$config['per_page'] = 15;

		$offset = ($page - 1) * $config['per_page'];
		$result = $this->M_a_boardgroup->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$list = array();
		$token = get_token();
		foreach ($result['qry'] as $i => $row) {
			$s_mod = icon('수정', 'boardgroup/form/u/'.$row['gr_id']);
			$s_del = icon('삭제', "javascript:post_s('".ADM_F."/boardgroup/delete', {gr_id:'".$row['gr_id']."', token:'".$token."'}, true);");
			
			$list[$i]->lst = $i%2;
			$list[$i]->bo_cnt = $row['bo_cnt'];
			$list[$i]->gr_id = $row['gr_id'];
			$list[$i]->gr_subject = $row['gr_subject'];
			$list[$i]->gr_admin = $row['gr_admin'];
			$list[$i]->s_mod = $s_mod;
			$list[$i]->s_del = $s_del;
		}

		$vars = array(
			'_TITLE_'		=> '게시판 그룹 리스트',
			'_BODY_'		=> ADM_F.'/board/boardgroup_list',
			
			'token'			=> $token,

			'list'			=> $list,
			's_add'			=> icon('추가', 'boardgroup/form'),

			'sfl'			=> $sfl,
			'stx'			=> $stx,

			'total_cnt'		=> number_format($result['total_cnt']),
			'paging'		=> $this->pagination->create_links(),

			'sort_gr_id'	=> sort_link('gr_id', 'desc'),
			'sort_gr_subject'=> sort_link('gr_subject'),
			'sort_gr_admin'	=> sort_link('gr_admin')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $gr_id='') {
		include "init.php";
		
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'gr_id', 'label'=>'아이디', 'rules'=>'trim|required|min_length[3]|max_length[20]|alpha_dash|xss_clean'),
			array('field'=>'gr_subject', 'label'=>'제목', 'rules'=>'trim|required|max_length[20]'),
			array('field'=>'gr_admin', 'label'=>'그룹 관리자', 'rules'=>'trim|min_length[3]|max_length[20]|alpha_dash')
		);

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '') {
				$title = '생성';

				$gr = FALSE;
			} 
			else if ($w == 'u') {
				$gr = $this->M_a_boardgroup->get_group($gr_id);
				if (!isset($gr['gr_id']))
					alert('존재하지 않는 그룹 ID 입니다.');

				$title = '수정';
			} 
			else
				alert('잘못된 접근입니다.');
			
			$vars = array(
				'_TITLE_'		=> '게시판 그룹 '.$title,
				'_BODY_'		=> ADM_F.'/board/boardgroup_form',
				
				'w'				=> $w,
				'token'			=> get_token(),
				'gr_id'			=> $gr['gr_id'],
				'gr_subject'	=> $gr['gr_subject'],
				'gr_admin'		=> $gr['gr_admin']
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();

			$w = $this->input->post('w');
			$gr_id = $this->input->post('gr_id');

			if (!$w) {
				$gr = $this->M_a_boardgroup->get_group($gr_id);
				if (isset($gr['gr_id']))
					alert("이미 존재하는 그룹 ID 입니다.");

				$this->M_a_boardgroup->insert();
			}
			else if ($w == 'u') {
				$this->M_a_boardgroup->update();
			}
			else
				alert('잘못된 접근입니다.');

			goto_url(ADM_F.'/boardgroup/form/u/'.$gr_id);
		}
	}
	
	function delete() {
		check_token(URL);
	
		if (!$this->input->post('gr_id'))
			alert("잘못된 접근입니다.");
	
		if (!$this->M_a_boardgroup->delete())
			alert('이 그룹에 속한 게시판이 존재하여 게시판 그룹을 삭제할 수 없습니다.\\n\\n이 그룹에 속한 게시판을 먼저 삭제하여 주십시오.', URL);
	
		goto_url(URL);
	}
	
	function update() {
		check_token(URL);
	
		if ($this->input->post('chk')) {
			$gr_ids = $this->input->post('chk');
			$gr_subjects = $this->input->post('gr_subject');
			$gr_admins = $this->input->post('gr_admin');
		}
		else
			alert("잘못된 접근입니다.");
	
		foreach ($gr_ids as $gr_id) {
			$this->M_a_boardgroup->list_update($gr_id, $gr_subjects[$gr_id], $gr_admins[$gr_id]);
		}
	
		goto_url(URL);
	}
}
?>
