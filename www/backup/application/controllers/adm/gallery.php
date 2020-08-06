<?php
class Gallery extends CI_Controller {
	var $cate = array(1 => '카테고리1', 2 => '카테고리2', 3 => '카테고리3');
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_gallery');
		$this->load->model('M_gallery');
		$this->load->model('M_upload_files');
	}
	
	function lists() {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper('search');

		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sst  = $seg->get_seg('sst');
		$sod  = $seg->get_seg('sod');
		$sfl  = 'ga_subject';		// 고정
		$stx  = $seg->get_seg('stx');
		$cate  = $seg->get_seg('cate') ? $seg->get_seg('cate') : 1;

		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'ga_subject';
		if (!$sod) $sod = 'asc';

		$qstr = $seg->get_qstr();
		
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/gallery/lists/page/';
		$config['per_page'] = 30;
		
		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_gallery->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset, 'ga_cate = '. $cate);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$list = array();
		$token = get_token();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = $row;
			
			$list[$i]['s_mod'] = icon('수정', 'gallery/form/u/'. $row['ga_id'] . $qstr);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/gallery/delete', {id:'".$row['ga_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['ga_regdate'] = date('Y-m-d', strtotime($row['ga_regdate']));
			$list[$i]['ga_hidden_chk'] = $row['ga_hidden'] ? "checked='checked'" : '';
		}
		
		$vars = array(
			'_TITLE_'			=> '갤러리',
			'_BODY_'			=> ADM_F.'/gallery/gallery_list',
			
			'token'				=> $token,
			'categories'		=> $this->cate,

			'list'				=> $list,
			's_add'				=> icon('추가', 'gallery/form?page='. $page .'&cate='. $cate .'&stx='. $stx),

			'page'				=> $page,
			'cate'				=> $cate,
			'stx'				=> $stx,

			'total_cnt'			=> number_format($result['total_cnt']),
			'paging'			=> $this->pagination->create_links(),

			'sort_ga_subject'	=> sort_link('ga_subject'),
			'sort_ga_file'		=> sort_link('uf_count_image'),
			'sort_ga_hidden'	=> sort_link('ga_hidden'),
			'sort_ga_regdate'	=> sort_link('ga_regdate')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $ga_id='') {
		include "init.php";
		
		$this->load->model('M_editor');
		$this->load->library('form_validation');
		$this->load->helper('search');

		$seg  = new search_seg;
		$qstr = $seg->get_qstr();

		$page = $this->input->get('page');
		$cate = $this->input->get('cate');
		
		$config = array(
			array('field'=>'ga_subject', 'label'=>'제목', 'rules'=>'trim|required|max_length[20]|xss_clean'),
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_gallery->table);

				$row['ga_cate'] = $cate;
				$row['ga_hidden'] = 0;
				
				$qstr = '/page/'. $page .'/cate/'. $cate;
			}
			else if ($w == 'u') {
				$title = '수정';
				$row = $this->M_gallery->row($ga_id);
				
				$uf_files = $this->M_upload_files->get_files($this->M_gallery->table, $ga_id);
				$so['files'] = array();
				foreach($uf_files AS $file) {
					$so['files'][$file['uf_no']] = $file['uf_source'];
				}
				
				if (!isset($row['ga_id']))
					alert('등록된 자료가 없습니다.');
			}
			
			if ($w == 'u') {
				$row['files'] = $this->M_upload_files->get_files($this->M_a_gallery->table, $ga_id, '*');
			}
			
			$row['ga_hidden_chk'] = $row['ga_hidden'] ? "checked='checked'" : '';
			
			$vars = array(
				'_TITLE_'		=> '갤러리',
				'_BODY_'		=> ADM_F.'/gallery/gallery_form',
				'_CSS_'			=> array('swfupload'),
				'_JS_'			=> array('jvalidate','jvalid_ext'),
				
				'token'			=> get_token(),
				
				'cate'			=> $cate,
				
				'qstr'			=> $qstr,
				'dqstr'			=> qstr_rep($qstr, 'u'),
					
				'w'				=> $w,
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			$this->load->model('M_editor');
			
			check_token();

			$w = $this->input->post('w');
			$ga_id = $this->input->post('ga_id');
			$delFile = $this->input->post('delFile');
			
			$ga = $this->M_gallery->row($ga_id);
			
			$data = array(
					'ga_cate'		=> $this->input->post('ga_cate'),
					'ga_subject'	=> $this->input->post('ga_subject'),
					'ga_hidden'		=> $this->input->post('ga_hidden'),
					'ga_mdydate'	=> TIME_YMDHIS
			);
			
			$ga_id = $this->M_a_gallery->record($w, $data);
			
			// 새 파일이 업로드 될 경우 기존 파일은 삭제 (1개만 업로드 가능)
			if($images = $this->input->post('images') || $files  = $this->input->post('files')) {
				$delFile = array(1);
			}
			
			// 파일삭제
			if(is_array($delFile)) {
				$this->M_upload_files->file_delete($this->M_a_gallery->table, $ga_id, $delFile);
			}
			
			// Files upload
			$this->M_editor->uploadFile($this->M_a_gallery->table, $ga_id, '', 0);
			
			goto_url(ADM_F.'/gallery/form/u/'. $ga_id .'/cate/'. $cate);
		}
	}
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$ga_hidden = $this->input->post('ga_hidden');
		
		if(!$ids)
			return false;

		$data = array(
			'ga_hidden' => $ga_hidden
		);
		
		$this->M_a_gallery->list_update($ids, $data);
		
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
			$this->M_upload_files->file_delete($this->M_a_gallery->table, $i);
			$this->M_a_gallery->delete($i);
		}
		
		goto_url(URL);
	}
}
?>
