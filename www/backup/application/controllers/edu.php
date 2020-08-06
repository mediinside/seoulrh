<?php
class Edu extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('M_edu'));
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
		if (!$sst) $sst = 'pd_id';
		if (!$sod) $sod = 'desc';
		
		$de_sfl = '';
		switch($sfl) {
			case '1' :
				$de_sfl = 'pd_name';
				break;
			case '2' :
				$de_sfl = 'pd_content';
				break;
			default :
				$de_sfl = 'pd_name|pd_content';
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
		$pageConf['base_url'] =	RT_PATH.'/edu/lists/page/';
		$pageConf['per_page'] =	15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_edu->list_result($cate, $sst, $sod, $de_sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['no'] =			$result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =			$i % 2;
			
			$list[$i]['href'] =			'/edu/view/'. $list[$i]['pd_id'] . $qstr;
			$list[$i]['subject'] =		cut_str(get_text($row['pd_name']), 85);
			$list[$i]['status'] =		!$row['pd_soldout'] && (strtotime($row['pd_sdatetime']) < 0 || TIME > strtotime($row['pd_sdatetime'])) && (strtotime($row['pd_edatetime']) < 0 || TIME < strtotime($row['pd_edatetime'])) ? 'ing' : (TIME < strtotime($row['pd_sdatetime']) ? 'ready' : 'end');
			$list[$i]['status_str'] =	$list[$i]['status'] == 'ing' ? '접수중' : ($list[$i]['status'] == 'ready' ? '접수전' : '점수마감');
			$list[$i]['sdate'] =		strtotime($row['pd_sdatetime']) > 0 ? preg_replace('/\-/','.',substr($row['pd_sdatetime'],0,10)) : '제한없음';
			$list[$i]['edate'] =		strtotime($row['pd_edatetime']) > 0 ? preg_replace('/\-/','.',substr($row['pd_edatetime'],0,10)) : '제한없음';
			
			$list[$i]['pd_cate'] =		isset($this->full_cate[$list[$i]['pd_cate']]) ? $this->full_cate[$list[$i]['pd_cate']] : '';
			$list[$i]['pd_regdate'] =	date('Y-m-d', strtotime($list[$i]['pd_regdate']));
		}

		$cate_info = array('no' => str_pad($cate, 2, 0, STR_PAD_LEFT));
		$cate_info['title'] = $this->M_edu->cate[$cate];
		
		$vars = array(
			'_TITLE_'		=> '강좌 리스트',
			'_BODY_'		=> 'edu/edu_list',
			
			'm1'			=> '06',
			'm2'			=> '02',
			'm3'			=> $cate_info['no'],
			'm1_tt'			=> '교육연구사업',
			'm2_tt'			=> '세미나신청',
			'm3_tt'			=> $cate_info['title'],
			
			'token'			=> $token,
			'list'			=> $list,
			'qstr'			=> $qstr,
			
			'cate'			=> $cate,
			'cate_info'		=> $cate_info,
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
		
		$row = $this->M_edu->row($id);
		if (!isset($row['pd_id']))
			alert('등록된 자료가 없습니다.');
		
		$row['subject'] = get_text($row['pd_name']);
		$row['content'] = $row['pd_content'];
		$row['status'] = !$row['pd_soldout'] && (strtotime($row['pd_sdatetime']) < 0 || TIME > strtotime($row['pd_sdatetime'])) && (strtotime($row['pd_edatetime']) < 0 || TIME < strtotime($row['pd_edatetime'])) ? 'ing' : (TIME < strtotime($row['pd_sdatetime']) ? 'ready' : 'end');
		$row['status_str'] = $row['status'] == 'ing' ? '접수중' : ($row['status'] == 'ready' ? '접수전' : '점수마감');
		$row['files'] = $this->M_upload_files->get_files($this->M_edu->table, $id, '*');
		$row['thumbnail'] = '/useful/thumbnail/212x212x1/ki_edu/'. $id .'/no/'. $row['pd_image1'];
		$row['sdate'] = strtotime($row['pd_sdatetime']) > 0 ? preg_replace('/\-/','.',substr($row['pd_sdatetime'],0,10)) : '제한없음';
		$row['edate'] = strtotime($row['pd_edatetime']) > 0 ? preg_replace('/\-/','.',substr($row['pd_edatetime'],0,10)) : '제한없음';
		$row['eduDate'] = (strtotime($row['pd_eduSdate']) > 0 ? $row['pd_eduSdate'] : '') . (strtotime($row['pd_eduSdate']) > 0 && strtotime($row['pd_eduEdate']) > 0 ? ' ~ ' : '') .(strtotime($row['pd_eduEdate']) > 0 ? $row['pd_eduEdate'] : '');
		$row['eduTime'] = $row['pd_eduTime'] ? $row['pd_eduTime'] .' 시간' : '';
		
		$row['pd_schedule'] = unserialize($row['pd_schedule']);
		if(isset($row['pd_schedule']['stime']) && is_array($row['pd_schedule']['stime'])) {
			foreach($row['pd_schedule']['stime'] as $key => $val){
				foreach($val as $k => $v){
					$row['schedule'][$key][$k]['stime'] = $row['pd_schedule']['stime'][$key][$k];
					$row['schedule'][$key][$k]['etime'] = $row['pd_schedule']['etime'][$key][$k];
					$row['schedule'][$key][$k]['text'] = preg_replace('/\n/', '\\n', $row['pd_schedule']['text'][$key][$k]);
				}
			}
		}
		
		$cate_info = array('no' => str_pad($row['pd_cate'], 2, 0, STR_PAD_LEFT));
		$cate_info['title'] = $this->M_edu->cate[$row['pd_cate']];
		
		$vars = array(
			'_TITLE_'		=> '강좌 상세정보',
			'_BODY_'		=> 'edu/edu_view',
			'_JS_'			=> array('layerDlg'),

			'm1'			=> '06',
			'm2'			=> '02',
			'm3'			=> $cate_info['no'],
			'm1_tt'			=> '교육연구사업',
			'm2_tt'			=> '세미나신청',
			'm3_tt'			=> $cate_info['title'],

			'cate_info'		=> $cate_info,
			'row'			=> $row,
			'receipt_href'	=> '/edu/printConfirm/'. $id .'/receipt'. $qstr,
			'cert_href'		=> '/edu/printConfirm/'. $id .'/cert'. $qstr,
			'reg_href'		=> $row['status'] == 'ing' ? "layerWin('/edu/reg/{$id}{$qstr}', 'layerWin', 800, 630, 'no');" : 'ended();',
			'list_href'		=> '/edu/lists'. $qstr
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
	
	function reg($id='') {
		$this->load->helper('search');
		$this->load->library('form_validation');
		
		// 서치
		$seg = new search_seg();
		$qstr  = $seg->get_qstr();
		
		$row = $this->M_edu->row($id);
		if(!isset($row['pd_id'])) {
			alert('등록된 자료가 없습니다.');
		}
		else if($row['pd_soldout'] || (strtotime($row['pd_sdatetime']) > 0 && TIME < strtotime($row['pd_sdatetime'])) || (strtotime($row['pd_edatetime']) > 0 && TIME > strtotime($row['pd_edatetime']))) {
			alert('접수 신청 기간이 아닙니다.');
		}
		
		$config = array(
			array('field'=>'name', 'label'=>'국문 이름', 'rules'=>'trim|required|max_length[50]|xss_clean'),
			array('field'=>'name_en', 'label'=>'영문 이름', 'rules'=>'trim|required|max_length[50]|xss_clean'),
			array('field'=>'sex', 'label'=>'성별', 'rules'=>'trim|required|xss_clean'),
			array('field'=>'birth[]', 'label'=>'생일', 'rules'=>'trim|required|xss_clean'),
			array('field'=>'phone[]', 'label'=>'연락처', 'rules'=>'trim|required|xss_clean'),
			array('field'=>'email', 'label'=>'이메일', 'rules'=>'trim|required|xss_clean')
		);
		
		$yy = range(date('Y', TIME), date('Y', TIME)-100);
		$mm = range(1, 12);
		$dd = range(1, 31);
		$sel_birth = array(
			form_dropdown('birth[]', array_combine($yy, $yy)),
			form_dropdown('birth[]', array_combine($mm, $mm)),
			form_dropdown('birth[]', array_combine($dd, $dd))
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$vars = array(
				'_TITLE_'		=> '수강등록신청',
				'_BODY_'		=> 'edu/edu_reg',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jquery-ui.min', 'jtimepicker'),

				'm1'			=> '06',
				'm2'			=> '02',
				'm1_tt'			=> '교육연구사업',
				'm2_tt'			=> '세미나신청',
					
				'id'			=> $id,
				'qstr'			=> $qstr,
				'sel_birth'		=> $sel_birth,
				'back_href'		=> '/edu/view/'. $id . $qstr
			);

			$this->load->view('layout/layout_blank', $vars);
		}
		else {
			$row = $this->M_edu->row($id);
			if(!isset($row['pd_id'])) {
				alert('등록된 자료가 없습니다.');
			}
			else if($row['pd_soldout'] || (strtotime($row['pd_sdatetime']) > 0 && TIME < strtotime($row['pd_sdatetime'])) || (strtotime($row['pd_edatetime']) > 0 && TIME > strtotime($row['pd_edatetime']))) {
				alert('접수 신청 기간이 아닙니다.');
			}
			
			$data = array(
					'reg_edu_id'	=> $id,
					'reg_name'		=> $this->input->post('name'),
					'reg_name_en'	=> $this->input->post('name_en'),
					'reg_sex'		=> $this->input->post('sex'),
					'reg_birth'		=> implode('-', $this->input->post('birth')),
					'reg_phone'		=> implode('-', $this->input->post('phone')),
					'reg_email'		=> setValue('', $this->input->post('email')),
					'reg_job'		=> $this->input->post('job'),
					'reg_school'	=> $this->input->post('school'),
					'reg_company'	=> $this->input->post('company'),
					'reg_grade'		=> $this->input->post('grade'),
					'reg_inflow'	=> $this->input->post('inflow') ? implode('|', $this->input->post('inflow')) : '',
					'reg_message'	=> $this->input->post('message'),
					'reg_mdydate'	=> TIME_YMDHIS
			);

			if(!$this->M_edu->record($data)) {
				alert('시스템 오류로 인하여 저장되지 않았습니다.');
			}
			
			alert_dlg_close('신청서가 접수되었습니다.');
		}
	}
	
	function printConfirm($id='', $mode='') {
		if($mode == 'cert') {
			$reg_where = 'reg_cert = 1';
		}
		else if($mode == 'receipt') {
			$reg_where = 'reg_pay = 1';
		}
		else {
			show_404();
		}
		
		$this->load->helper('search');
		$this->load->library('form_validation');

		// 서치
		$seg = new search_seg();
		$qstr  = $seg->get_qstr();
		
		$row = $this->M_edu->row($id);
		if(!isset($row['pd_id'])) {
			alert('등록된 자료가 없습니다.');
		}
		
		$config = array(
				array('field'=>'name', 'label'=>'이름', 'rules'=>'trim|required|max_length[50]|xss_clean'),
				array('field'=>'phone[]', 'label'=>'연락처', 'rules'=>'trim|required|xss_clean'),
				array('field'=>'email', 'label'=>'이메일', 'rules'=>'trim|required|max_length[50]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$vars = array(
					'_TITLE_'		=> '세미나신청',
					'_BODY_'		=> 'edu/edu_'. $mode .'_confirm',
					'_JS_'			=> array('jvalidate', 'layerDlg'),
			
					'm1'			=> '06',
					'm2'			=> '02',
					'm1_tt'			=> '교육연구사업',
					'm2_tt'			=> '세미나신청',
						
					'id'			=> $id,
					'qstr'			=> $qstr,
					'back_href'		=> '/edu/view/'. $id . $qstr
			);
			
			$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
		}
		else {
			$name = $this->input->post('name');
			$phone = $this->input->post('phone');
			$email = $this->input->post('email');
			
			$reg_where .= ' AND reg_edu_id='. $id;
			
			if($row = $this->M_edu->reg_seach($name, $phone, $email, $reg_where)) {
				$cfm_ids = array($row['reg_id']);
				if($this->session->userdata('ss_reg_confirm')) {
					$cfm_ids = array_merge($cfm_ids, unserialize($this->session->userdata('ss_reg_confirm')));
				}
				
				$this->session->set_userdata('ss_reg_confirm', serialize($cfm_ids));
				die( json_encode(array('id' => $row['reg_id'])) );
			}
			else {
				die('001');
			}
		}
	}
	
	function printPage($id='', $mode='') {
		if($mode != 'cert' && $mode != 'receipt') {
			show_404();
		}
		
		$cfm_ids = $this->session->userdata('ss_reg_confirm') ? unserialize($this->session->userdata('ss_reg_confirm')) : array();
		
		if(!$row = $this->M_edu->edu_reg_row($id)) {
			die('등록된 자료가 없습니다.');
		}
		else if(array_search($id, $cfm_ids) === FALSE) {
			show_404();
		}
		
		$row['eduDate'] = preg_replace('/\-/', '. ', (strtotime($row['reg_eduSdate']) > 0 ? $row['reg_eduSdate'] : '') . (strtotime($row['reg_eduSdate']) > 0 && strtotime($row['reg_eduEdate']) > 0 ? ' ~ ' : '') .(strtotime($row['reg_eduEdate']) > 0 ? $row['reg_eduEdate'] : ''));
		$row['eduTime'] = $row['reg_eduTime'] ? '(총 '. $row['reg_eduTime'] .'  시간)' : '';
		
		if($mode == 'cert') {
			$regCode = 'SRH'. substr($row['pd_eduEdate'], 0, 4) .'E-'. sprintf('%04d', $row['pd_id']) .'-'. $row['reg_cert_code'];
		}
		else if($mode == 'receipt') {
			$regCode = 'SRH'. substr($row['pd_eduEdate'], 0, 4) .'E-'. sprintf('%04d', $row['pd_id']) .'-'. $row['reg_pay_code'];
		}
		
		// 세션을 지워 해당 페이지를 다시 로딩 못하게... (새로고침 안되어 주석 처리함)
		//$cfm_ids = array_diff($cfm_ids, array($id));
		//$this->session->set_userdata('ss_reg_confirm', serialize($cfm_ids));
		
		$vars = array(
				'_TITLE_'		=> '수료증발급',
				'_BODY_'		=> 'edu/edu_'. $mode .'_print',
				'_JS_'			=> array('jprintArea'),
						
				'row'			=> $row,
				'regCode'		=> $regCode,
				'now_date'		=> date('Y년 m월 d일', strtotime($row['pd_eduSdate']))
		);
			
		$this->load->view('layout/layout_blank', $vars);
	}
}
?>
