<?php
class Banner extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model(array(ADM_F.'/M_a_banner', 'M_banner'));
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
		
		$banner_groups = $this->M_banner->groups();
		
		if ($page < 1) $page = 1;
		if ($stx) $stx = array_search(search_decode($stx), $banner_groups); 
		if (!$sst) $sst = 'bn_id';
		if (!$sod) $sod = 'desc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/banner/lists/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_banner->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i] = $row;
			
			$list[$i]['s_mod'] = icon('수정', 'banner/form/u/'.$row['bn_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/banner/delete', {id:'".$row['bn_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['bn_sdate_conv'] = substr($row['bn_sdate'], 0, 1) != '0' ? $row['bn_sdate'] : '';
			$list[$i]['bn_edate_conv'] = substr($row['bn_edate'], 0, 1) != '0' ? $row['bn_edate'] : '';
			$list[$i]['bn_regdate_conv'] = date('Y-m-d', strtotime($row['bn_regdate']));
		}

		$vars = array(
			'_TITLE_'			=> '배너관리',
			'_BODY_'			=> ADM_F.'/banner/banner_list',

			'token'				=> $token,

			'group'				=> $banner_groups,
			'bimg_path'			=> preg_replace('/^'.addcslashes(DATA_PATH, '/').'/', DATA_DIR, $this->M_banner->filepath()),
			'list'				=> $list,
			's_add'				=> icon('추가', 'banner/form'),

			'sfl'				=> $sfl,
			'stx'				=> $stx,		

			'total_cnt'			=> number_format($result['total_cnt']),
			'paging'			=> $this->pagination->create_links(),

			'sort_bn_name'		=> sort_link('bn_name'),
			'sort_bn_type'		=> sort_link('bn_type'),
			'sort_bn_sdate'		=> sort_link('bn_sdate'),
			'sort_bn_edate'		=> sort_link('bn_edate'),
			'sort_bn_hidden'	=> sort_link('bn_hidden'),
			'sort_bn_regdate'	=> sort_link('bn_regdate')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function group_lists() {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper('search');
		
		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sst  = $seg->get_seg('sst');
		$sod  = $seg->get_seg('sod');
		$sfl  = $seg->get_seg('sfl');
		$stx  = $seg->get_seg('stx');
		
		$banner_types = $this->M_banner->type;
		
		if ($page < 1) $page = 1;
		if ($stx) $stx = array_search(search_decode($stx), $banner_types);
		if (!$sst) $sst = 'bg_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/banner/lists/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];
		$result = $this->M_banner->group_lists_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i] = $row;
			
			$list[$i]['s_mod'] = icon('수정', 'banner/form_group/u/'.$row['bg_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/banner/delete_group', {id:'".$row['bg_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['bg_regdate_conv'] = date('Y-m-d', strtotime($row['bg_regdate']));
		}
		
		$vars = array(
				'_TITLE_'			=> '배너그룹관리',
				'_BODY_'			=> ADM_F.'/banner/banner_list_group',
				
				'token'				=> $token,
				
				'type'				=> $this->M_banner->type,
				'list'				=> $list,
				's_add'				=> icon('추가', 'banner/form_group'),
				
				'sfl'				=> $sfl,
				'stx'				=> $stx,
				
				'total_cnt'			=> number_format($result['total_cnt']),
				'paging'			=> $this->pagination->create_links(),
				
				'sort_bg_width'		=> sort_link('bg_width'),
				'sort_bg_height'	=> sort_link('bg_height'),
				'sort_bg_name'		=> sort_link('bg_name'),
				'sort_bg_type'		=> sort_link('bg_type'),
				'sort_bg_regdate'	=> sort_link('bg_regdate')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $id='') {
		include "init.php";
		
		$max_size = array('w' => 1024, 'h' => 768);
		
		$this->load->library(array('upload', 'form_validation'));

		$config = array(
			array('field'=>'bn_target', 'label'=>'타겟',      'rules'=>'trim|required|xss_clean')
		);

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_banner->table);
			}
			else if ($w == 'u') {
				$title = '수정';
				$row = $this->M_banner->row($id);
				if (!isset($row['bn_id'])) {
					alert('등록된 자료가 없습니다.');
				}
				$row['bn_sdate'] = substr($row['bn_sdate'], 0, 1) != '0' ? $row['bn_sdate'] : '';
				$row['bn_edate'] = substr($row['bn_edate'], 0, 1) != '0' ? $row['bn_edate'] : '';
			}
			
			$vars = array(
				'_TITLE_'		=> '배너 '.$title,
				'_BODY_'		=> ADM_F.'/banner/banner_form',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jvalid_ext', 'jquery-ui.min', 'jtimepicker'),
				
				'token'			=> get_token(),
				
				'w'				=> $w,
				'group'			=> $this->M_banner->groups(),
				'max_size'		=> $max_size,
				
				'row'			=> $row,
				'bimg_path'		=> preg_replace('/^'.addcslashes(DATA_PATH, '/').'/', DATA_DIR, $this->M_banner->filepath())
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('bn_id');
			
			// 등록된 정보
			$row = $this->M_banner->row($id);
			
			// 폼 파일 업로드 설정
			$config['upload_path'] =	$this->M_banner->filepath();
			$config['allowed_types'] =	'gif|jpg|png';
			$config['max_size']	=		'2048';
			$config['max_width'] =		$max_size['w'];
			$config['max_height'] =		$max_size['h'];
			$config['encrypt_name'] =	TRUE;
			$this->upload->initialize($config);
			
			// 폼 파일 업로드
			$filename = $row['bn_image'] ? $row['bn_image'] : '';
			if($_FILES['bn_image']['name']) {
				if($this->upload->do_upload('bn_image')) {
					$upload_file = $this->upload->data();
					$filename = $upload_file['file_name'];
					
					// 기존 파일 삭제
					@unlink($this->M_banner->filepath().'/'.$row['bn_image']);
				}
				else {
					alert($this->upload->display_errors());
				}
			}
			
			// DB 저장
			$data = array(
				'bn_group'		=> $this->input->post('bn_group'),
				'bn_url'		=> $this->input->post('bn_url'),
				'bn_target'		=> $this->input->post('bn_target'),
				'bn_hidden'		=> $this->input->post('bn_hidden'),
				'bn_image'		=> $filename,
				'bn_sdate'		=> $this->input->post('bn_sdate'),
				'bn_edate'		=> $this->input->post('bn_edate'),
				'bn_mdydate'	=> TIME_YMDHIS
			);
			$id = $this->M_a_banner->record($w, $data);
			
			goto_url(ADM_F.'/banner/form/u/'.$id);
		}
	}
	
	function form_group($w='', $id='') {
		include "init.php";
		
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'bg_name', 'label'=>'그룹명',      'rules'=>'trim|required|max_length[50]|xss_clean'),
			array('field'=>'bg_type', 'label'=>'출력 형식',   'rules'=>'trim|required|numeric')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_banner->table_gr);
			}
			else if ($w == 'u') {
				$title = '수정';
				$row = $this->M_banner->row($id, '*', $this->M_banner->table_gr);
				if (!isset($row['bg_id'])) {
					alert('등록된 자료가 없습니다.');
				}
			}
			
			$vars = array(
				'_TITLE_'		=> '배너그룹 '.$title,
				'_BODY_'		=> ADM_F.'/banner/banner_form_group',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jvalid_ext', 'jquery-ui.min', 'jtimepicker'),
				
				'token'			=> get_token(),
				
				'w'				=> $w,
				'type'			=> $this->M_banner->type,
				
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('bg_id');
			
			// 등록된 정보
			$row = $this->M_banner->row($id, '*', $this->M_banner->table_gr);
			
			// DB 저장
			$data = array(
				'bg_name'			=> $this->input->post('bg_name'),
				'bg_type'			=> $this->input->post('bg_type'),
				'bg_count'			=> $this->input->post('bg_count'),
				'bg_width'			=> $this->input->post('bg_width'),
				'bg_height'			=> $this->input->post('bg_height'),
				'bg_width_type'		=> $this->input->post('bg_width_type'),
				'bg_height_type'	=> $this->input->post('bg_height_type'),
				'bg_imgW'			=> $this->input->post('bg_imgW'),
				'bg_imgH'			=> $this->input->post('bg_imgH'),
				'bg_ul_css'			=> $this->input->post('bg_ul_css'),
				'bg_li_css'			=> $this->input->post('bg_li_css'),
				'bg_option'			=> $this->input->post('bg_option'),
				'bg_mdydate'		=> TIME_YMDHIS
			);
			$id = $this->M_a_banner->record($w, $data, $this->M_banner->table_gr);
			
			goto_url(ADM_F.'/banner/form_group/u/'.$id);
		}
	}
	
	function update() {
		check_token(URL);
		
		$ids =			$this->input->post('chk');
		$bn_group =		$this->input->post('bn_group');
		$bn_hidden =	$this->input->post('bn_hidden');
		
		if(!$ids)
			return false;

		$data = array(
			'bn_group'		=> $bn_group,
			'bn_hidden'		=> $bn_hidden
		);
		
		$this->M_a_banner->list_update($ids, $data);
		
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
			$row = $this->M_banner->row($i);
			// 배너는 첨부파일 정보가 upload_files DB에 저장되지 않으므로 그냥 삭제
			@unlink($this->M_banner->filepath().'/'.$row['bn_image']);
			$this->M_a_banner->delete($i);
		}
		
		goto_url(URL);
	}
	
	function update_group() {
		check_token(URL);
		
		$ids =			$this->input->post('chk');
		$bg_name =		$this->input->post('bg_name');
		
		if(!$ids)
			return false;

		$data = array(
			'bg_name'		=> $bg_name
		);
		
		$this->M_a_banner->list_update($ids, $data, $this->M_a_banner->table_gr);
		
		goto_url(URL);
	}
	
	function delete_group() {
		check_token(URL);
		
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
	
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;
		
		$this->M_a_banner->delete($ids, $this->M_a_banner->table_gr);
		
		goto_url(URL);
	}
}
?>
