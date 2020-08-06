<?php
class Staff extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_staff', 'M_staff'));
	}
	
	function lists() {
		include "init.php";
		
		$this->load->helper(array('search', 'textual'));
		$this->load->library('pagination');
		
		$cate = $this->input->get('cate');
		
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
		if (!$sst) $sst = 'st_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		// 페이징
		$pageConf['suffix'] = $qstr;
		$pageConf['base_url'] = RT_PATH.'/'.ADM_F.'/staff/lists/page/';
		$pageConf['per_page'] = 15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_a_staff->list_result($sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];

		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i]['no'] = $result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] = $i % 2;
			
			$list[$i]['regdate'] = date('Y-m-d', strtotime($list[$i]['st_regdate']));
			
			$list[$i]['s_mod'] = icon('수정', 'staff/form/u/'.$list[$i]['st_id']."?qstr=$qstr");
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/staff/delete', {id:'".$list[$i]['st_id']."', token:'".$token."', qstr:'".$qstr."', cate:'".$cate."'}, true);");
		}
		
		$sort_link['st_name'] = sort_link('st_name');
		$sort_link['st_type'] = sort_link('st_type');
		$sort_link['st_phone'] = sort_link('st_phone');
		$sort_link['st_mobile'] = sort_link('st_mobile');
		$sort_link['st_fax'] = sort_link('st_fax');
		$sort_link['st_regdate'] = sort_link('st_regdate');
		
		$vars = array(
			'_TITLE_'		=> '직원 리스트',
			'_BODY_'		=> ADM_F.'/staff/staff_list',
			
			'token'			=> $token,
				
			'list'			=> $list,
			'qstr'			=> $qstr,
			's_add'			=> icon('추가', "staff/form"),
				
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			'total_cnt'		=> $result['total_cnt'],

			'types'			=> $this->M_staff->type,
			'paging'		=> $this->pagination->create_links(),
			'sort_link'		=> $sort_link
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w = '', $id = '') {
		include "init.php";

		$this->load->library(array('upload', 'form_validation'));
		
		$config = array(
			array('field'=>'st_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[20]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_staff->table);
			}
			else if ($w == 'u') {
				$title = '수정';
				
				$row = $this->M_a_staff->row($id);
				if (!isset($row['st_id']))
					alert('등록된 자료가 없습니다.');
			}
			
			$vars = array(
				'_TITLE_'		=> '직원 '.$title,
				'_BODY_'		=> ADM_F.'/staff/staff_form',
				'_JS_'			=> array('jvalidate', 'jvalid_ext'),
				
				'w'				=> $w,
				'token'			=> get_token(),
				'types'			=> $this->M_staff->type,
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('st_id');
			$delFile = $this->input->post('delFile');
			
			$row = $this->M_staff->row($id);
			
			// 폼 파일 업로드 설정
			$config['upload_path'] =	$this->M_staff->filepath();
			$config['allowed_types'] =	'gif|jpg|png';
			$config['max_size']	=		'2048';
			$config['encrypt_name'] =	TRUE;
			$this->upload->initialize($config);
			
			// 폼 파일 업로드
			$filename = $row['st_image'] ? $row['st_image'] : '';
			if($_FILES['st_image']['name']) {
				if($this->upload->do_upload('st_image')) {
					$upload_file = $this->upload->data();
					$filename = $upload_file['file_name'];
					
					// 기존 파일 삭제
					$delFile = TRUE;
				}
				else {
					alert($this->upload->display_errors());
				}
			}
			if($delFile) {
				@unlink($this->M_staff->filepath().'/'.$row['st_image']);
				$filename = $filename != $row['st_image'] ? $filename : '';
			}
			
			// DB 저장
			$data = array(
				'st_name'		=> $this->input->post('st_name'),
				'st_type'		=> $this->input->post('st_type'),
				'st_phone'		=> $this->input->post('st_phone'),
				'st_mobile'		=> $this->input->post('st_mobile'),
				'st_fax'		=> $this->input->post('st_fax'),
				'st_intro'		=> $this->input->post('st_intro'),
				'st_image'		=> $filename,
				'st_mdydate'	=> TIME_YMDHIS
			);
			$id = $this->M_a_staff->record($w, $data);
			
			goto_url(ADM_F.'/staff/form/u/'.$id);
		}
	}
	
	function update() {
		check_token(URL);
	
		$ids = $this->input->post('chk');
		$st_type = $this->input->post('st_type');
		
		if(!$ids)
			return false;
	
		$data = array(
				'st_type' => $st_type
		);
	
		$this->M_a_staff->list_update($ids, $data);
	
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
			$row = $this->M_staff->row($i);
			// 직원은 첨부파일 정보가 upload_files DB에 저장되지 않으므로 그냥 삭제
			@unlink($this->M_staff->filepath().'/'.$row['st_image']);
			// 썸네일 삭제
			@del_thumb($this->M_staff->filepath().'/thumb', $row['st_image']);
			$this->M_a_staff->delete($i);
		}
	
		goto_url(URL);
	}
}
?>
