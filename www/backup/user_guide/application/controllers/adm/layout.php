<?php
class Layout extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_layout', ADM_F.'/M_a_dbvars', 'M_banner'));
		$this->load->helper('textual');
	}
	
	function lists() {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper('search');

		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sst  = $seg->get_seg('sst');
		$sod  = $seg->get_seg('sod');
		$sfl  = $seg->get_seg('sfl');
		$stx  = $seg->get_seg('stx');

		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'ly_id';
		if (!$sod) $sod = 'asc';
		
		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/layout/lists/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_a_layout->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$layout_using_cnt = $this->M_a_layout->get_using_cnt();
		
		$token = get_token();
		foreach ($list as $i => $row) {
			//$list[$i]['s_pre'] = icon('보기', "javascript:win_open('content/".$row['ly_id']."', 'content', 'left=".$row['ly_x']."px,top=".$row['ly_y']."px,width=".$row['ly_width']."px,height=".$row['ly_height']."px,scrollbars=0');");
			$list[$i]['s_pre'] = '';
			$list[$i]['s_mod'] = icon('수정', 'layout/form/u/'.$row['ly_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/layout/delete', {id:'".$row['ly_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['using_cnt'] = isset($layout_using_cnt[$row['ly_id']]) ? $layout_using_cnt[$row['ly_id']] : 0;
			$list[$i]['ly_mdydate_conv'] = date('Y-m-d', strtotime($row['ly_mdydate']));
			$list[$i]['ly_regdate_conv'] = date('Y-m-d', strtotime($row['ly_regdate']));
		}
		
		$vars = array(
			'_TITLE_'		=> '레이아웃 관리',
			'_BODY_'		=> ADM_F.'/layout/layout_list',

			'token' => $token,

			'list' => $list,
			's_add' => icon('추가', 'layout/form'),

			'sfl' => $sfl,
			'stx' => $stx,

			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination->create_links(),

			'sort_ly_file' => sort_link('ly_file'),
			'sort_ly_name' => sort_link('ly_name'),
			'sort_ly_hidden' => sort_link('ly_hidden')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $id='') {
		include "init.php";
		
		$this->load->model('M_editor');
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'ly_file',	'label'=>'아이디',		'rules'=>'trim|required|alpha_dash|max_length[50]|xss_clean'),
			array('field'=>'ly_name',		'label'=>'이름',			'rules'=>'trim|required|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_layout->table);
				$html_code = FALSE;
				
				$row['ly_hidden'] = 0;
			}
			else if ($w == 'u') {
				$title = '수정';
				$row = $this->M_layout->row($id);
				if (!isset($row['ly_id']))
					alert('등록된 자료가 없습니다.');
				
				$html_code = encodeVars(getCode($row['ly_file'], '/layout'));
				
				// 태그/PHP 만 입력될 경우 에디터에서 출력 못함. 특단의 조치. DB update시 복원. (html태그는 이지윅 모드때문에 포기)
				$html_code = preg_replace('/<\?/', '＜?', $html_code);
				$html_code = preg_replace('/\?>/', '?＞', $html_code);
			}
			
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_layout->table, $id);
				$edt_info = json_encode($edt_info);
			}
			// 에디터 매개변수
			$edt = array(
					'content' => $html_code,
					'edt_info' => isset($edt_info) ? $edt_info : '[]',
					'wr_table' => $this->M_a_layout->table,
					'edt_mode' => 'source',
					'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 1;
			
			$editor = $this->load->view('editor/editor', $edt, TRUE);
			
			$content = ''; // 그냥 비우기
			
			$dbVars = $row['ly_id'] ? $this->M_dbvars->get_data_cfg('layout', $row['ly_id']) : array();
			$dbVars = getContent($dbVars, 'resource');
			
			$vars = array(
				'_TITLE_'		=> '레이아웃 '.$title,
				'_BODY_'		=> ADM_F.'/layout/layout_form',
				'_CSS_'			=> array('editor'),
				'_JS_'			=> array('../editor/js/editor_loader', 'jvalidate', 'jvalid_ext', 'admin_dbvar'),
				
				'token'			=> get_token(),
				
				'w'				=> $w,
				'editor'		=> $editor,
				
				'row'			=> $row,
				'dbVars'		=> $dbVars,
				'banners'		=> $this->M_banner->get_groups()
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('ly_id');
			$ly_code = $this->input->post('wr_content');

			// 태그/PHP 만 입력될 경우 에디터에서 출력 못함. 특단의 조치. 에디터에서 수정시 재변환. (html 태그는 이지윅 모드 때문에 포기)
			$ly_code = preg_replace('/＜\?/', '<?', $ly_code);
			$ly_code = preg_replace('/\?＞/', '?>', $ly_code);
			
			$ct = $this->M_layout->row($id);
			
			if($this->M_a_layout->chk_layout_id($id, $this->input->post('ly_file'))) {
				alert('저장 실패: '.  $this->input->post('ly_file') .'는 이미 등록된 아이디입니다.', ADM_F.'/layout/form/u/'.$id);
			}
			
			if (!$w) {
				if (isset($ct['ly_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$data = array(
					'ly_file'	=> $this->input->post('ly_file'),
					'ly_css'		=> preg_replace('/ /', '', $this->input->post('ly_css')),
					'ly_js'			=> $this->input->post('ly_js'),
					'ly_hidden'		=> $this->input->post('ly_hidden'),
					'ly_name'		=> $this->input->post('ly_name'),
					'ly_mdydate'	=> TIME_YMDHIS
			);
			
			$id = $this->M_a_layout->record($w, $data);
			$this->M_a_dbvars->record('layout', $id, $this->input->post('dbVar'));
			
			// Files upload
			$ly_code = $this->M_editor->uploadFile($this->M_a_layout->table, $id, decodeVars($ly_code));
			
			// code를 html 파일로 저장
			setCode($this->input->post('ly_file'), $ly_code, $ct['ly_file'], '/layout');
			
			goto_url(ADM_F.'/layout/form/u/'.$id);
		}
	}
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$ly_file = $this->input->post('ly_file');
		$ly_name = $this->input->post('ly_name');
		$ly_hidden = $this->input->post('ly_hidden');
		
		if(!$ids)
			return false;
		
		foreach($ids AS $id) {
			if($this->M_a_layout->chk_layout_id($id, $ly_file[$id])) {
				alert('저장 실패: '. $ly_file[$id].'는 이미 등록된 파일명입니다.', URL);
			}
			else if(!$ly_file[$id]) {
				alert('저장 실패: 파일명이 입력되지 않았습니다.', URL);
			}
			else if(preg_replace('/[a-zA-Z0-9_-]/', '', $ly_file[$id])) {
				alert('저장 실패: 파일명에 언더바(_), 대쉬(-) 이외의 특수문자가 있습니다.', URL);
			}
		}
		
		$data = array(
			'ly_file'		=> $ly_file,
			'ly_name'		=> $ly_name,
			'ly_hidden'		=> $ly_hidden
		);
		
		$this->M_a_layout->list_update($ids, $data);
		
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
			$row = $this->M_layout->row($i);
			$this->M_upload_files->file_delete($this->M_a_layout->table, $i);
			$this->M_a_layout->delete($i);
			delCode($row['ly_file'], '/layout');
		}
		
		goto_url(URL);
	}
}
?>
