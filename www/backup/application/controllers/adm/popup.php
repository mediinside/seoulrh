<?php
class Popup extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_popup');
		$this->load->model('M_upload_files');

		if (!$this->config->item('cf_use_popup'))
			goto_url(ADM_F);
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
		if (!$sst) $sst = 'pu_id';
		if (!$sod) $sod = 'asc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/popup/lists/page/';
		$config['per_page'] = 15;

		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_a_popup->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$type = array('일반팝업', '고정레이어');
		
		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i]['s_pre'] = icon('보기', "javascript:win_open('popup/".$row['pu_id']."', 'popup', 'left=".$row['pu_x']."px,top=".$row['pu_y']."px,width=".$row['pu_width']."px,height=".$row['pu_height']."px,scrollbars=0');");
			$list[$i]['s_mod'] = icon('수정', 'popup/form/u/'.$row['pu_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/popup/delete', {id:'".$row['pu_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['pu_sdate_conv'] = substr($row['pu_sdate'], 0, 1) != '0' ? $row['pu_sdate'] : '';
			$list[$i]['pu_edate_conv'] = substr($row['pu_edate'], 0, 1) != '0' ? $row['pu_edate'] : '';
			$list[$i]['pu_type_conv'] = $type[$row['pu_type']];
			$list[$i]['pu_regdate_conv'] = date('Y-m-d', strtotime($row['pu_regdate']));
		}

		$vars = array(
			'_TITLE_'		=> '팝업관리',
			'_BODY_'		=> ADM_F.'/popup/popup_list',

			'token' => $token,

			'list' => $list,
			's_add' => icon('추가', 'popup/form'),

			'sfl' => $sfl,
			'stx' => $stx,		

			'total_cnt' => number_format($result['total_cnt']),
			'paging' => $this->pagination->create_links(),

			'sort_pu_name' => sort_link('pu_name'),
			'sort_pu_type' => sort_link('pu_type'),
			'sort_pu_sdate' => sort_link('pu_sdate'),
			'sort_pu_edate' => sort_link('pu_edate'),
			'sort_pu_hidden' => sort_link('pu_hidden')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $id='') {
		include "init.php";
		
		$this->load->model('M_editor');
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'pu_name',  'label'=>'팝업 이름', 'rules'=>'trim|required|max_length[100]|xss_clean')
		);

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = array_false(array('pu_id','pu_name','pu_sdate','pu_edate','pu_content'));

				$row['pu_hidden'] = $row['pu_type'] = 0;
				$row['pu_width'] = $row['pu_height'] = 300;
				$row['pu_x'] = $row['pu_y'] = 0;
			}
			else if ($w == 'u') {
				$title = '수정';
				$row = $this->M_a_popup->row($id);
				if (!isset($row['pu_id'])) {
					alert('등록된 자료가 없습니다.');
				}
				$row['pu_sdate'] = substr($row['pu_sdate'], 0, 1) != '0' ? $row['pu_sdate'] : '';
				$row['pu_edate'] = substr($row['pu_edate'], 0, 1) != '0' ? $row['pu_edate'] : '';
			}
			
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_popup->table, $id);
				$edt_info = json_encode($edt_info);
			}
			// 에디터 매개변수
			$edt = array(
				'content' => $row['pu_content'],
				'edt_info' => isset($edt_info) ? $edt_info : '[]',
				'wr_table' => $this->M_a_popup->table,
				'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 1;
			
			$editor = $this->load->view('editor/editor', $edt, TRUE);
			
			$content = ''; // 그냥 비우기
			
			$vars = array(
				'_TITLE_'		=> '팝업 '.$title,
				'_BODY_'		=> ADM_F.'/popup/popup_form',
				'_CSS_'			=> array('editor', 'jquery-ui'),
				'_JS_'			=> array('../editor/js/editor_loader', 'jvalidate', 'jvalid_ext', 'jquery-ui.min', 'jtimepicker'),
				
				'token'			=> get_token(),
				
				'w'				=> $w,
				'editor'		=> $editor,
				
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('pu_id');
			$pu_content = $this->input->post('wr_content');
			
			$pu = $this->M_a_popup->row($id);
			
			if (!$w) {
				if (isset($pu['pu_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$data = array(
					'pu_name' => $this->input->post('pu_name'),
					'pu_hidden' => $this->input->post('pu_hidden'),
					'pu_type' => $this->input->post('pu_type'),
					'pu_sdate' => $this->input->post('pu_sdate'),
					'pu_edate' => $this->input->post('pu_edate'),
					'pu_width' => $this->input->post('pu_width'),
					'pu_height' => $this->input->post('pu_height'),
					'pu_x' => $this->input->post('pu_x'),
					'pu_y' => $this->input->post('pu_y'),
					'pu_mdydate' => TIME_YMDHIS
			);
			
			$id = $this->M_a_popup->record($w, $data);
			
			// Files upload
			$pu_content = $this->M_editor->uploadFile($this->M_a_popup->table, $id, $pu_content);

			// 내용에서 첨부 파일 경로 수정
			$this->M_a_popup->db->update($this->M_a_popup->table, array('pu_content' => $pu_content), array('pu_id' => $id));
			
			goto_url(ADM_F.'/popup/form/u/'.$id);
		}
	}
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$pu_name = $this->input->post('pu_name');
		$pu_hidden = $this->input->post('pu_hidden');
		
		if(!$ids)
			return false;
		
		$data = array(
				'pu_name' => $pu_name,
				'pu_hidden' => $pu_hidden
		);
		
		$this->M_a_popup->list_update($ids, $data);
		
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
			$this->M_upload_files->file_delete($this->M_a_popup->table, $i);
			$this->M_a_popup->delete($i);
		}
		
		goto_url(URL);
	}
}
?>
