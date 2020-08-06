<?php
class Recruit extends CI_Controller {
	var $recruit_cate = '';
	var $img_max = array('w' => 1024, 'h' => 768);		// 최대 이미지 사이즈. 에디터 제외
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_layout', ADM_F.'/M_a_recruit', 'M_upload_files', 'M_recruit'));
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
		if (!$sst) $sst = 'recr_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		$dqstr = qstr_rep($seg->get_qstr(), 'cate');
		
		// 페이징
		$pageConf['suffix'] =	$qstr;
		$pageConf['base_url'] =	RT_PATH.'/'.ADM_F.'/recruit/lists/page/';
		$pageConf['per_page'] =	15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_a_recruit->list_result($cate, $sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['no'] =			$result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =			$i % 2;
			
			$list[$i]['recr_sdatetime'] = strtotime($row['recr_sdatetime']) > 0 ? $row['recr_sdatetime'] : '시작일 제한없음';
			$list[$i]['recr_edatetime'] = strtotime($row['recr_edatetime']) > 0 ? $row['recr_edatetime'] : '마감일 제한없음';
			
			$list[$i]['recr_cate'] =	isset($this->full_cate[$list[$i]['recr_cate']]) ? $this->full_cate[$list[$i]['recr_cate']] : '';
			$list[$i]['recr_regdate'] =	date('Y-m-d', strtotime($list[$i]['recr_regdate']));
			$list[$i]['soldout_chk'] =	$list[$i]['recr_soldout'] ? "checked='checked'" : '';
			$list[$i]['hidden_chk'] =	$list[$i]['recr_hidden'] ? "checked='checked'" : '';
			
			$list[$i]['s_mod'] =		icon('수정', 'recruit/form/u/'.$list[$i]['recr_id'].'?cate='. $cate .'&qstr='. $dqstr);
			$list[$i]['s_del'] =		icon('삭제', "javascript:post_s('".ADM_F."/recruit/delete', {id:'".$list[$i]['recr_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
			$list[$i]['s_view'] =		'';
		}
		
		$sort_link['recr_subject'] =	sort_link('recr_subject') ."?qstr=$qstr";
		$sort_link['recr_cate'] =		sort_link('recr_cate') ."?qstr=$qstr";
		$sort_link['recr_soldout'] =	sort_link('recr_soldout') ."?qstr=$qstr";
		$sort_link['recr_regdate'] =	sort_link('recr_regdate') ."?qstr=$qstr";
		
		$vars = array(
			'_TITLE_'		=> '모집공고 리스트',
			'_BODY_'		=> ADM_F.'/recruit/recruit_list',
			
			'token'			=> $token,
			'cate'			=> $cate,
			'list'			=> $list,
			'qstr'			=> $qstr,
			'categories'	=> $this->M_recruit->cate,
				
			's_add'			=> icon('추가', "recruit/form".'?cate='. $cate .'&qstr='. $dqstr),
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
	
		$this->load->helper(array('sideview', 'search', 'textual'));
		$this->load->library('pagination');
	
		// 서치
		$seg = new search_seg();
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		$id = $seg->get_seg('id');
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'rreg_id';
		if (!$sod) $sod = 'desc';
	
		$qstr = $seg->get_qstr();
	
		// 페이징
		$pageConf['suffix'] =	$qstr;
		$pageConf['base_url'] =	RT_PATH.'/'.ADM_F.'/recruit/lists_reg/page/';
		$pageConf['per_page'] =	15;
	
		$offset = ($page - 1) * $pageConf['per_page'];
	
		// 데이터
		$result = $this->M_a_recruit->reg_list_result($id, $sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
	
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
	
		$token = get_token();
	
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['no'] =			$result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =			$i % 2;
				
			$list[$i]['regdate'] =		date('Y-m-d', strtotime($list[$i]['rreg_regdate']));
			
			$list[$i]['s_mod'] =		icon('수정', 'recruit/reg_form/u/'.$list[$i]['rreg_id'].$qstr);
			$list[$i]['s_del'] =		icon('삭제', "javascript:post_s('".ADM_F."/recruit/reg_delete', {id:'".$list[$i]['rreg_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
			$list[$i]['s_view'] =		'';
		}
	
		$vars = array(
				'_TITLE_'		=> '강좌 리스트',
				'_BODY_'		=> ADM_F.'/recruit/recruit_reg_list',
					
				'token'			=> $token,
				'list'			=> $list,
				'qstr'			=> $qstr,
				'id'			=> $id,
				'categories'	=> $this->M_recruit->cate,
	
				's_add'			=> icon('추가', "recruit/reg_form?recruit_id=". $id),
				'sfl'			=> $sfl,
				'stx'			=> $stx,
				'total_cnt'		=> $result['total_cnt'],
				'paging'		=> $this->pagination->create_links()
		);
	
		$this->load->view('layout/layout_admin', $vars);
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
				array('field'=>'recr_subject', 'label'=>'제목', 'rules'=>'trim|required|max_length[150]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_recruit->table);
				$row['recr_cate'] = $cate;
			}
			else if ($w == 'u') {
				$title = '수정';

				$row = $this->M_recruit->row($id);
				if (!isset($row['recr_id']))
					alert('등록된 자료가 없습니다.');
				
				$row['recr_sdatetime'] = strtotime($row['recr_sdatetime']) > 0 ? $row['recr_sdatetime'] : '';
				$row['recr_edatetime'] = strtotime($row['recr_edatetime']) > 0 ? $row['recr_edatetime'] : '';
			}
			
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_recruit->table, $id);
				$edt_info = json_encode($edt_info);
			}
			
			// 에디터 매개변수
			$edt = array(
				'content' => $row['recr_content'],
				'edt_info' => isset($edt_info) ? $edt_info : '[]',
				'wr_table' => $this->M_a_recruit->table,
				'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 1;
			$editor = $this->load->view('editor/editor', $edt, TRUE);
			$content = ''; // 그냥 비우기
			
			// 체크박스 & 셀렉트 메뉴
			$row['hidden_chk'] = $row['recr_hidden'] ? "checked='checked'" : '';
			$row['soldout_chk'] = $row['recr_soldout'] ? "checked='checked'" : '';

			$vars = array(
				'_TITLE_'		=> '모집공고 '.$title,
				'_BODY_'		=> ADM_F.'/recruit/recruit_form',
				'_CSS_'			=> array('editor', 'jquery-ui'),
				'_JS_'			=> array('../editor/js/editor_loader','jvalidate','jvalid_ext', 'jquery-ui.min', 'jtimepicker'),
				
				'w'				=> $w,
				'qstr'			=> '/cate/'. $cate . $qstr,
				'token'			=> get_token(),
				'editor'		=> $editor,
				
				'cate_sel'		=> form_dropdown('recr_cate', $this->M_recruit->cate, $row['recr_cate']),
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);

		}
		else {
			check_token();
						
			$this->load->model('M_editor');
			
			$w = $this->input->post('w');
			$id = $this->input->post('recr_id');
			$recr_content = $this->input->post('wr_content');
			$cate = $this->input->post('recr_cate');
			
			$row = $this->M_recruit->row($id);
			if (!$w) {
				if (isset($row['recr_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
						
			$data = array(
					'recr_cate'			=> $cate,
					'recr_subject'		=> $this->input->post('recr_subject'),
					'recr_sdatetime'	=> $this->input->post('recr_sdatetime'),
					'recr_edatetime'	=> $this->input->post('recr_edatetime'),
					'recr_soldout'		=> $this->input->post('recr_soldout'),
					'recr_mdydate'		=> TIME_YMDHIS
			);
			
			$id = $this->M_a_recruit->record($w, $data);
			
			// 에디터 파일 업로드
			$recr_content = $this->M_editor->uploadFile($this->M_a_recruit->table, $id, $recr_content);
			
			// 에디터 내용에서 첨부 파일 경로 수정 (temp -> real)
			$this->M_a_recruit->db->update($this->M_a_recruit->table, array('recr_content' => $recr_content), array('recr_id' => $id));
			
			goto_url(ADM_F.'/recruit/form/u/'. $id .'?cate='. $cate .'&qstr='. $qstr);
		}
	}

	function reg_form($w = '', $id = '') {
		include "init.php";
	
		$this->load->library('form_validation');
	
		$config = array(
				array('field'=>'rreg_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[50]|xss_clean')
		);
	
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_recruit->table_reg);
	
				$row['phone'] = array_fill(0, 3, '');
			}
			else if ($w == 'u') {
				$title = '수정';
	
				$row = $this->M_a_recruit->reg_row($id);
				if (!isset($row))
					alert('등록된 자료가 없습니다.');
	
				$row['phone'] = explode('-', $row['rreg_phone']);
				$row['license'] = explode('|', $row['rreg_license']);
			}
			
			$vars = array(
					'_TITLE_'		=> '강좌 '.$title,
					'_BODY_'		=> ADM_F.'/recruit/recruit_reg_form',
					'_JS_'			=> array('jvalidate'),
	
					'w'				=> $w,
	
					'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
	
		}
		else {
			$w = $this->input->post('w');
			$id = $this->input->post('rreg_id');
			$delFile = $this->input->post('delFile');
				
			$row = $this->M_a_recruit->reg_row($id);
			if(!$w) {
				if($row)
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
				
				
			$data = array(
					'rreg_name'		=> $this->input->post('rreg_name'),
					'rreg_phone'	=> implode('-', $this->input->post('rreg_phone')),
					'rreg_email'	=> setValue('', $this->input->post('rreg_email')),
					'rreg_mdydate'	=> TIME_YMDHIS
			);

			$license_nos = explode('|', $row['rreg_license']);
			
			if(!$id = $this->M_a_recruit->reg_record($w, $data)) {
				alert('저장 실패!');
			}

			// 폼 파일 삭제
			if(is_array($delFile)) {
				foreach($delFile as $field => $no) {
					$new_val = '';
					if(preg_match('/^rreg_license_/', $field)) {
						$field = 'rreg_license';
						$license_nos = array_diff($license_nos, array($no));
						$new_val = implode('|', $license_nos);
					}
					$this->M_upload_files->file_delete($this->M_recruit->table_reg, $id, $no);
					$this->M_recruit->db->update($this->M_recruit->table_reg, array($field => $new_val), array('rreg_id' => $id));
				}
			}
			
			/* 첨부파일 업로드 */
			$nos_data = array();
			
			if(isset($_FILES['rreg_resume']) && $_FILES['rreg_resume']['tmp_name']) {
				$nos_data['rreg_resume'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_resume']);
			}
			if(isset($_FILES['rreg_introduction']) && $_FILES['rreg_introduction']['tmp_name']) {
				$nos_data['rreg_introduction'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_introduction']);
			}
			if(isset($_FILES['rreg_transcript']) && $_FILES['rreg_transcript']['tmp_name']) {
				$nos_data['rreg_transcript'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_transcript']);
			}
			if(isset($_FILES['rreg_diploma']) && $_FILES['rreg_diploma']['tmp_name']) {
				$nos_data['rreg_diploma'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_diploma']);
			}
			if(isset($_FILES['rreg_registration']) && $_FILES['rreg_registration']['tmp_name']) {
				$nos_data['rreg_registration'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_registration']);
			}
			if(isset($_FILES['rreg_family']) && $_FILES['rreg_family']['tmp_name']) {
				$nos_data['rreg_family'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_family']);
			}
			if(isset($_FILES['rreg_experience']) && $_FILES['rreg_experience']['tmp_name']) {
				$nos_data['rreg_experience'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_experience']);
			}
			if(isset($_FILES['rreg_license']) && $_FILES['rreg_license']['tmp_name']) {
				$nos_data['rreg_license'] = $this->M_upload_files->form_upload($this->M_recruit->table_reg, $id, $_FILES['rreg_license']);
				$nos_data['rreg_license'] = array_flip(array_merge($license_nos, array_flip($nos_data['rreg_license'])));
			}
			
			foreach($nos_data AS $key => $val) {
				$arr = array_flip($val);
				$nos_data[$key] = implode('|', $arr);
			}
			
			if($nos_data) {
				$this->M_recruit->db->update($this->M_recruit->table_reg, $nos_data, array('rreg_id' => $id));
			}
			
			goto_url(ADM_F.'/recruit/reg_form/u/'.$id);
		}
	}
	
	function download($id, $no) {
		$this->load->helper('download');
		
		$file = $this->M_upload_files->get_file($this->M_recruit->table_reg, $id, $no);
		if (!isset($file['uf_file']))
			alert_close('파일 정보가 존재하지 않습니다.');
		
		$fileDir = $this->M_upload_files->get_dir($this->M_recruit->table_reg);
		$filepath = addslashes($fileDir['data_path'].'/'.$file['uf_file']);
	
		if (file_exists($filepath)) {
			if (preg_match("/^utf/i", $this->config->item('charset')))
				$original = urlencode($file['uf_source']);
			else
				$original = $file['uf_source'];
				
			if (!force_download($original, file_get_contents($filepath)))
				alert('파일을 찾을 수 없습니다.');
		}
		else
			alert('파일을 찾을 수 없습니다.');
	}
	
	function update() {
		check_token(URL);
	
		$ids = $this->input->post('chk');
		$recr_soldout = $this->input->post('recr_soldout');
		$recr_hidden = $this->input->post('recr_hidden');
	
		if(!$ids)
			return false;
	
		$data = array(
				'recr_soldout' => $recr_soldout,
				'recr_hidden' => $recr_hidden
		);
	
		$this->M_a_recruit->list_update($ids, $data);
	
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
			$this->M_upload_files->file_delete($this->M_a_recruit->table, $i);
			$this->M_a_recruit->delete($i);
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
		
		foreach($ids as $i) {
			$this->M_upload_files->file_delete($this->M_a_recruit->table_reg, $i);
			$this->M_a_recruit->reg_delete($i);
		}
		
		goto_url(URL);
	}
}
?>
