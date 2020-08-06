<?php
class Apply extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_apply', 'M_apply'));
		$this->load->helper('search');
	}
	
	function lists() {
		include "init.php";
		
		$this->load->helper(array('admin', 'sideview', 'textual', 'form'));
		$this->load->library('pagination');
		
		// 서치
		$seg = new search_seg;
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		
		if(!$cid = $this->input->get('cid')) {
			$cid = 1;
		}
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'ap_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		if(!$apc = $this->M_apply->row_form($cid)) {
			alert('신청서 양식이 없습니다.');
		}
		
		// 페이징
		$pageConf['suffix'] = $qstr;
		$pageConf['base_url'] = RT_PATH.'/'.ADM_F.'/apply/lists/page/';
		$pageConf['per_page'] = 15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_apply->list_result($sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset, $cid);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		
		$forms = $checkbox = array();
		foreach($apc['form'] AS $form) {
			if($form['apf_listing']) {
				$forms[] = $form;
				$sort_link[$form['apf_field']] = sort_link('ap_'. $form['apf_field']) .'?cid='. $cid;
			}
			if($form['apf_type'] == 'checkbox') {
				$checkbox[] = 'ap_'. $form['apf_field'];
			}
		}
		
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['s_view'] = icon('보기', 'javascript:apply_view(\''. $cid .'\','. $row['ap_id'] .');');
			$list[$i]['s_mod'] = icon('수정', 'apply/form/u/'.$row['ap_id'] . $qstr .'?cid='. $cid);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/apply/delete', {cid:'".$cid."', id:'".$row['ap_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
			
			$list[$i]['no'] = $result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] = $i % 2;
			$list[$i]['status'] = form_dropdown('ap_status['. $row['ap_id'] .']', explode('|',$apc['apc_status']), $row['ap_status'], 'class="ed"');
			$list[$i]['regdate'] = date('Y.m.d', strtotime($row['ap_regdate']));
			
			foreach($checkbox AS $chkb) {
				$chks = explode('|', $row[$chkb]);
				
				foreach($chks AS $k => $v) {
					if($v) {
						$chks[$k] = '['.$v.']';
					}
				}
				$list[$i][$chkb] = implode(', ', $chks);
			}
		}
		
		$sort_link['no'] =			sort_link('ap_id') .'?cid='. $cid;		
		$sort_link['ap_regdate'] =	sort_link('ap_regdate') .'?cid='. $cid;
		
		$vars = array(
			'_TITLE_'		=> '신청서 리스트',
			'_BODY_'		=> ADM_F.'/apply/apply_list',
			
			'token'			=> $token,
			'forms'			=> $forms,
			'apc'			=> $apc,
			
			'list'			=> $list,
			'qstr'			=> $qstr,
			'cid'			=> $cid,
			
			's_add'			=> icon('추가', "apply/form?cid=". $cid),
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			
			'total_cnt'		=> $result['total_cnt'],
			'paging'		=> $this->pagination->create_links(),
			'sort_link'		=> $sort_link
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w = '', $id = '') {
		include "init.php";
		
		$this->load->library('form_validation');
		
		if(!$cid = $this->input->get('cid')) {
			return FALSE;
		}
		
		$apc = $this->M_apply->row_form($cid);
		
		$config = array();
		foreach($apc['form'] AS $key => $form) {
			if($form['apf_type'] == 'hidden') {
				/*
				$ap['form'][$key]['apf_type'] = 'text';
				$ap['form'][$key]['apf_name'] = $form['apf_name'] . '<span class=dgray>(hidden)</span>';
				*/
				
				unset($apc['form'][$key]);		// 히든 필드는 출력하지 않음
				
				continue;
			}
			
			$is_trim = !is_array($this->input->post($form['apf_field'])) ? '|trim' : '';
			$is_required = $form['apf_required'] ? '|required' : '';
			$config[] = array('field' => $form['apf_field'], 'label' => $form['apf_name'], 'rules' => 'xss_clean'. $is_trim . $is_required);
		}
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_apply->table . $cid);

			}
			else if ($w == 'u') {
				$title = '수정';
				
				$row = $this->M_apply->row($cid, $id);
				if (!$row)
					alert('등록된 자료가 없습니다.');
			}
			
			$vars = array(
				'_TITLE_'		=> '신청서 '.$title,
				'_BODY_'		=> ADM_F.'/apply/apply_form',
				'_JS_'			=> array('jvalidate', 'jvalid_ext'),

				'token'			=> get_token(),
				
				'w'				=> $w,
				'cid'			=> $cid,
				
				'apc'			=> $apc,
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();

			$w = $this->input->post('w');
			$id = $this->input->post('ap_id');
			$delFile = $this->input->post('delFile');
			
			$row = $this->M_apply->row($cid, $id);
			
			$sql = array(
				'ap_mdydate'	=> TIME_YMDHIS
			);
			
			foreach($apc['form'] AS $form) {
				$value = $this->input->post($form['apf_field']) !== FALSE ? $this->input->post($form['apf_field']) : '';
				
				if($form['apf_type'] == 'checkbox') {
					$value = is_array($value) ? implode('|', $value) : '';
				}
				else if($form['apf_type'] == 'file') {
					$uppath = $this->M_upload_files->get_dir('apply');
					
					$value = '';
					
					// 기존 파일이 있고 삭제 체크값이 없으면 파일명 정의
					if(isset($row['ap_'. $form['apf_field']]) && (!isset($delFile[$form['apf_field']]) || !$delFile[$form['apf_field']])) {
						$value = $row['ap_'. $form['apf_field']];
					}
					
					// 업로드된 파일이 있으면 파일명 변경
					if(isset($_FILES[$form['apf_field']]['name']) && $_FILES[$form['apf_field']]['name']) {
						$value = fileupload($form['apf_field'], $uppath);
					}
					
					// 기존 파일명과 새 파일명이 다르면 기존 파일 삭제
					if($value != $row['ap_'. $form['apf_field']]) {
						@unlink($uppath['data_path'] .'/'. $row['ap_'. $form['apf_field']]);
					}
				}
				
				$sql['ap_'. $form['apf_field']] = $value;
			}
			
			$id = $this->M_a_apply->record($w, $apc['apc_id'], $id, $sql);
			
			alert('저장 되었습니다.', ADM_F.'/apply/form/u/'.$id.'?cid='. $cid);
		}
	}
	
	function lists_conf() {
		include "init.php";
		
		$this->load->helper(array('admin', 'textual'));
		$this->load->library('pagination');
		
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
		if (!$sst) $sst = 'apc_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		// 페이징
		$pageConf['suffix'] = $qstr;
		$pageConf['base_url'] = RT_PATH.'/'.ADM_F.'/apply/lists/page/';
		$pageConf['per_page'] = 15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_a_apply->list_result($sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['s_view'] = icon('보기', '../apply/'.$row['apc_id']);
			$list[$i]['s_mod'] = icon('수정', 'apply/form_conf/u/'.$row['apc_id']."?qstr=$qstr");
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/apply/delete_conf', {id:'".$row['apc_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
				
			$list[$i]['no'] = $result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] = $i % 2;
		}
		
		$sort_link['apc_name'] =	sort_link('apc_name');
		$sort_link['apc_mdydate'] =	sort_link('apc_mdydate');
		$sort_link['acp_regdate'] =	sort_link('ap_regdate');
		
		$vars = array(
			'_TITLE_'		=> '신청서 리스트',
			'_BODY_'		=> ADM_F.'/apply/apply_list_conf',
			
			'token'			=> $token,
			
			'list'			=> $list,
			'qstr'			=> $qstr,
			
			's_add'			=> icon('추가', "apply/form_conf"),
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			
			'total_cnt'		=> $result['total_cnt'],
			'paging'		=> $this->pagination->create_links(),
			'sort_link'		=> $sort_link
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form_conf($w = '', $id = '') {
		include "init.php";
		
		$this->load->library('form_validation');
		
		$config = array(
			array('field'=>'apc_id', 'label'=>'신청서 ID', 'rules'=>'trim|required|alpha_numeric_under|max_length[20]|xss_clean'),
			array('field'=>'apc_name', 'label'=>'신청서 이름', 'rules'=>'trim|required|max_length[20]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$row = $this->db->get_columns($this->M_a_apply->table_cate);
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_apply->table_cate);
				$row['form'] = array();
			}
			else if ($w == 'u') {
				$title = '수정';
				
				$row = $this->M_apply->row_form($id);
				if (!$row)
					alert('등록된 자료가 없습니다.');
			}

			$row['email'] = $row['apc_email'] ? explode('|', $row['apc_email']) : array();
			$row['status'] = $row['apc_status'] ? explode('|', $row['apc_status']) : array();
				
			$input_types = $this->db->get_values($this->M_a_apply->table_form, 'apf_type');
			
			$vars = array(
				'_TITLE_'		=> '신청서 폼 '.$title,
				'_BODY_'		=> ADM_F.'/apply/apply_form_conf',
				'_JS_'			=> array('jvalidate', 'jvalid_ext'),
				
				'w'				=> $w,
				'token'			=> get_token(),
				
				'input_types'	=> array_combine($input_types, $input_types),
				'row'			=> $row,

				'layout_select'	=> get_layout_select('apc_layout', $row['apc_layout']),
				'layout_select_m'	=> get_layout_select('apc_layout_m', $row['apc_layout_m']),
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('apc_id');
			$email = $this->input->post('apc_email');
			$status = $this->input->post('apc_status');
			
			$row = $this->M_apply->row_form($id);
			
			if (!$w) {
				if (isset($row['ap_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$sql = array(
				'apc_id'			=> $id,
				'apc_name'			=> $this->input->post('apc_name'),
				'apc_layout'		=> $this->input->post('apc_layout'),
				'apc_top_html'		=> $this->input->post('apc_top_html'),
				'apc_bottom_html'	=> $this->input->post('apc_bottom_html'),
				'apc_email'			=> is_array($email) ? implode('|', $this->input->post('apc_email')) : '',
				'apc_status'		=> is_array($status) ? implode('|', $this->input->post('apc_status')) : '',
				'apc_mdydate'		=> TIME_YMDHIS
			);
			
			if($this->M_a_apply->record_cate($w, $sql, $id)) {
				$fields = is_array($this->input->post('apf_field')) ? $this->input->post('apf_field') : array();
				$types = $this->input->post('apf_type');
				
				$fsql = array(
						'apf_id'		=> $this->input->post('apf_id'),
						'apf_field'		=> $fields,
						'apf_name'		=> $this->input->post('apf_name'),
						'apf_type'		=> $types,
						'apf_options'	=> $this->input->post('apf_options'),
						'apf_align_r'	=> $this->input->post('apf_align_r'),
						'apf_required'	=> $this->input->post('apf_required'),
						'apf_listing'	=> $this->input->post('apf_listing'),
						'apf_mdydate'	=> TIME_YMDHIS
				);
				
				$this->M_a_apply->record_form($id, $fsql);
				
				// DB table 추가/수정/삭제
				$this->load->dbforge();
				if($w == '') {
					$this->dbforge->add_field(array(
							'ap_id' => array(
									'type' => 'int',
									'constraint' => 11,
									'unsigned' => TRUE,
									'auto_increment' => TRUE
							),
							'ap_mb_id' => array(
									'type' => 'varchar',
									'constraint' => 20
							),
							'ap_status' => array(
									'type' => 'varchar',
									'constraint' => 50
							),
							'ap_mdydate' => array(
									'type' => 'datetime'
							),
							'ap_regdate' => array(
									'type' => 'datetime'
							)
					));
				}
				else {
					$columns = $this->db->get_columns($this->M_a_apply->table . $id);
					unset($columns['ap_id']);
					unset($columns['ap_mb_id']);
					unset($columns['ap_status']);
					unset($columns['ap_mdydate']);
					unset($columns['ap_regdate']);
				}
				
				foreach($fields AS $key => $fld) {
					if($types[$key] == 'textarea' || $types[$key] == 'checkbox') {
						$add_field_type = array('type' => 'text');
					}
					else {
						$add_field_type = array('type' => 'varchar', 'constraint' => 255);
					}
					
					if($w == '') {
						$this->dbforge->add_field( array('ap_'. $fld => $add_field_type) );
					}
					else {
						if(isset($columns['ap_'. $fld])) {
							$this->dbforge->modify_column($this->M_a_apply->table . $id, array('ap_'. $fld => $add_field_type));
							unset($columns['ap_'. $fld]);
						}
						else {
							$this->dbforge->add_column($this->M_a_apply->table . $id, array('ap_'. $fld => $add_field_type));
						}
					}
				}
				
				if($w == '') {
					$this->dbforge->add_key('ap_id', TRUE);
					$this->dbforge->create_table($this->M_a_apply->table . $id, TRUE);
				}
				else if(isset($columns) && $columns) {
					foreach($columns AS $col => $val) {
						$this->dbforge->drop_column($this->M_a_apply->table . $id, $col);
					}
				}
				
				alert('저장 되었습니다.', ADM_F.'/apply/form_conf/u/'.$id);
			}
		}
	}
	
	function view() {
		include "init.php";

		$seg = new search_seg;
		$cid = $seg->get_seg('cid');
		$id = $seg->get_seg('id');

		if(!$apc = $this->M_apply->row_form($cid)) {
			alert('신청서 양식이 없습니다.');
		}
		
		if (!$row = $this->M_apply->row($cid, $id)) {
			alert_close('등록된 자료가 없습니다.', FALSE);
		}
		
		$checkbox = array();
		foreach($apc['form'] AS $key => $form) {
			if($form['apf_type'] == 'hidden') {
				/*
				$apc['form'][$key]['apf_type'] = 'text';
				$apc['form'][$key]['apf_name'] = $form['apf_name'] . '<span class=dgray>(hidden)</span>';
				*/
				
				unset($apc['form'][$key]);		// 히든 필드는 출력하지 않음
			}

			if($form['apf_type'] == 'checkbox') {
				$checkbox[] = 'ap_'. $form['apf_field'];
			}
			else if($form['apf_type'] == 'file') {
				$row['ap_'. $form['apf_field']] = '<a href="/'. ADM_F .'/apply/download?file='. $row['ap_'. $form['apf_field']] .'">'. $row['ap_'. $form['apf_field']] .'</a>';
			}
		}
		
		foreach($checkbox AS $chkb) {
			$chks = explode('|', $row[$chkb]);
			
			foreach($chks AS $k => $v) {
				if($v) {
					$chks[$k] = '['.$v.']';
				}
			}
			$row[$chkb] = implode(', ', $chks);
		}
		
		$vars = array(
			'_TITLE_'		=> '신청서',
			'_BODY_'		=> ADM_F.'/apply/apply_view',

			'apc'			=> $apc,
			'row'			=> $row
		);
		
		$this->load->view('layout/layout_blank', $vars);
	}
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$ap_status = $this->input->post('ap_status');
		
		if(!$ids)
			return false;
		
		$data = array(
			'ap_status' => $ap_status
		);
		
		$this->M_a_apply->list_update($ids, $data);
	
		goto_url(URL);
	}
	
	function delete() {
		check_token(URL);
		
		$cid = $this->input->post('cid');
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
		
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;
		
		if(!$apc = $this->M_apply->row_form($cid)) {
			alert('신청서 양식이 없습니다.');
		}
		
		// 파일 항목 확인
		$fileForms = array();
		foreach($apc['form'] AS $key => $form) {
			if($form['apf_type'] == 'file') {
				$fileForms[] = $form['apf_field'];
			}
		}
		
		// 파일 삭제
		if($fileForms) {
			$fileDir = $this->M_upload_files->get_dir('apply');
			foreach($ids AS $id) {
				$row = $this->M_apply->row($cid, $id);
				foreach($fileForms AS $form) {
					@unlink($fileDir['data_path'] .'/'. $row['ap_'. $form]);
				}			
			}
		}
		
		$this->M_a_apply->delete($ids, $this->M_a_apply->table . $cid);
		
		goto_url(URL);
	}
	
	// 신청 폼 리스트 업데이트
	function update_conf() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$apc_name = $this->input->post('apc_name');
		
		if(!$ids)
			return false;
	
		$data = array(
			'apc_name'		=> $apc_name,
			'apc_mdydate'	=> array_false($ids, FALSE, TIME_YMDHIS)
		);
		
		$this->M_a_apply->list_update($ids, $data, $this->M_a_apply->table_cate);
		
		goto_url(URL);
	}
	
	// 신청 폼 삭제
	function delete_conf() {
		check_token(URL);
		
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
		
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;
		
		$this->M_a_apply->delete($ids, $this->M_a_apply->table_cate);
		$this->M_a_apply->delete($ids, $this->M_a_apply->table_form, 'apf_cid');
		
		$this->load->dbforge();
		foreach($ids AS $id) {
			$this->dbforge->drop_table($this->M_a_apply->table . $id);
		}
		
		goto_url(URL);
	}
	
	function download() {
		$this->load->helper('download');
		
		$file = $this->input->get('file');
		
		if(!SU_ADMIN) {
			show_404();
		}
		
		$fileDir = $this->M_upload_files->get_dir('apply');
		$filepath = addslashes($fileDir['data_path'] .'/'. $file);
		
		if (file_exists($filepath)) {
			if (preg_match("/^utf/i", $this->config->item('charset')))
				$file = urlencode($file);
			
			if (!force_download($file, file_get_contents($filepath)))
				alert('파일을 찾을 수 없습니다.');
		}
		else
			alert('파일을 찾을 수 없습니다.');
	}
}
?>
