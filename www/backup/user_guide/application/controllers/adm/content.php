<?php
class Content extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('/M_content', ADM_F.'/M_a_content', ADM_F.'/M_a_layout', ADM_F.'/M_a_dbvars', 'M_banner'));
		$this->load->library('form_validation');
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
		if (!$sst) $sst = 'ct_id';
		if (!$sod) $sod = 'asc';
		
		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/content/lists/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];
		$result = $this->M_a_content->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$token = get_token();
		foreach ($list as $i => $row) {
			//$list[$i]['s_pre'] = icon('보기', "javascript:win_open('content/".$row['ct_id']."', 'content', 'left=".$row['ct_x']."px,top=".$row['ct_y']."px,width=".$row['ct_width']."px,height=".$row['ct_height']."px,scrollbars=0');");
			$pre_url = $row['ct_url'] ? '../'.$row['ct_url'].'/'.$row['ct_filename'] : '../'.$row['ct_filename'];
			$list[$i]['s_pre'] = icon('미리보기', $pre_url, '_blank');
			$list[$i]['s_mod'] = icon('수정', 'content/form/u/'.$row['ct_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/content/delete', {id:'".$row['ct_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['ct_mdydate_conv'] = date('Y-m-d', strtotime($row['ct_mdydate']));
			$list[$i]['ct_regdate_conv'] = date('Y-m-d', strtotime($row['ct_regdate']));
		}
		
		$vars = array(
			'_TITLE_'			=> '컨텐츠 관리',
			'_BODY_'			=> ADM_F.'/content/content_list',
			
			'token'				=> $token,

			'list'				=> $list,
			's_add'				=> icon('추가', 'content/form'),

			'sfl'				=> $sfl,
			'stx'				=> $stx,		

			'total_cnt'			=> number_format($result['total_cnt']),
			'paging'			=> $this->pagination->create_links(),

			'sort_ct_url'		=> sort_link('ct_url'),
			'sort_ct_layout'	=> sort_link('ct_layout'),
			'sort_ct_hidden'	=> sort_link('ct_hidden')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $id='') {
		include "init.php";
		
		$this->load->model('M_editor');

		$config = array(
			array('field'=>'ct_url',		'label'=>'컨텐츠 URL',	'rules'=>'trim|alpha_numeric_under_dash_slash|max_length[200]|xss_clean'),
			array('field'=>'ct_filename',	'label'=>'파일명',		'rules'=>'trim|required|alpha_dash|max_length[50]|xss_clean'),
			array('field'=>'ct_layout',		'label'=>'레이아웃',		'rules'=>'trim|required|alpha_dash|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_content->table);
				$html_code = FALSE;
				$row['ct_vars'] = json_encode(array(
					array('name'=>'pageNum','value'=>'0'),
					array('name'=>'subNum','value'=>'0'),
					array('name'=>'mainTitle','value'=>''),
					array('name'=>'subTitle','value'=>'')
				));
			}
			else if ($w == 'u') {
				$title = '수정';
				$row = $this->M_content->row($id);
				if (!isset($row['ct_id']))
					alert('등록된 자료가 없습니다.');
				
				$html_code = encodeVars(getCode($row['ct_filename']));
				
				// 태그/PHP 만 입력될 경우 에디터에서 출력 못함. 특단의 조치. DB update시 복원. (html태그는 이지윅 모드때문에 포기)
				$html_code = preg_replace('/<\?/', '＜?', $html_code);
				$html_code = preg_replace('/\?>/', '?＞', $html_code);
			}
			
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_content->table, $id);
				$edt_info = json_encode($edt_info);
			}
			// 에디터 매개변수
			$edt = array(
					'content' => $html_code,
					'edt_info' => isset($edt_info) ? $edt_info : '[]',
					'wr_table' => $this->M_a_content->table,
					'edt_mode' => 'source',
					'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 1;
			
			$editor = $this->load->view('editor/editor', $edt, TRUE);
			
			$content = ''; // 그냥 비우기
			
			$dbVars = $row['ct_id'] ? $this->M_dbvars->get_data_cfg('content', $row['ct_id']) : array();
			$dbVars = getContent($dbVars, 'resource');
			
			$vars = array(
				'_TITLE_'		=> '컨텐츠 '.$title,
				'_BODY_'		=> ADM_F.'/content/content_form',
				'_CSS_'			=> array('editor'),
				'_JS_'			=> array('../editor/js/editor_loader', 'jvalidate', 'jvalid_ext', 'admin_dbvar'),
				
				'token'			=> get_token(),
				
				'w'				=> $w,
				'editor'		=> $editor,
				
				'row'			=> $row,
				'dbVars'		=> $dbVars,
				'layout_select'	=> get_layout_select('ct_layout', $row['ct_layout']),
				'banners'		=> $this->M_banner->get_groups()
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('ct_id');
			$ct_code = $this->input->post('wr_content');
			$ct_parameter = $this->input->post('ct_parameter');

			// 태그/PHP 만 입력될 경우 에디터에서 출력 못함. 특단의 조치. 에디터에서 수정시 재변환. (html태그는 이지윅 모드때문에 포기)
			$ct_code = preg_replace('/＜\?/', '<?', $ct_code);
			$ct_code = preg_replace('/\?＞/', '?>', $ct_code);
			
			$ct = $this->M_content->row($id);
			
			if($this->M_a_content->chk_field($id, 'ct_filename', $this->input->post('ct_filename'))) {
				alert('저장 실패: '.  $this->input->post('ct_filename') .'는 이미 등록된 파일명입니다.', ADM_F.'/content/form/u/'.$id);
			}
			
			if (!$w) {
				if (isset($ct['ct_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$data = array(
					'ct_url'		=> $this->input->post('ct_url'),
					'ct_parameter'	=> param_encode($ct_parameter),
					'ct_filename'	=> $this->input->post('ct_filename'),
					'ct_hidden'		=> $this->input->post('ct_hidden'),
					'ct_layout'		=> $this->input->post('ct_layout'),
					'ct_mdydate'	=> TIME_YMDHIS
			);
			
			$id = $this->M_a_content->record($w, $data);
			$this->M_a_dbvars->record('content', $id, $this->input->post('dbVar'));
			
			// Files upload
			$ct_code = $this->M_editor->uploadFile($this->M_a_content->table, $id, decodeVars($ct_code));
			
			// code를 html 파일로 저장
			setCode($this->input->post('ct_filename'), $ct_code, $ct['ct_filename']);
			
			goto_url(ADM_F.'/content/form/u/'.$id);
		}
	}
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$ct_url = $this->input->post('ct_url');
		$ct_filename = $this->input->post('ct_filename');
		$ct_layout = $this->input->post('ct_layout');
		$ct_hidden = $this->input->post('ct_hidden');
		
		if(!$ids)
			return false;
		
		foreach($ids AS $id) {
			if($this->M_a_content->chk_field($id, 'ct_filename', $ct_filename[$id])) {
				alert('저장 실패: '. $ct_filename[$id].'는 이미 등록된 파일명입니다.', URL);
			}
			else if(!$ct_filename[$id]) {
				alert('저장 실패: 파일명이 입력되지 않았습니다.', URL);
			}
			else if(preg_replace('/[a-zA-Z0-9_-]|\//', '', $ct_url[$id])) {
				alert('저장 실패: URL에 언더바(_), 대쉬(-), 슬래쉬(/) 이외의 특수문자가 있습니다.', URL);
			}
			else if(preg_replace('/[a-zA-Z0-9_-]/', '', $ct_filename[$id])) {
				alert('저장 실패: 파일명에 언더바(_), 대쉬(-) 이외의 특수문자가 있습니다.', URL);
			}
			$ct_url[$id] = preg_replace('/(^\/)|(\/$)/', '', $ct_url[$id]);
		}
		
		$data = array(
			'ct_url'		=> $ct_url,
			'ct_filename'	=> $ct_filename,
			'ct_layout'		=> $ct_layout,
			'ct_hidden'		=> $ct_hidden
		);
		
		$this->M_a_content->list_update($ids, $data);
		
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
			$row = $this->M_content->row($i);
			$this->M_upload_files->file_delete($this->M_a_content->table, $i);
			$this->M_a_content->delete($i);
			delCode($row['ct_filename']);
		}
		
		goto_url(URL);
	}
}
?>
