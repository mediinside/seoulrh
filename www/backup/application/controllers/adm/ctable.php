<?php
class Ctable extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_ctable', 'M_ctable'));
		$this->load->helper('search');
	}
	
	function lists() {
		include "init.php";

		$this->load->helper('form');
		
		// 서치
		$seg = new search_seg;
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');

		if (!$sst) $sst = 'cta_id';
		if (!$sod) $sod = 'asc';
		
		$token = get_token();
		
		$cate_result = $this->M_ctable->list_result_cate();
		if(!$cid = $this->input->get('cid')) {
			$cid = current(array_flip($cate_result));
		}
		
		$result = $this->M_a_ctable->list_result($cid, $sst, $sod, $sfl, $stx);
		$list = $result['qry'];
		
		foreach ($list as $i => $row) {
			$list[$i]['s_mod'] = icon('수정', 'javascript:add('.$row['cta_id'] .');');
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/ctable/delete', {cid:'".$cid."', id:'".$row['cta_id']."', token:'".$token."'}, true);");
			
			$list[$i]['no'] = $i + 1;
			$list[$i]['lst'] = $i % 2;
		}
		
		$vars = array(
			'_TITLE_'		=> '비급여항목 테이블',
			'_BODY_'		=> ADM_F.'/ctable/ctable_list',
			
			'token'			=> $token,
			'list'			=> $list,
			'cate_sel'		=> form_dropdown('cate', $cate_result, $cid)
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}

	function form($cid='') {
		include "init.php";
		
		$id = $this->input->get_post('id');
		
		if(!$cid && !$id) {
			show_404();
		}
		
		$this->load->library('form_validation');
		
		$config = array(
				array('field'=>'name', 'label'=>'수가액', 'rules'=>'trim|required|max_length[100]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$row = $this->db->get_columns($this->M_a_ctable->table);
			if(!$id) {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_ctable->table);
			}
			else {
				$title = '수정';
		
				$row = $this->M_a_ctable->row($id);
				if (!$row)
					alert('등록된 자료가 없습니다.');
			}
				
			$vars = array(
				'_TITLE_'		=> $title,
				'_BODY_'		=> ADM_F.'/ctable/ctable_form',
				'_JS_'			=> array('jvalidate'),
				
				'cid'			=> $cid,
				'row'			=> $row
			);
				
			$this->load->view('layout/layout_blank', $vars);
		}
		else {
			if($id) {
				if(!$row = $this->M_a_ctable->row($id))
					alert('등록된 데이터가 없습니다.');
			}
			
			$sql = array(
				'cta_name'		=> $this->input->post('name'),
				'cta_price'		=> $this->input->post('price'),
				'cta_memo'		=> $this->input->post('memo'),
				'cta_mdydate'	=> TIME_YMDHIS
			);
				
			$id = $this->M_a_ctable->record($cid, $id, $sql);
			
			alert_dlg_close('저장 되었습니다.', 'parent.location.href="/adm/ctable/lists?cid='. $cid .'";');
		}
	}
	
	function form_cate($id='') {
		include "init.php";
		
		$this->load->library('form_validation');
		
		$config = array(
				array('field'=>'subject', 'label'=>'분류명', 'rules'=>'trim|required|max_length[50]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if(!$id) {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_ctable->table_cate);
			}
			else {
				$title = '수정';
				
				$row = $this->M_a_ctable->row_cate($id);
				if (!$row)
					alert('등록된 자료가 없습니다.');
			}
			
			$vars = array(
				'_TITLE_'		=> $title,
				'_BODY_'		=> ADM_F.'/ctable/ctable_form_cate',
				'_JS_'			=> array('jvalidate'),

				'row'			=> $row
			);
			
			$this->load->view('layout/layout_blank', $vars);
		}
		else {
			if($id) {
				if(!$row = $this->M_a_ctable->row_cate($id))
					alert('등록된 데이터가 없습니다.');
			}
			
			$sql = array(
					'ctg_subject'		=> $this->input->post('subject'),
					'ctg_mdydate'		=> TIME_YMDHIS
			);
			
			$id = $this->M_a_ctable->record_cate($id, $sql);
			
			alert_dlg_close('저장 되었습니다.', 'parent.location.href="/adm/ctable/lists?cid='. $id .'";');
		}
	}
	
	function delete_cate() {
		$id = $this->input->post('id');
		
		if($row = $this->M_a_ctable->delete_cate($id)) {
			die('000');
		}
		else {
			die('001');
		}
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
		
		$this->M_a_ctable->delete($ids);
		
		goto_url(URL);
	}
}
?>
