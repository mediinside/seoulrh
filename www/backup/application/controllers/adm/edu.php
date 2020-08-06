<?php
class Edu extends CI_Controller {
	var $edu_cate = '';
	var $img_max = array('w' => 1024, 'h' => 768);		// 최대 이미지 사이즈. 에디터 제외
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_layout', ADM_F.'/M_a_edu', 'M_upload_files', 'M_edu'));
	}
		
	function lists() {
		include "init.php";
		
		$this->load->helper(array('sideview', 'search', 'textual'));
		$this->load->library('pagination');
		
		// 서치
		$seg = new search_seg;
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		$cate = setValue('1', $seg->get_seg('cate'));
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'pd_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		$dqstr = qstr_rep($seg->get_qstr(), 'cate');
		
		// 페이징
		$pageConf['suffix'] = $qstr;
		$pageConf['uri_segment'] = $seg->get_order('page');
		$pageConf['base_url'] =	RT_PATH.'/'.ADM_F.'/edu/lists/page/';
		$pageConf['per_page'] =	15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_a_edu->list_result($cate, $sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['no'] =			$result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =			$i % 2;

			$list[$i]['pd_sdatetime'] = strtotime($row['pd_sdatetime']) > 0 ? $row['pd_sdatetime'] : '시작일 제한없음';
			$list[$i]['pd_edatetime'] = strtotime($row['pd_edatetime']) > 0 ? $row['pd_edatetime'] : '마감일 제한없음';
			
			$list[$i]['pd_cate'] =		isset($this->full_cate[$list[$i]['pd_cate']]) ? $this->full_cate[$list[$i]['pd_cate']] : '';
			$list[$i]['pd_regdate'] =	date('Y-m-d', strtotime($list[$i]['pd_regdate']));
			$list[$i]['soldout_chk'] =	$list[$i]['pd_soldout'] ? "checked='checked'" : '';
			$list[$i]['hidden_chk'] =	$list[$i]['pd_hidden'] ? "checked='checked'" : '';
			
			$list[$i]['s_mod'] =		icon('수정', 'edu/form/u/'.$list[$i]['pd_id'].'?cate='. $cate .'&qstr='. $dqstr);
			$list[$i]['s_del'] =		icon('삭제', "javascript:post_s('".ADM_F."/edu/delete', {id:'".$list[$i]['pd_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
			$list[$i]['s_view'] =		icon('보기', 'javascript:layerWin(\'/'.ADM_F.'/edu/reg_lists/id/'.$list[$i]['pd_id'].'\',\'regView\',870,700);');
		}
		
		$sort_link['pd_name'] =		sort_link('pd_name') ."?qstr=$qstr";
		$sort_link['pd_md'] =		sort_link('pd_price') ."?qstr=$qstr";
		$sort_link['pd_cate'] =		sort_link('pd_cate') ."?qstr=$qstr";
		$sort_link['pd_soldout'] =	sort_link('pd_soldout') ."?qstr=$qstr";
		$sort_link['pd_regdate'] =	sort_link('pd_regdate') ."?qstr=$qstr";
		
		$vars = array(
			'_TITLE_'		=> '강좌 리스트',
			'_BODY_'		=> ADM_F.'/edu/edu_list',
			
			'token'			=> $token,
			'cate'			=> $cate,
			'list'			=> $list,
			'qstr'			=> $qstr,
			'categories'	=> $this->M_edu->cate,
				
			's_add'			=> icon('추가', "edu/form".'?cate='. $cate .'&qstr='. $dqstr),
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			'total_cnt'		=> $result['total_cnt'],
			'paging'		=> $this->pagination->create_links(),
			'sort_link'		=> $sort_link
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
		
	function reg_lists() {
		include "init.php";
		
		$this->load->helper(array('sideview', 'search', 'textual', 'form'));
		$this->load->library('pagination');
		
		// 서치
		$seg = new search_seg(4);
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		$id = $seg->get_seg('id');
		
		if(!$id) {
			alert('선택된 교육이 없습니다.');
		}
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'reg_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		// 페이징
		$pageConf['suffix'] = qstr_rep($qstr, 'id');
		$pageConf['uri_segment'] = $seg->get_order('page');
		$pageConf['base_url'] =	RT_PATH.'/'.ADM_F.'/edu/reg_lists/id/'. $id .'/page/';
		$pageConf['per_page'] =	15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_a_edu->reg_list_result($id, $sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['no'] =			$result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =			$i % 2;

			$list[$i]['reg_regdate'] =	date('Y-m-d', strtotime($list[$i]['reg_regdate']));
			$list[$i]['sel_pay'] =		form_dropdown('reg_pay['. $list[$i]['reg_id'] .']', $this->M_edu->pay_arr, $list[$i]['reg_pay']);
			$list[$i]['sel_cert'] =		form_dropdown('reg_cert['. $list[$i]['reg_id'] .']', $this->M_edu->cert_arr, $list[$i]['reg_cert']);
			
			$list[$i]['s_mod'] =		icon('수정', 'edu/reg_form/u/'.$list[$i]['reg_id']."?edu_id=$id");
			$list[$i]['s_del'] =		icon('삭제', "javascript:post_s('".ADM_F."/edu/reg_delete', {id:'".$list[$i]['reg_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
			$list[$i]['s_view'] =		'';
		}
		
		$vars = array(
			'_TITLE_'			=> '강좌 리스트',
			'_BODY_'			=> ADM_F.'/edu/edu_reg_list',
			
			'token'				=> $token,
			'list'				=> $list,
			'qstr'				=> $qstr,
			'id'				=> $id,
			'categories'		=> $this->M_edu->cate,
				
			's_add'				=> icon('추가', "edu/reg_form?edu_id=". $id),
			'sfl'				=> $sfl,
			'stx'				=> $stx,
			'total_cnt'			=> $result['total_cnt'],
			'paging'			=> $this->pagination->create_links(),

			'sort_reg_name'		=> sort_link('reg_name'),
			'sort_reg_phone'	=> sort_link('reg_phone'),
			'sort_reg_email'	=> sort_link('reg_email'),
			'sort_reg_regdate'	=> sort_link('reg_regdate'),
			'sort_reg_pay'		=> sort_link('reg_pay'),
			'sort_reg_cert'		=> sort_link('reg_cert')
		);
		
		$this->load->view('layout/layout_blank_admin', $vars);
	}
	
	function form($w = '', $id = '') {
		include "init.php";
		
		$this->load->model('M_editor');
		$this->load->library('form_validation');
		$this->load->helper('search');
		
		$seg = new search_seg;
		$cate = $this->input->get('cate');
		$qstr = $seg->get_qstr();
		
		$config = array(
				array('field'=>'pd_name', 'label'=>'강좌명', 'rules'=>'trim|required|max_length[150]|xss_clean'),
				array('field'=>'pd_cate', 'label'=>'카테고리', 'rules'=>'trim|required|xss_clean'),
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_edu->table);
				$row['pd_cate'] = $cate;
			}
			else if ($w == 'u') {
				$title = '수정';

				$row = $this->M_edu->row($id);
				if (!isset($row['pd_id']))
					alert('등록된 자료가 없습니다.');
				
				$row['files'] = $this->M_upload_files->get_files($this->M_edu->table, $id, '*');
				$row['pd_sdatetime'] = strtotime($row['pd_sdatetime']) > 0 ? $row['pd_sdatetime'] : '';
				$row['pd_edatetime'] = strtotime($row['pd_edatetime']) > 0 ? $row['pd_edatetime'] : '';
				$row['pd_eduSdate'] = strtotime($row['pd_eduSdate']) > 0 ? $row['pd_eduSdate'] : '';
				$row['pd_eduEdate'] = strtotime($row['pd_eduEdate']) > 0 ? $row['pd_eduEdate'] : '';
				
				$row['pd_schedule'] = unserialize($row['pd_schedule']);
				if(isset($row['pd_schedule']['stime']) && is_array($row['pd_schedule']['stime'])) {
					foreach($row['pd_schedule']['stime'] as $key => $val){
						foreach($val as $k => $v){
							$row['pd_schedule']['text'][$key][$k] = preg_replace('/\n/', '\\n', $row['pd_schedule']['text'][$key][$k]);
						}
					}
				}
			}
			
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_edu->table, $id);
				$edt_info = json_encode($edt_info);
			}
			
			// 에디터 매개변수
			$edt = array(
				'content' => $row['pd_content'],
				'edt_info' => isset($edt_info) ? $edt_info : '[]',
				'wr_table' => $this->M_a_edu->table,
				'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 1;
			$editor = $this->load->view('editor/editor', $edt, TRUE);
			$content = ''; // 그냥 비우기
			
			// 체크박스 & 셀렉트 메뉴
			$row['hidden_chk'] = $row['pd_hidden'] ? "checked='checked'" : '';
			$row['soldout_chk'] = $row['pd_soldout'] ? "checked='checked'" : '';

			$vars = array(
				'_TITLE_'		=> '강좌 '.$title,
				'_BODY_'		=> ADM_F.'/edu/edu_form',
				'_CSS_'			=> array('editor', 'jquery-ui'),
				'_JS_'			=> array('../editor/js/editor_loader','jvalidate','jvalid_ext', 'jquery-ui.min', 'jtimepicker'),
				
				'w'				=> $w,
				'qstr'			=> '/cate/'. $cate . $qstr,
				'token'			=> get_token(),
				'editor'		=> $editor,
				
				'cate_sel'		=> form_dropdown('pd_cate', $this->M_edu->cate, $row['pd_cate']),
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);

		}
		else {
			check_token();
						
			$this->load->model('M_editor');
			
			$w = $this->input->post('w');
			$id = $this->input->post('pd_id');
			$pd_content = $this->input->post('wr_content');
			$pd_options = $this->input->post('pd_options');
			$day_stime = $this->input->post('day_stime');
			$day_etime = $this->input->post('day_etime');
			$day_text = $this->input->post('day_text');
			$delFile = $this->input->post('delPdFile');
			$cate = $this->input->post('pd_cate');
			
			$row = $this->M_edu->row($id);
			if (!$w) {
				if (isset($row['pd_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$pd_schedule = serialize(array(
					'stime' => $day_stime,
					'etime'	=> $day_etime,
					'text'	=> $day_text
			));
			
			$data = array(
					'pd_cate'		=> $cate,
					'pd_name'		=> $this->input->post('pd_name'),
					'pd_sdatetime'	=> $this->input->post('pd_sdatetime'),
					'pd_edatetime'	=> $this->input->post('pd_edatetime'),
					'pd_eduSdate'	=> $this->input->post('pd_eduSdate'),
					'pd_eduEdate'	=> $this->input->post('pd_eduEdate'),
					'pd_eduTime'	=> $this->input->post('pd_eduTime'),
					'pd_location'	=> $this->input->post('pd_location'),
					'pd_target'		=> $this->input->post('pd_target'),
					'pd_price'		=> $this->input->post('pd_price'),
					'pd_soldout'	=> $this->input->post('pd_soldout'),
					'pd_schedule'	=> $pd_schedule,
					'pd_mdydate'	=> TIME_YMDHIS
			);
			
			if($id = $this->M_a_edu->record($w, $data)) {
				$this->M_a_edu->set_options($id, $pd_options);
			}
			
			// 폼 파일 삭제
			if(is_array($delFile)) {
				foreach($delFile as $field => $no) {
					$this->M_upload_files->file_delete($this->M_a_edu->table, $id, $no);
					$this->M_a_edu->db->update($this->M_a_edu->table, array($field => ''), array('pd_id' => $id));
				}
			}
			
			// 폼 파일 업로드
			if(isset($_FILES['pd_image']) && $_FILES['pd_image']['tmp_name']) {
				// 이미지 파일이 아니면 제거
				if(is_array($_FILES['pd_image']['tmp_name'])) {
					foreach($_FILES['pd_image']['tmp_name'] as $key => $val) {
						if($val) {
							$size = @getimagesize($val);
							$fldName[$val] = 'pd_image'.$key;
							
							// gif, jpg, png 형식이 아니거나 이미지 사이즈가 너무 크면 제거
							if($size[2] == 0 || $size[2] > 3) {
								alert_continue($_FILES['pd_image']['name'][$key].': 이 파일은 업로드 할 수 없는 형식입니다.');
								unset($_FILES['pd_image']['tmp_name'][$key]);
							}
							else if($size[0] > $this->img_max['w'] || $size[1] > $this->img_max['h']) {
								alert_continue($_FILES['pd_image']['name'][$key].': 이미지의 크기가 너무 큽니다.\\n\\n최대 W: '.$this->img_max['w'].'\\n최대 H: '.$this->img_max['h']);
								unset($_FILES['pd_image']['tmp_name'][$key]);
							}
						}
					}
				}
				else {
					$size = @getimagesize($_FILES['pd_image']['tmp_name']);
					if($size[2] == 0) unset($_FILES['pd_image']);
				}
				
				$pd_nos = $this->M_upload_files->form_upload($this->M_a_edu->table, $id, $_FILES['pd_image']);
				
				// 이미지 DB 업데이트
				if(count($pd_nos) > 0) {
					foreach($pd_nos as $no => $filename) {
						if($row[$fldName[$filename]]) {
							$this->M_upload_files->file_delete($this->M_a_edu->table, $id, $row[$fldName[$filename]]);
						}
						$this->M_a_edu->db->update($this->M_a_edu->table, array($fldName[$filename] => $no), array('pd_id' => $id));
					}
				}
			}
			
			// 에디터 파일 업로드
			$pd_content = $this->M_editor->uploadFile($this->M_a_edu->table, $id, $pd_content);
			
			// 에디터 내용에서 첨부 파일 경로 수정 (temp -> real)
			$this->M_a_edu->db->update($this->M_a_edu->table, array('pd_content' => $pd_content), array('pd_id' => $id));
			
			goto_url(ADM_F.'/edu/form/u/'. $id .'?cate='. $cate .'&qstr='. $qstr);
		}
	}
	
	function reg_form($w = '', $id = '') {
		include "init.php";
		
		$this->load->library('form_validation');

		$edu_id = $this->input->get('edu_id');
		
		$config = array(
				array('field'=>'reg_name', 'label'=>'국문 이름', 'rules'=>'trim|required|max_length[50]|xss_clean'),
				array('field'=>'reg_name_en', 'label'=>'영문 이름', 'rules'=>'trim|required|max_length[50]|xss_clean'),
				array('field'=>'reg_sex', 'label'=>'성별', 'rules'=>'trim|required|xss_clean'),
				array('field'=>'reg_birth', 'label'=>'생일', 'rules'=>'trim|required|xss_clean'),
				array('field'=>'reg_phone[]', 'label'=>'연락처', 'rules'=>'trim|required|xss_clean'),
				array('field'=>'reg_email', 'label'=>'이메일', 'rules'=>'trim|required|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_edu->table_reg);
				
				$row['phone'] = array_fill(0, 3, '');
				$row['inflow'] = array();
			}
			else if ($w == 'u') {
				$title = '수정';

				$row = $this->M_a_edu->reg_row($id);
				if (!isset($row))
					alert('등록된 자료가 없습니다.');
				
				$row['phone'] = explode('-', $row['reg_phone']);
				$row['inflow'] = explode('|', $row['reg_inflow']);
			}
			
			$vars = array(
				'_TITLE_'		=> '강좌 '.$title,
				'_BODY_'		=> ADM_F.'/edu/edu_reg_form',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate','jvalid_ext', 'jquery-ui.min', 'jtimepicker'),
				
				'w'				=> $w,
				'edu_id'		=> $edu_id,		
				
				'row'			=> $row
			);

			$this->load->view('layout/layout_blank_admin', $vars);

		}
		else {
			$w = $this->input->post('w');
			$id = $this->input->post('reg_id');
			
			$row = $this->M_a_edu->reg_row($id);
			if(!$w) {
				if($row)
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			
			$data = array(
					'reg_name'		=> $this->input->post('reg_name'),
					'reg_name_en'	=> $this->input->post('reg_name_en'),
					'reg_sex'		=> $this->input->post('reg_sex'),
					'reg_birth'		=> $this->input->post('reg_birth'),
					'reg_phone'		=> implode('-', $this->input->post('reg_phone')),
					'reg_email'		=> setValue('', $this->input->post('reg_email')),
					'reg_job'		=> $this->input->post('reg_job'),
					'reg_school'	=> $this->input->post('reg_school'),
					'reg_company'	=> $this->input->post('reg_company'),
					'reg_grade'		=> $this->input->post('reg_grade'),
					'reg_pay_price'	=> $this->input->post('reg_pay_price'),
					'reg_inflow'	=> $this->input->post('reg_inflow') ? implode('|', $this->input->post('reg_inflow')) : '',
					'reg_message'	=> $this->input->post('reg_message'),
					'reg_mdydate'	=> TIME_YMDHIS
			);
			
			if(!$w) {
				$data['reg_edu_id'] = $edu_id;
			}

			if(!$id = $this->M_a_edu->reg_record($w, $data)) {
				alert('저장 실패!');
			}
			
			goto_url(ADM_F.'/edu/reg_form/u/'.$id.'?edu_id='.$edu_id);
		}
	}
	
	function update() {
		check_token(URL);
	
		$ids = $this->input->post('chk');
		$pd_soldout = $this->input->post('pd_soldout');
		$pd_hidden = $this->input->post('pd_hidden');
	
		if(!$ids)
			return false;
	
		$data = array(
				'pd_soldout' => $pd_soldout,
				'pd_hidden' => $pd_hidden
		);
	
		$this->M_a_edu->list_update($ids, $data);
	
		goto_url(URL);
	}
	
	function reg_update() {
		check_token(URL);

		$edu_id = $this->input->post('edu_id');
		$ids = $this->input->post('chk');
		$pay_price = $this->input->post('reg_pay_price');
		$pay = $this->input->post('reg_pay');
		$cert = $this->input->post('reg_cert');
		
		if(!$ids)
			return false;
		
		$edu = $this->M_edu->row($edu_id, 'pd_id, pd_max_pay, pd_max_cert, pd_eduSdate, pd_eduEdate, pd_eduTime');
		
		$pay_code = $cert_code = $eduSdate = $eduEdate = $eduTime = array();
		$rows = $this->M_a_edu->reg_lists($ids);
		foreach($rows AS $row) {
			if($row['reg_pay'] == 0 && $pay[$row['reg_id']] == 1) {
				$pay_code[$row['reg_id']] = sprintf('%03d', ++$edu['pd_max_pay']);
			}
			else {
				$pay_code[$row['reg_id']] = $row['reg_pay_code'];
			}
			
			if($row['reg_cert'] == 0 && $cert[$row['reg_id']] == 1) {
				$cert_code[$row['reg_id']] = sprintf('%03d', ++$edu['pd_max_cert']);
				$eduSdate[$row['reg_id']] = $edu['pd_eduSdate'];
				$eduEdate[$row['reg_id']] = $edu['pd_eduEdate'];
				$eduTime[$row['reg_id']] = $edu['pd_eduTime'];
			}
			else {
				$cert_code[$row['reg_id']] = $row['reg_cert_code'];
				$eduSdate[$row['reg_id']] = $row['reg_eduSdate'];
				$eduEdate[$row['reg_id']] = $row['reg_eduEdate'];
				$eduTime[$row['reg_id']] = $row['reg_eduTime'];
			}
		}
		
		$data = array(
				'reg_pay_price' => $pay_price,
				'reg_pay' => $pay,
				'reg_cert' => $cert,
				'reg_pay_code' => $pay_code,
				'reg_cert_code' => $cert_code,
				'reg_eduSdate' => $eduSdate,
				'reg_eduEdate' => $eduEdate,
				'reg_eduTime' => $eduTime
		);
		
		$this->M_a_edu->reg_list_update($ids, $data);
		$this->M_a_edu->list_update(array($edu_id), array('pd_max_pay' => array($edu_id => $edu['pd_max_pay']), 'pd_max_cert' => array($edu_id => $edu['pd_max_cert'])));
	
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
			$this->M_upload_files->file_delete($this->M_a_edu->table, $i);
			$this->M_a_edu->delete($i);
		}
		
		goto_url(URL);
	}
	
	function reg_delete() {
		check_token(URL);
		
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
	
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;
		
		$this->M_a_edu->reg_delete($ids);
		
		goto_url(URL);
	}
}
?>
