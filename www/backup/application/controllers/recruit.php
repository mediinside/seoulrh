<?php
class Recruit extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('M_recruit'));
	}
		
	function lists() {
		$this->load->helper(array('sideview', 'search', 'textual'));
		$this->load->library('pagination');
		
		// 서치
		$seg = new search_seg(3);
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		$cate = setValue(1, $seg->get_seg('cate'));
		$qstr = $seg->get_qstr();
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'recr_id';
		if (!$sod) $sod = 'desc';
		
		$de_sfl = '';
		switch($sfl) {
			case '1' :
				$de_sfl = 'recr_subject';
				break;
			case '2' :
				$de_sfl = 'recr_content';
				break;
			default :
				$de_sfl = 'recr_subject|recr_content';
				break;
		}
		
		$qstr = $seg->get_qstr();
		
		$this->pagination->uri_segment =		4;
		
		$this->pagination->first_link =			'<img width="25" height="25" alt="처음" src="/img/board/p_first.gif"/>';
		$this->pagination->next_link =			'<img width="25" height="25" alt="이전" src="/img/board/p_next.gif"/>';
		$this->pagination->prev_link =			'<img width="25" height="25" alt="이전" src="/img/board/p_prev.gif"/>';
		$this->pagination->last_link =			'<img width="25" height="25" alt="끝" src="/img/board/p_last.gif"/>';
		
		// 페이징
		$pageConf['suffix'] =	$qstr;
		$pageConf['base_url'] =	RT_PATH.'/recruit/lists/page/';
		$pageConf['per_page'] =	15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_recruit->list_result($cate, $sst, $sod, $de_sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['no'] =			$result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =			$i % 2;
			
			$list[$i]['href'] =			'/recruit/view/'. $list[$i]['recr_id'] . $qstr;
			$list[$i]['subject'] =		cut_str(get_text($row['recr_subject']), 85);
			$list[$i]['status'] =		!$row['recr_soldout'] && (strtotime($row['recr_sdatetime']) < 0 || TIME > strtotime($row['recr_sdatetime'])) && (strtotime($row['recr_edatetime']) < 0 || TIME < strtotime($row['recr_edatetime'])) ? 'ing' : (TIME < strtotime($row['recr_sdatetime']) ? 'ready' : 'end');
			$list[$i]['status_str'] =	$list[$i]['status'] == 'ing' ? '접수중' : ($list[$i]['status'] == 'ready' ? '접수전' : '점수마감');
			$list[$i]['sdate'] =		strtotime($row['recr_sdatetime']) > 0 ? preg_replace('/\-/','.',substr($row['recr_sdatetime'],0,10)) : '제한없음';
			$list[$i]['edate'] =		strtotime($row['recr_edatetime']) > 0 ? preg_replace('/\-/','.',substr($row['recr_edatetime'],0,10)) : '제한없음';
			
			$list[$i]['recr_cate'] =		isset($this->full_cate[$list[$i]['recr_cate']]) ? $this->full_cate[$list[$i]['recr_cate']] : '';
			$list[$i]['recr_regdate'] =	date('Y-m-d', strtotime($list[$i]['recr_regdate']));
		}
		
		$vars = array(
			'_TITLE_'		=> '모집공고 리스트',
			'_BODY_'		=> 'recruit/recruit_list',
			
			'm1'			=> '08',
			'm2'			=> '02',
			'm1_tt'			=> '채용정보',
			'm2_tt'			=> '채용안내',
			
			'token'			=> $token,
			'list'			=> $list,
			'qstr'			=> $qstr,
			
			'cate'			=> $cate,
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			'total_cnt'		=> $result['total_cnt'],
			'paging'		=> $this->pagination->create_links()
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
	
	function view($id='') {	
		$this->load->helper(array('search', 'textual'));
		$this->load->library('pagination');
		
		// 서치
		$seg = new search_seg();
		$qstr  = $seg->get_qstr();
		
		$row = $this->M_recruit->row($id);
		if (!isset($row['recr_id']))
			alert('등록된 자료가 없습니다.');
		
		$row['subject'] = get_text($row['recr_subject']);
		$row['content'] = $row['recr_content'];
		$row['status'] = !$row['recr_soldout'] && (strtotime($row['recr_sdatetime']) < 0 || TIME > strtotime($row['recr_sdatetime'])) && (strtotime($row['recr_edatetime']) < 0 || TIME < strtotime($row['recr_edatetime'])) ? 'ing' : (TIME < strtotime($row['recr_sdatetime']) ? 'ready' : 'end');
		$row['status_str'] = $row['status'] == 'ing' ? '접수중' : ($row['status'] == 'ready' ? '접수전' : '점수마감');
		$row['sdate'] = strtotime($row['recr_sdatetime']) > 0 ? preg_replace('/\-/','.',substr($row['recr_sdatetime'],0,10)) : '제한없음';
		$row['edate'] = strtotime($row['recr_edatetime']) > 0 ? preg_replace('/\-/','.',substr($row['recr_edatetime'],0,10)) : '제한없음';
				
		$vars = array(
			'_TITLE_'		=> '모집공고 상세정보',
			'_BODY_'		=> 'recruit/recruit_view',

			'm1'			=> '08',
			'm2'			=> '02',
			'm1_tt'			=> '채용정보',
			'm2_tt'			=> '채용안내',
			
			'row'			=> $row,
			'reg_href'		=> $row['status'] == 'ing' ? '/recruit/reg/'. $id . $qstr : 'javascript:ended();',
			'list_href'		=> '/recruit/lists'. $qstr
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
	
	function reg($id='') {
		$this->load->helper('search');
		$this->load->library('form_validation');

		// 서치
		$seg = new search_seg();
		$qstr  = $seg->get_qstr();
		
		$row = $this->M_recruit->row($id);
		if(!isset($row['recr_id'])) {
			alert('등록된 자료가 없습니다.');
		}
		else if($row['recr_soldout'] || (strtotime($row['recr_sdatetime']) > 0 && TIME < strtotime($row['recr_sdatetime'])) || (strtotime($row['recr_edatetime']) > 0 && TIME > strtotime($row['recr_edatetime']))) {
			alert('접수 신청 기간이 아닙니다.');
		}
		
		$config = array(
			array('field'=>'name', 'label'=>'이름', 'rules'=>'trim|required|max_length[50]|xss_clean'),
			array('field'=>'phone[]', 'label'=>'연락처', 'rules'=>'trim|required|xss_clean'),
			array('field'=>'email', 'label'=>'이메일', 'rules'=>'trim|required|max_length[50]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {			
			$vars = array(
				'_TITLE_'		=> '지원신청',
				'_BODY_'		=> 'recruit/recruit_reg',
				'_JS_'			=> array('jvalidate'),

				'm1'			=> '08',
				'm2'			=> '02',
				'm1_tt'			=> '채용정보',
				'm2_tt'			=> '채용안내',
					
				'id'			=> $id,
				'qstr'			=> $qstr,
				'back_href'		=> '/recruit/view/'. $id . $qstr
			);

			$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
		}
		else {
			$row = $this->M_recruit->row($id);
			if(!isset($row['recr_id'])) {
				alert('등록된 자료가 없습니다.');
			}
			else if($row['recr_soldout'] || (strtotime($row['recr_sdatetime']) > 0 && TIME < strtotime($row['recr_sdatetime'])) || (strtotime($row['recr_edatetime']) > 0 && TIME > strtotime($row['recr_edatetime']))) {
				alert('접수 신청 기간이 아닙니다.');
			}
			
			$data = array(
					'rreg_recr_id'	=> $id,
					'rreg_name'		=> $this->input->post('name'),
					'rreg_phone'	=> implode('-', $this->input->post('phone')),
					'rreg_email'	=> setValue('', $this->input->post('email')),
					'rreg_mdydate'	=> TIME_YMDHIS
			);

			if(!$rreg_id = $this->M_recruit->record($data)) {
				alert('시스템 오류로 인하여 저장되지 않았습니다.');
			}

			/* 첨부파일 업로드 */
			$nos_data = array();
			
			if(isset($_FILES['resume']) && $_FILES['resume']['tmp_name']) {
				$nos_data['rreg_resume'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['resume']);
			}
			if(isset($_FILES['introduction']) && $_FILES['introduction']['tmp_name']) {
				$nos_data['rreg_introduction'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['introduction']);
			}
			if(isset($_FILES['transcript']) && $_FILES['transcript']['tmp_name']) {
				$nos_data['rreg_transcript'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['transcript']);
			}
			if(isset($_FILES['diploma']) && $_FILES['diploma']['tmp_name']) {
				$nos_data['rreg_diploma'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['diploma']);
			}
			if(isset($_FILES['registration']) && $_FILES['registration']['tmp_name']) {
				$nos_data['rreg_registration'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['registration']);
			}
			if(isset($_FILES['family']) && $_FILES['family']['tmp_name']) {
				$nos_data['rreg_family'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['family']);
			}
			if(isset($_FILES['experience']) && $_FILES['experience']['tmp_name']) {
				$nos_data['rreg_experience'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['experience']);
			}
			if(isset($_FILES['license']) && $_FILES['license']['tmp_name']) {
				$nos_data['rreg_license'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $rreg_id, $_FILES['license']);
			}
			
			foreach($nos_data AS $key => $val) {
				$arr = array_flip($val);
				$nos_data[$key] = count($arr) > 1 ? implode('|', $arr) : current($arr);
			}
			
			if($nos_data) {
				$this->M_recruit->db->update($this->M_recruit->table_reg, $nos_data, array('rreg_id' => $rreg_id));
			}
			
			alert('신청서가 접수되었습니다.', '/recruit/view/'. $id . $qstr);
		}
	}
}
?>
