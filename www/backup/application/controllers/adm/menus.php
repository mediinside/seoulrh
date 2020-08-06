<?php
class Menus extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		include "init.php";
		
		$id = $this->input->get('id');
		$pid = $this->input->get('pid');
		
		$menu = $this->db->get_columns($this->M_a_menus->table);
		if($id) $menu = $this->M_a_menus->getInfo($id, 'a.*, b.am_name AS am_pname');
		else if($pid) $menu = array_merge($menu, $this->M_a_menus->row($pid, 'am_id AS am_pid,am_name AS am_pname'));
		
		$token = get_token();
		
		$vars = array(
			'_TITLE_'		=> '관리자 메뉴 설정',
			'_BODY_'		=> ADM_F.'/menus',
			'_CSS_'			=> array('table'),
			'_JS_'			=> array('jvalidate', 'jvalid_ext'),
			
			'token'			=> $token,
						
			'menu'			=> $menu
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function update() {
		$this->load->model(ADM_F.'/M_a_menus');
		$this->load->helper('admin');

		check_token();

		$w = $this->input->post('w');
		$am_id = $this->input->post('am_id');
		
		// 입력 점증 라이브러리
		$this->load->library('form_validation');
		
		// 입력 검증
		$config = array(
			array('field'=>'am_name', 'label'=>'메뉴명', 'rules'=>'trim|required|max_length[20]|xss_clean'),
		);
		
		$this->form_validation->set_rules($config);
		
		// 검증 결과
		if($this->form_validation->run() == FALSE)	//validation_errors() works too.
			alert('입력 규칙이 올바르지 않습니다.', ADM_F.'/menus?id='.$am_id);

		if (!$w) {
			$am = $this->M_a_menus->row($am_id, 'am_id');
			if (isset($am['am_id']))
				alert('이미 존재하는 ID 입니다.');
		}
		else if ($w == 'u') {
			// what!?
		}
		else
			alert('잘못된 접근입니다.');

		$data = array(
				'am_pid'	=> $this->input->post('am_pid'),
				'am_name'	=> $this->input->post('am_name'),
				'am_level'	=> $this->input->post('am_level'),
				'am_icon'	=> $this->input->post('am_icon'),
				'am_link'	=> $this->input->post('am_link'),
				'am_target'	=> $this->input->post('am_target'),
				'am_sort'	=> $this->input->post('am_sort')
		);
		
		$am_id = $this->M_a_menus->record($w, $data);
		
		alert('저장 되었습니다.', ADM_F.'/menus?id='.$am_id);
	}
	
	function delete() {
		$this->load->model(ADM_F.'/M_a_menus');
		$this->load->helper('admin');
		
		check_token();
		
		$id = $this->input->post('id');
		
		if($this->M_a_menus->delete($id))
			alert('삭제 되었습니다.', ADM_F.'/menus');
		else
			alert('삭제 실패!', ADM_F.'/menus?id='.$id);
	}
}
?>
