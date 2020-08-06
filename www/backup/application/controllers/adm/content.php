<?php
class Content extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('/M_content', ADM_F.'/M_a_content', ADM_F.'/M_a_layout', ADM_F.'/M_a_dbvars', 'M_banner'));
		$this->load->library('form_validation');
		$this->load->helper(array('textual', 'search'));
	}
	
	function lists() {
		include "init.php";
		
		$this->load->library('pagination');

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
		$config['per_page'] = 20;
		
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
			$list[$i]['s_mod'] = icon('수정', 'content/form/u/'.$row['ct_id'].$qstr);
			$list[$i]['s_cpy'] = icon('복사', "javascript:content_copy(". $row['ct_id'] .");");
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
		
		$use_mobile = $this->config->item('cf_mobile');
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$seg  = new search_seg;
			$qstr = qstr_rep($seg->get_qstr(), 'u');
			
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_content->table);
				$html_code = $html_code_m = FALSE;
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
					alert('등록된 자료가 없습니다.', ADM_F.'/content/lists');

				$fullPath = $this->input->server('DOCUMENT_ROOT') . HTML_PATH . PC_DIR .'/'. $row['ct_url'];
				$html_code = encodeVars(getCode($row['ct_filename'], $fullPath));
				
				// 태그/PHP 만 입력될 경우 에디터에서 출력 못함. 특단의 조치. DB update시 복원. (html태그는 이지윅 모드때문에 포기)
				$html_code = preg_replace('/<\?/', '＜?', $html_code);
				$html_code = preg_replace('/\?>/', '?＞', $html_code);

				$html_code_m = '';
				if($use_mobile) {
					$fullPath = $this->input->server('DOCUMENT_ROOT') . HTML_PATH . MOBILE_DIR .'/'. $row['ct_url'];
					$html_code_m = encodeVars(getCode($row['ct_filename'], $fullPath));
					
					// 태그/PHP 만 입력될 경우 에디터에서 출력 못함. 특단의 조치. DB update시 복원. (html태그는 이지윅 모드때문에 포기)
					$html_code_m = preg_replace('/<\?/', '＜?', $html_code_m);
					$html_code_m = preg_replace('/\?>/', '?＞', $html_code_m);
				}
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
			$editor_m = $this->load->view('editor/editor', array_merge($edt, array('content' => $html_code_m)), TRUE);
			
			$content = ''; // 그냥 비우기
			
			$dbVars = $row['ct_id'] ? $this->M_dbvars->get_data_cfg('content', $row['ct_id']) : array();
			$dbVars = getContent($dbVars, TRUE);
			$boards = $this->M_basic->get_board(FALSE,'bid,bo_subject');

			$shop_cate = array();
			if(is_dir(DATA_PATH.'/shop')) {
				$this->load->model('M_shop');
				$shop_cate = $this->M_shop->list_result_cate();
			}
			
			$vars = array(
				'_TITLE_'		=> '컨텐츠 '.$title,
				'_BODY_'		=> ADM_F.'/content/content_form',
				'_CSS_'			=> array('editor'),
				'_JS_'			=> array('../editor/js/editor_loader', 'jvalidate', 'jvalid_ext', 'admin_dbvar'),
				
				'token'			=> get_token(),
				'qstr'			=> $qstr,
				
				'w'				=> $w,
				'editor'		=> $editor,
				'editor_m'		=> $editor_m,
				'layout_select'	=> get_layout_select('ct_layout', $row['ct_layout']),
				'layout_m_select'	=> get_layout_select('ct_layout_m', $row['ct_layout_m']),
				'level_select'	=> get_mb_level_select('ct_level', (isset($row['ct_level'])) ? $row['ct_level'] : 1, '', $member['mb_level']),
				
				'row'			=> $row,
				'dbVars'		=> $dbVars,
				'boards'		=> setValue(array(), $boards),
				'banners'		=> $this->M_banner->get_groups(),
				'shop_cate'		=> $shop_cate,
				'use_mobile'	=> $use_mobile,
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('ct_id');
			$content = $this->input->post('wr_content');
			$ct_parameter = $this->input->post('ct_parameter');
			$qstr = $this->input->post('qstr');

			// 태그/PHP 만 입력될 경우 에디터에서 출력 못함. 특단의 조치. 에디터에서 수정시 재변환. (html태그는 이지윅 모드때문에 포기)
			$content = is_array($content) ? $content : array($content);
			foreach($content AS $key => $val) {
				$ct_code[$key] = $val;
				$ct_code[$key] = preg_replace('/＜\?/', '<?', $ct_code[$key]);
				$ct_code[$key] = preg_replace('/\?＞/', '?>', $ct_code[$key]);
			}
			
			$ct = $this->M_content->row($id);
			
			$root_path = $this->input->server('DOCUMENT_ROOT') . HTML_PATH;
			$old_path = $root_path .'/'. $ct['ct_url'] .'/'. $ct['ct_filename'] .'.html';
			$new_path = $root_path .'/'. $this->input->post('ct_url') .'/'. $this->input->post('ct_filename') .'.html';
			if($old_path != $new_path && file_exists($new_path)) {
				$url = $id ?  ADM_F.'/content/form/u/'.$id :  ADM_F.'/content/form';
				alert('저장 실패: 파일명 중복. 경로 또는 파일명을 변경해주세요.', $url);
			}
			
			if (!$w) {
				if (isset($ct['ct_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$data = array(
					'ct_url'		=> preg_replace('/(\/(\/)+)/', '/', $this->input->post('ct_url')),
					'ct_parameter'	=> param_encode($ct_parameter),
					'ct_filename'	=> $this->input->post('ct_filename'),
					'ct_hidden'		=> $this->input->post('ct_hidden'),
					'ct_layout'		=> $this->input->post('ct_layout'),
					'ct_layout_m'	=> $this->input->post('ct_layout_m'),
					'ct_level'		=> $this->input->post('ct_level'),
					'ct_mdydate'	=> TIME_YMDHIS
			);
			
			$id = $this->M_a_content->record($w, $data);
			$this->M_a_dbvars->record('content', $id, $this->input->post('dbVar'));
			
			// Files upload
			$ct_code[0] = $this->M_editor->uploadFile($this->M_a_content->table, $id, decodeVars($ct_code[0]), 1, 0);
			
			// code를 html 파일로 저장
			$fullPath = $this->input->server('DOCUMENT_ROOT') . HTML_PATH . PC_DIR .'/'. $this->input->post('ct_url');
			setCode($this->input->post('ct_filename'), $ct_code[0], $ct['ct_filename'], $fullPath);
			
			// 모바일용 동일 작업
			if($use_mobile) {
				$ct_code[1] = $this->M_editor->uploadFile($this->M_a_content->table, $id, decodeVars($ct_code[1]), 2, 1);
				$fullPath = $this->input->server('DOCUMENT_ROOT') . HTML_PATH . MOBILE_DIR .'/'. $this->input->post('ct_url');
				setCode($this->input->post('ct_filename'), $ct_code[1], $ct['ct_filename'], $fullPath);
			}
			
			goto_url(ADM_F.'/content/form/u/'.$id.$qstr);
		}
	}
	
	function copy($id='') {
		include "init.php";
		
		if(!$id) {
			return FALSE;
		}
		
		$config = array(
			array('field'=>'ct_url',		'label'=>'컨텐츠 URL',	'rules'=>'trim|alpha_numeric_under_dash_slash|max_length[200]|xss_clean'),
			array('field'=>'ct_filename',	'label'=>'파일명',		'rules'=>'trim|required|alpha_dash|max_length[50]|xss_clean')
		);

		$ct = $this->M_content->row($id);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$vars = array(
				'_BODY_'		=> ADM_F.'/content/content_copy',
				'_JS_'			=> array('jvalidate', 'jvalid_ext'),
				
				'token'			=> get_token(),
				
				'ct'			=> $ct
			);
			
			$this->load->view('layout/layout_blank', $vars);
		}
		else
		{
			check_token();
			
			$fullPath = $this->input->server('DOCUMENT_ROOT') . HTML_PATH . PC_DIR .'/'. $ct['ct_url'];
			$ct_code = getCode($ct['ct_filename'], $fullPath);
			
			$root_path = $this->input->server('DOCUMENT_ROOT') . HTML_PATH;
			$new_path = $root_path .'/'. $this->input->post('ct_url') .'/'. $this->input->post('ct_filename') .'.html';
			if(file_exists($new_path)) {
				alert('저장 실패: 파일명 중복. 경로 또는 파일명을 변경해주세요.', ADM_F .'/content/copy/'. $id);
			}
			
			$data = array(
					'ct_url'			=> preg_replace('/(\/(\/)+)/', '/', $this->input->post('ct_url')),
					'ct_filename'		=> $this->input->post('ct_filename'),
					'ct_parameter'		=> $ct['ct_parameter'],
					'ct_hidden'			=> $ct['ct_hidden'],
					'ct_layout'			=> $ct['ct_layout'],
					'ct_layout_m'		=> $ct['ct_layout_m'],
					'ct_level'			=> $ct['ct_level'],
					'uf_count_file'		=> $ct['uf_count_file'],
					'uf_count_image'	=> $ct['uf_count_image'],
					'ct_mdydate'		=> TIME_YMDHIS
			);
			
			$new_id = $this->M_a_content->record('', $data);
			$this->M_a_dbvars->copy($new_id, array('ref_type' => 'content', 'ref_id' => $id));
			
			// 파일 복사
			if ($ct['uf_count_image'] > 0 || $ct['uf_count_file'] > 0) {
				$dirs = $this->M_upload_files->get_dir($this->M_content->table);
				$filePath = $dirs['data_path'];
				$up_files = $this->M_upload_files->get_files($this->M_content->table, $id);
				
				$val_file = array();				
				foreach ($up_files as $k => $uf) {
					if ($uf['uf_file']) {
						$old_file = $filePath .'/'. $uf['uf_file'];
						$new_filename = time() . substr($uf['uf_file'], 10, strlen($uf['uf_file']));
						@copy($old_file, $filePath .'/'.$new_filename);
						@chmod($new_file, 0606);
						
						$val_file[] = "('".$uf['uf_table']."','".$new_id."','".$uf['uf_no']."','".$uf['uf_editor']."','".$uf['uf_source']."','".$new_filename."','".$uf['uf_download']."','".$uf['uf_filesize']."','".$uf['uf_width']."','".$uf['uf_height']."','".$uf['uf_type']."','".TIME_YMDHIS."')";
						$ct_code = str_replace($dirs['data_url'].'/'.$uf['uf_file'], $dirs['data_url'].'/'.$new_filename, $ct_code);
					}
				}
				
				// uploadfiles Insert
				if($val_file) {
					$this->M_upload_files->file_insert(implode(',', $val_file));
				}
			}
			
			// code를 html 파일로 저장
			$fullPath = array(
					$this->input->server('DOCUMENT_ROOT') . HTML_PATH . PC_DIR .'/'. $this->input->post('ct_url'),
					$this->input->server('DOCUMENT_ROOT') . HTML_PATH . MOBILE_DIR .'/'. $this->input->post('ct_url')
			);
			foreach($fullPath AS $path) {
				setCode($this->input->post('ct_filename'), $ct_code, $this->input->post('ct_filename'), $path);
			}
			
			goto_url(ADM_F.'/content/lists', 'parent');
		}
	}
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$ct_url = $this->input->post('ct_url');
		$ct_filename = $this->input->post('ct_filename');
		$ct_layout = $this->input->post('ct_layout');
		$ct_layout_m = $this->input->post('ct_layout_m');
		$ct_hidden = $this->input->post('ct_hidden');
		$old_url = $this->input->post('old_url');
		$old_filename = $this->input->post('old_filename');
		
		$root_path = $this->input->server('DOCUMENT_ROOT') . HTML_PATH;
		
		if(!$ids)
			return false;
		
		foreach($ids AS $id) {
			$old_file = $root_path .'/'. $old_url[$id] .'/'. $old_filename[$id];
			$new_file = $root_path .'/'. $ct_url[$id] .'/'. $ct_filename[$id];

			if($old_file.'.html' != $new_file.'.html' && file_exists($new_file.'.html')) {
				alert('저장 실패: 파일명 중복. 경로 또는 파일명을 변경해주세요.', URL);
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
			'ct_layout_m'	=> $ct_layout_m,
			'ct_hidden'		=> $ct_hidden
		);
		
		$this->M_a_content->list_update($ids, $data);
	
		// 파일 이동
		foreach($ids AS $id) {
			$old_file = $root_path .'/'. $old_url[$id] .'/'. $old_filename[$id];
			$new_dir = $root_path .'/'. $ct_url[$id];
			
			$path = chkDir($new_dir, TRUE);
			
			if($old_file != $path .'/'. $ct_filename[$id]) {
				rename($old_file .'.html', $path .'/'. $ct_filename[$id] .'.html');
			}
		}
		
		emptyDir($root_path);
		
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
			$this->M_a_dbvars->delete('content', $i);
			
			$fullPath = array(
					$this->input->server('DOCUMENT_ROOT') . HTML_PATH . PC_DIR .'/'. $row['ct_url'],
					$this->input->server('DOCUMENT_ROOT') . HTML_PATH . MOBILE_DIR .'/'. $row['ct_url']
			);
			foreach($fullPath AS $path) {
				delCode($row['ct_filename'], $path);
			}
		}

		$root_path = $this->input->server('DOCUMENT_ROOT') . HTML_PATH;
		emptyDir($root_path);
		
		goto_url(URL);
	}
}
?>
