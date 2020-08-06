<?php
class Apply extends CI_Controller {
	
	/* 커스터 마이징 설정 */
	var $list_width = array(100, 80, 100, 150, 100);
	var $apply_info = array(
		'기본정보'	=> array(
			'ap_mb_id'			=> array('label'	=> '아이디',		'input'	=> 'text',		'listing'	=> true),
			'ap_name'			=> array('label'	=> '신청자',		'input'	=> 'text',		'listing'	=> true),
			'ap_phone'			=> array('label'	=> '전화번호',	'input'	=> 'text',		'listing'	=> true),
			'ap_email'			=> array('label'	=> '이메일',		'input'	=> 'text',		'listing'	=> true),
			'ap_status'			=> array('label'	=> '상태',		'input'	=> 'select',	'listing'	=> true)
		)
	);
	/* ----------------- */
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_apply');
		$this->load->model('M_apply');
		
		/* 상태 리스트 */
		$this->apply_info['기본정보']['ap_status']['option'] = $this->M_apply->ap_status;
		
	}
	
	function lists() {
		include "init.php";
		
		$this->load->helper(array('admin', 'sideview', 'search', 'textual'));
		$this->load->library('pagination');
				
		// 서치
		$seg = new search_seg;
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'ap_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		// 페이징
		$pageConf['suffix'] = $qstr;
		$pageConf['base_url'] = RT_PATH.'/'.ADM_F.'/apply/lists/page/';
		$pageConf['per_page'] = 15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_apply->list_result($sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['s_mod'] = icon('수정', 'apply/form/u/'.$list[$i]['ap_id']."?qstr=$qstr");
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/apply/delete', {id:'".$list[$i]['ap_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
			
			$list[$i]['no'] = $result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] = $i % 2;			
			$list[$i]['ap_regdate_conv'] = date('Y-m-d', strtotime($list[$i]['ap_regdate']));
		}
		
		foreach($this->apply_info as $group) {
			foreach($group as $column => $value) {
				if($value['listing']) {
					$sort_link[$column] = sort_link($column) ."?qstr=$qstr";
				}
			}
		}
		
		$vars = array(
			'_TITLE_'		=> '신청서 리스트',
			'_BODY_'		=> ADM_F.'/apply/apply_list',
			
			'token'			=> $token,
			'list_width'	=> $this->list_width,
			'apply_info'	=> $this->apply_info,
			'list'			=> $list,
			'qstr'			=> $qstr,
			's_add'			=> icon('추가', "apply/form"),
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			'total_cnt'		=> $result['total_cnt'],
			'paging'		=> $this->pagination->create_links(),
			'sort_link'		=> $sort_link
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w = '', $id = '') {
		include "init.php";
		
		$this->load->model('M_editor');
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'ap_name', 'label'=>'신청자', 'rules'=>'trim|required|max_length[120]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = array_false(array('ap_id','ap_mdydate','ap_regdate'));
				foreach($this->apply_info AS $ap_group_name => $val) {
					$row = array_merge($row, array_false(array_keys($this->apply_info[$ap_group_name])));
				}

				$row['files'] = array();
			}
			else if ($w == 'u') {
				$title = '수정';
				
				$row = $this->M_apply->row($id);
				if (!isset($row['ap_id']))
					alert('등록된 자료가 없습니다.');
				$row['files'] = $this->M_upload_files->get_files($this->M_apply->table, $id, '*');
			}
			
			$vars = array(
				'_TITLE_'		=> '신청서 '.$title,
				'_BODY_'		=> ADM_F.'/apply/apply_form',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jvalid_ext', 'jquery-ui.min', 'jtimepicker'),
				
				'w'				=> $w,
				'token'			=> get_token(),
				'apply_info'	=> $this->apply_info,
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('ap_id');
			
			$row = $this->M_apply->row($id);
			
			if (!$w) {
				if (isset($row['ap_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$data = array(
				'ap_mdydate' => TIME_YMDHIS
			);
			foreach($this->apply_info AS $ap_group) {
				foreach($ap_group AS $key => $val) {
					$data[$key] = $this->input->post($key);
				}
			}
			
			$id = $this->M_a_apply->record($w, $data);
			
			goto_url(ADM_F.'/apply/form/u/'.$id);
		}
	}
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$ap_status = $this->input->post('ap_status');
		
		if(!$ids)
			return false;
	
		$data = array(
			'ap_status' => $ap_status
		);
		
		$this->M_a_apply->list_update($ids, $data);
	
		goto_url(URL);
	}
	
	function delete() {
		check_token(URL);
	
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
	
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;
	
		foreach($ids as $i) {
			$this->M_a_apply->delete($i);
		}
	
		goto_url(URL);
	}
}
?>
