<?php
class Client extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_client', 'M_client'));
		$this->load->helper(array('admin', 'sideview', 'search', 'textual'));
		$this->load->library('pagination');
	}
	
	function lists() {
		include "init.php";
		
		$seg = new search_seg;
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'cl_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		$pageConf['suffix'] = $qstr;
		$pageConf['base_url'] = RT_PATH.'/'.ADM_F.'/client/lists/page/';
		$pageConf['per_page'] = 15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		$result = $this->M_client->list_result($sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i]['no'] = $result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] = $i % 2;
			$list[$i]['cl_memo'] = cut_str($list[$i]['cl_memo'], 15);
			
			$list[$i]['s_mod'] = icon('수정', 'client/form/u/'.$list[$i]['cl_id']."?qstr=$qstr");
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/client/delete', {id:'".$list[$i]['cl_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
		}
		
		$vars = array(
			'_TITLE_'			=> '고객관리',
			'_BODY_'			=> ADM_F.'/client/client_list',
			
			'token'				=> $token,
			'list'				=> $list,
			'qstr'				=> $qstr,
			's_add'				=> icon('추가', "client/form"),
			'sfl'				=> $sfl,
			'stx'				=> $stx,
			'total_cnt'			=> $result['total_cnt'],
			
			'paging'			=> $this->pagination->create_links(),
			
			'sort_cl_name'		=> sort_link('cl_name'),
			'sort_cl_mb_id'		=> sort_link('cl_mb_id'),
			'sort_cl_product'	=> sort_link('cl_product'),
			'sort_cl_price'		=> sort_link('cl_price')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w = '', $id = '') {
		include "init.php";
		
		$this->load->library('form_validation');
		
		$config = array(
				array('field'=>'cl_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[20]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_client->table);
			}
			else if ($w == 'u') {
				$title = '수정';
				$row = $this->M_client->row($id);
				if (!isset($row['cl_id']))
					alert('등록된 자료가 없습니다.', '/adm/client/lists');
			}
				
			$vars = array(
				'_TITLE_'			=> '고객관리',
				'_BODY_'			=> ADM_F.'/client/client_form',
				'_CSS_'				=> array('jquery-ui'),
				'_JS_'				=> array('jvalidate', 'jvalid_ext', 'jquery-ui.min', 'jtimepicker', 'layerDlg'),
				
				'w'					=> $w,
				'token'				=> get_token(),
				'row'				=> $row,
				'product'			=> $this->M_client->product
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			
			$w = $this->input->post('w');
			$cl_id = $this->input->post('cl_id');
			$delFile = $this->input->post('delFile');
			
			$row = $this->M_client->row($cl_id);
			
			if (!$w) {
				if (isset($row['cl_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w == 'u') {
				// what!?
			}
			else
				alert('잘못된 접근입니다.');
			
			$data = array(
					'cl_mb_id'		=> $this->input->post('cl_mb_id'),
					'cl_name'		=> $this->input->post('cl_name'),
					'cl_product'	=> $this->input->post('cl_product'),
					'cl_use_s'		=> $this->input->post('cl_use_s'),
					'cl_use_e'		=> $this->input->post('cl_use_e'),
					'cl_contract'	=> $this->input->post('cl_contract'),
					'cl_price'		=> $this->input->post('cl_price'),
					'cl_memo'		=> $this->input->post('cl_memo'),
					'cl_mdydate'	=> TIME_YMDHIS
			);
			
			$cl_id = $this->M_a_client->record($w, $data);
						
			goto_url(ADM_F.'/client/form/u/'.$cl_id);
		}
	}
	
	function delete() {
		check_token(URL);
		
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
	
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;
		
		$this->M_a_client->delete($ids);
		
		goto_url(URL);
	}
}
?>
