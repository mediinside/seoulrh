<?php
class Navigation extends CI_Controller {	
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_navigation');
		$this->load->model('M_navigation');
	}
	
	function lists() {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper('search');
		
		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sst  = $seg->get_seg('sst');
		$sod  = $seg->get_seg('sod');
		$sfl  = 'nv_name';
		$stx  = $seg->get_seg('stx');
		
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'nv_regdate';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/navigation/lists/page/';
		$config['per_page'] = 30;
		
		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_navigation->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$list = array();
		$token = get_token();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] = $row;
			
			$list[$i]['s_mod'] = icon('수정', 'navigation/form/u/'. $row['nv_id'] . $qstr);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/navigation/delete', {id:'".$row['nv_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['nv_regdate'] = date('Y-m-d', strtotime($row['nv_regdate']));
			$list[$i]['nv_hidden_chk'] = $row['nv_hidden'] ? "checked='checked'" : '';
		}
		
		$vars = array(
			'_BODY_'			=> ADM_F.'/navigation/navigation_list',
			
			'token'				=> $token,

			'list'				=> $list,
			's_add'				=> icon('추가', 'navigation/form?page='. $page .'&stx='. $stx),

			'page'				=> $page,
			'stx'				=> $stx,

			'total_cnt'			=> number_format($result['total_cnt']),
			'paging'			=> $this->pagination->create_links(),

			'sort_nv_subject'	=> sort_link('nv_subject'),
			'sort_nv_file'		=> sort_link('uf_count_image'),
			'sort_nv_hidden'	=> sort_link('nv_hidden'),
			'sort_nv_regdate'	=> sort_link('nv_regdate')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $nv_id='') {
		include "init.php";
		
		$this->load->library('form_validation');
		
		$config = array(
			array('field'=>'nv_id', 'label'=>'네비게이션 ID', 'rules'=>'trim|required|alpha_numeric_under|max_length[20]|xss_clean'),
			array('field'=>'nv_name', 'label'=>'네비게이션 이름', 'rules'=>'trim|required|max_length[20]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row['code'] = array();
			}
			else if ($w == 'u') {
				$title = '수정';
				
				$row = $this->M_navigation->row($id);
				if (!$row)
					alert('등록된 자료가 없습니다.');
			}
			
			$vars = array(
				'_TITLE_'		=> '신청서 폼 '.$title,
				'_BODY_'		=> ADM_F.'/navigation/navigation_form',
				'_JS_'			=> array('jvalidate', 'jvalid_ext'),
				
				'w'				=> $w,
				'token'			=> get_token(),
				
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			
			$w = $this->input->post('w');
			$id = $this->input->post('apc_id');
			$email = $this->input->post('apc_email');
			
			$row = $this->M_apply->row_form($id);
			
			if (!$w) {
				if (isset($row['ap_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$sql = array(
				'apc_id'		=> $id,
				'apc_name'		=> $this->input->post('apc_name'),
				'apc_layout'	=> $this->input->post('apc_layout'),
				'apc_email'		=> is_array($email) ? implode('|', $this->input->post('apc_email')) : '',
				'apc_mdydate'	=> TIME_YMDHIS
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
	
	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$nv_hidden = $this->input->post('nv_hidden');
		
		if(!$ids)
			return false;

		$data = array(
			'nv_hidden' => $nv_hidden
		);
		
		$this->M_a_navigation->list_update($ids, $data);
		
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
			$this->M_upload_files->file_delete($this->M_a_navigation->table, $i);
			$this->M_a_navigation->delete($i);
		}
		
		goto_url(URL);
	}
}
?>
